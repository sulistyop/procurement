<?php
namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use Illuminate\Support\Facades\Auth;
use App\Models\ApproveKeuanganParentPengajuan;

class ParentPengajuanUserController extends Controller
{
    public function index()
    {
        // Ambil user yang sedang login
        $user = auth()->user();
        
        // Ambil ParentPengajuan yang sesuai dengan prodi yang dimiliki oleh user
        $parentPengajuans = ParentPengajuan::where('prodi_id', $user->prodi_id)->get(); 
        foreach ($parentPengajuans as $item) {
            $item->canDelete = ApproveKeuanganParentPengajuan::where('parent_pengajuan_id', $item->id)->exists();
        }
        return view('user.parent-pengajuan.index', compact('parentPengajuans'));
    }

    public function create()
    {
        // Ambil Prodi berdasarkan prodi_id milik user yang sedang login
        $user = auth()->user();  // Ambil user yang sedang login
        $prodi = Prodi::where('id', $user->prodi_id)->get();  // Ambil Prodi yang sesuai dengan prodi_id user

        return view('user.parent-pengajuan.create', compact('prodi'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'prodi_id' => 'required|exists:prodi,id', // Validasi Prodi ID
        ]);
    
        ParentPengajuan::create([
            'nama' => $request->nama,
            'prodi_id' => $request->prodi_id, // Menyimpan prodi_id
        ]);
    
        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Parent Pengajuan berhasil disimpan.');
    }

    public function edit($id)
    {
        $parentPengajuan = ParentPengajuan::findOrFail($id);
        return view('user.parent-pengajuan.edit', compact('parentPengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $parentPengajuan = ParentPengajuan::findOrFail($id);
        $parentPengajuan->update($request->all());

        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        ParentPengajuan::findOrFail($id)->delete();
        return redirect()->route('user.parent-pengajuan.index')->with('success', 'Data berhasil dihapus.');
    }

    public function view($id)
    {
        // Ambil prodi_id yang dimiliki oleh user
        $prodiId = auth()->user()->prodi_id; // Asumsi: user memiliki kolom prodi_id
    
        return redirect()->route('home-index', [
            'parent_pengajuan_id' => $id,
            'prodi_id' => $prodiId
        ]);
    }
    
}
