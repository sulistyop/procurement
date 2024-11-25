<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;

class ParentPengajuanUserController extends Controller
{
    public function index()
    {
        $parentPengajuans = ParentPengajuan::all();
        return view('user.parent-pengajuan.index', compact('parentPengajuans'));
    }

    public function create()
    {
        $prodis = Prodi::all(); // Ambil semua data Prodi
        return view('user.parent-pengajuan.create', compact('prodis'));
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
    // Di dalam ParentPengajuanController.php
    public function view($id)
    {
        return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $id]);
    }
}
