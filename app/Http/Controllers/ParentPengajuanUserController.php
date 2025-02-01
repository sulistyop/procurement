<?php
namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use Illuminate\Support\Facades\Auth;
use App\Models\ApproveKeuanganParentPengajuan;
use Vinkla\Hashids\Facades\Hashids;

class ParentPengajuanUserController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $parentPengajuans = ParentPengajuan::where('prodi_id', $user->prodi_id)
        ->get();
        foreach ($parentPengajuans as $item) {
            $item->canDelete = !ApproveKeuanganParentPengajuan::where('parent_pengajuan_id', $item->id)->exists();
        }
        return view('user.parent-pengajuan.index', compact('parentPengajuans'));
    }

    public function create()
    {
        $user = auth()->user(); 
        $prodi = Prodi::where('id', $user->prodi_id)->get();  

        return view('user.parent-pengajuan.create', compact('prodi'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodi,id', 
        ]);
    
        ParentPengajuan::create([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id, 
        ]);
    
        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Pengajuan berhasil disimpan.');
    }

    public function edit($hashId)
    {
        $id = Hashids::decode($hashId);
        $parentPengajuan = ParentPengajuan::find($id)->first();
        return view('user.parent-pengajuan.edit', compact('parentPengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);
        $id = Hashids::decode($id)[0];
        $parentPengajuan = ParentPengajuan::findOrFail($id);
        $parentPengajuan->update($request->all());

        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Data berhasil diubah.');
    }

    // public function destroy($id)
    // {
    //     ParentPengajuan::findOrFail($id)->delete();
    //     return redirect()->route('user.parent-pengajuan.index')->with('success', 'Data berhasil dihapus.');
    // }
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
            return redirect()->route('user.parent-pengajuan.index')
                             ->with('error', 'Tidak bisa menghapus, karena ada pengajuan yang sudah di-approve.');
        }
    
        // Hapus semua pengajuan yang terkait dengan parent_pengajuan_id dan prodi_id ini
        Pengajuan::where('parent_pengajuan_id', $parentId)
                 ->where('prodi_id', $prodiId) // Pastikan pengajuan yang dihapus sesuai dengan prodi_id
                 ->delete();
    
        // Hapus ParentPengajuan itu sendiri
        $parentPengajuan->delete();
    
        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Data Pengajuan beserta usulan buku berhasil dihapus.');
    }
    
    public function view($hashId)
    {
        $parentId = Hashids::decode($hashId);

        $request = new Request();

        $prodiId = auth()->user()->prodi_id; 
        $request->merge([
            'parent_pengajuan_id' => $parentId[0],
            'prodi_id'=> Hashids::encode($prodiId)
        ]);

        // Ambil prodi_id yang dimiliki oleh user
    
        return app(PengajuanUserController::class)->index($request);
       
    }
}
