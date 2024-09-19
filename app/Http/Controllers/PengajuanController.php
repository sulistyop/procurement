<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function index()
    {
        // Menampilkan semua data pengajuan
        $pengajuan = Pengajuan::all();
        $pengajuan = $pengajuan->map(function ($item) {
            // jika isbn pernah diajukan sebelum tahun sekarang berdasarkan created_at, jika diajukan lagi berikan mark bahwa buku tersebut pernah diajukan
            $item->is_diajukan = Pengajuan::where('isbn', $item->isbn)
                ->count() > 1;
            if($item->is_diajukan){
	            $item->date_pernah_diajukan = Pengajuan::where('isbn', $item->isbn)
		            ->orderBy('created_at','desc')
		            ->first()
		            ->created_at ?? null;
            }
            return $item;
        });

        return view('pengajuan.index', compact('pengajuan'));
    }

    public function create()
    {
        // Menampilkan form untuk menambah pengajuan baru
        return view('pengajuan.create');
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'prodi' => 'required|max:100',
            'judul' => 'required|max:255',
            'edisi' => 'nullable|max:50',
            'isbn' => 'required|max:20',
            'penerbit' => 'required|max:100',
            'author' => 'required|max:100',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'eksemplar' => 'required|integer',

        ]);

        // Simpan data pengajuan
        Pengajuan::create($request->all());

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function show(Pengajuan $pengajuan)
    {
        // Menampilkan detail pengajuan tertentu
        return view('pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        // Menampilkan form untuk mengedit pengajuan
        return view('pengajuan.edit', compact('pengajuan'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
        // Validasi input
        $request->validate([
            'prodi' => 'required|max:100',
            'judul' => 'required|max:255',
            'edisi' => 'nullable|max:50',
            'isbn' => 'required|max:20|unique:pengajuan,isbn,' . $pengajuan->id,
            'penerbit' => 'required|max:100',
            'author' => 'required|max:100',
            'tahun' => 'required|integer|min:1900|max:' . date('Y'),
            'eksemplar' => 'required|integer',
        ]);

        // Update data pengajuan
        $pengajuan->update($request->all());

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diupdate.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Hapus data pengajuan
        $pengajuan->delete();
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
    public function approve($id)
    {
		dd($id);
        $pengajuan = Pengajuan::findOrFail($id);
        return view('pengajuan.approve', compact('pengajuan'));
    }

    public function storeApproval(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'eksemplar' => 'required|integer|min:1',
        ]);

		$store = $pengajuan->update([
			'is_approve' => true,
			'approved_at' => now(),
			'approved_by' => Auth::user() ? Auth::user()->id : 0, // Sesuaikan dengan id pengguna yang menyetujui
		]);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil disetujui!');
    }
}
