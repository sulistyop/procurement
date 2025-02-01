<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use App\Models\ApproveKeuanganParentPengajuan;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;

class ParentPengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua Prodi untuk dropdown filter
        $prodis = Prodi::all();
    
        // Ambil daftar tahun yang ada berdasarkan data 'created_at' dari ParentPengajuan
        $years = ParentPengajuan::selectRaw('DISTINCT YEAR(created_at) as year')
                                ->orderByDesc('year')
                                ->pluck('year');
    
        // Ambil data ParentPengajuan dengan filter berdasarkan tahun dan prodi
        $parentPengajuans = ParentPengajuan::with('prodi')
            ->when($request->has('tahun') && $request->tahun != '', function($query) use ($request) {
                return $query->whereYear('created_at', $request->tahun);
            })
            ->when($request->has('prodi') && $request->prodi != '', function($query) use ($request) {
                return $query->where('prodi_id', $request->prodi);
            })
            ->get()
            ->map(function(ParentPengajuan$item){
                $item->hashId = Hashids::encode($item->id);
                $item->canDelete = !ApproveKeuanganParentPengajuan::where('parent_pengajuan_id', $item->id)->exists();
                return $item;
            });
    
        // Kirim data ke view
        return view('admin.parent-pengajuan.index', compact('parentPengajuans', 'prodis', 'years'));
    }
    
    public function create()
    {
        $prodis = Prodi::all(); // Ambil semua data Prodi
        return view('admin.parent-pengajuan.create', compact('prodis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodi,id', // Validasi Prodi ID
        ]);
    
        ParentPengajuan::create([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id, // Pastikan ini disimpan
        ]);
    
        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Pengajuan berhasil disimpan.');
    }
    

    public function edit($id)
    {
        $id = Hashids::decode($id)[0];
        $parentPengajuan = ParentPengajuan::findOrFail($id);

        if($parentPengajuan){
            $parentPengajuan = $parentPengajuan->first();
        }
        return view('admin.parent-pengajuan.edit', compact('parentPengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $id = Hashids::decode($id)[0];
        $parentPengajuan = ParentPengajuan::findOrFail($id);
        $parentPengajuan->update($request->all());

        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy($hashId)
    {
        $parentId = Hashids::decode($hashId);
        $parentPengajuan = ParentPengajuan::where('id', $parentId)->first();
        
        if(!$parentPengajuan){
            return redirect()->back()->with('error', 'Data Parent Tidak ada');
        }
    
        // Ambil prodi_id dari ParentPengajuan
        $prodiId = $parentPengajuan->prodi_id;
    
        // Periksa apakah ada pengajuan yang terkait dengan ParentPengajuan dan Prodi yang sudah di-approve
        $approvedPengajuan = Pengajuan::where('parent_pengajuan_id', $parentId)
                                       ->where('prodi_id', $prodiId)
                                       ->where('is_approve', '1') 
                                       ->exists();
        if ($approvedPengajuan) {
            return redirect()->route('admin.parent-pengajuan.index')
                             ->with('error', 'Tidak bisa menghapus, karena ada pengajuan yang sudah di-approve.');
        }
    
        // Hapus semua pengajuan yang terkait dengan parent_pengajuan_id dan prodi_id ini
        Pengajuan::where('parent_pengajuan_id', $parentId)
                 ->where('prodi_id', $prodiId) // Pastikan pengajuan yang dihapus sesuai dengan prodi_id
                 ->delete();
    
        // Hapus ParentPengajuan itu sendiri
        $parentPengajuan->delete();
    
        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Data Pengajuan beserta Pengajuannya berhasil dihapus.');
    }
    
    public function view($id)
    {
        try {
            // Dekripsi ID
            $decryptedId = Hashids::decode($id);

            $request = new Request();
            $request->merge(['parent_pengajuan_id' => $decryptedId[0]]);

            return app(PengajuanController::class)->index($request);

        } catch (\Exception $e) {
            Log::error('Gagal mendekripsi ID: ' . $e->getMessage());
            
            return redirect()->route('admin.parent-pengajuan.index')->withErrors('ID tidak valid atau telah kadaluarsa');
        }
    }
    
}
