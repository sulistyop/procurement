<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use App\Models\ApproveKeuanganParentPengajuan;

class ParentPengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Ambil semua Prodi untuk dropdown filter
        $prodis = Prodi::all();
        
        // Set default prodi ID ke 1 (Perpustakaan) jika tidak ada filter
        $selectedProdi = $request->get('prodi', 1); // Default ke 1 (Perpustakaan)
        
        // Query untuk menampilkan Parent Pengajuan, filter berdasarkan Prodi jika ada
        $parentPengajuans = ParentPengajuan::with('prodi') // Pastikan sudah ada relasi 'prodi'
            ->when($selectedProdi, function($query) use ($selectedProdi) {
                return $query->where('prodi_id', $selectedProdi);
            })
            ->get();
    
        foreach ($parentPengajuans as $item) {
            $item->canDelete = !ApproveKeuanganParentPengajuan::where('parent_pengajuan_id', $item->id)->exists();
        }
        
        // Kirim data ke view
        return view('admin.parent-pengajuan.index', compact('parentPengajuans', 'prodis'));
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
    
        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Parent Pengajuan berhasil disimpan.');
    }
    

    public function edit($id)
    {
        $parentPengajuan = ParentPengajuan::findOrFail($id);
        return view('admin.parent-pengajuan.edit', compact('parentPengajuan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $parentPengajuan = ParentPengajuan::findOrFail($id);
        $parentPengajuan->update($request->all());

        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        $parentPengajuan = ParentPengajuan::findOrFail($id);
    
        // Ambil prodi_id dari ParentPengajuan
        $prodiId = $parentPengajuan->prodi_id;
    
        // Periksa apakah ada pengajuan yang terkait dengan ParentPengajuan dan Prodi yang sudah di-approve
        $approvedPengajuan = Pengajuan::where('parent_pengajuan_id', $id)
                                       ->where('prodi_id', $prodiId)
                                       ->where('is_approve', '1') 
                                       ->exists();
        if ($approvedPengajuan) {
            return redirect()->route('admin.parent-pengajuan.index')
                             ->with('error', 'Tidak bisa menghapus, karena ada pengajuan yang sudah di-approve.');
        }
    
        // Hapus semua pengajuan yang terkait dengan parent_pengajuan_id dan prodi_id ini
        Pengajuan::where('parent_pengajuan_id', $id)
                 ->where('prodi_id', $prodiId) // Pastikan pengajuan yang dihapus sesuai dengan prodi_id
                 ->delete();
    
        // Hapus ParentPengajuan itu sendiri
        $parentPengajuan->delete();
    
        return redirect()->route('admin.parent-pengajuan.index')->with('success', 'Data Parent Pengajuan beserta Pengajuannya berhasil dihapus.');
    }
    
    // Di dalam ParentPengajuanController.php
    public function view($id)
    {
        return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $id]);
    }
}
