<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanExport;
use App\Import\PengajuanImport;
use App\Models\Pengajuan;
use App\Models\Prodi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanController extends Controller
{
    public function index()
    {
        // Menampilkan semua data pengajuan
	    $pengajuan = Pengajuan::haveProdi()->get();
	    
	    $pengajuan = $pengajuan->map(function ($item) {
            // jika isbn pernah diajukan sebelum tahun sekarang berdasarkan created_at, jika diajukan lagi berikan mark bahwa buku tersebut pernah diajukan
            $item->is_diajukan = Pengajuan::where('isbn', $item->isbn)
	            ->where('isbn', '!=', null)
	            ->where('isbn', '!=', '-')
	            ->where('isbn', '!=', ' ')
	            ->where('prodi_id', $item->prodi_id)
                ->count() > 1;
            if($item->is_diajukan){
	            $item->date_pernah_diajukan = Pengajuan::where('isbn', $item->isbn)
		            ->orderBy('created_at','desc')
		            ->first()
		            ->created_at ?? null;
            }
			$item->nama_prodi = $item->prodi->nama;
			$item->prodi_id = $item->prodi->id;
            return $item;
        });
		
		if(request()->has('export')) {
			$excelReport = new PengajuanExport($pengajuan);
			$fileName = 'daftar_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
			return Excel::download($excelReport, $fileName);
		}
		
		$prodi = Prodi::all();

        return view('pengajuan.index', compact('pengajuan', 'prodi'));
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
		    'prodi_id' => 'required|exists:prodi,id',
		    'judul' => 'required|max:255',
		    'edisi' => 'nullable|max:50',
		    'isbn' => 'required|max:20',
		    'penerbit' => 'nullable|max:100',
		    'author' => 'required|max:100',
		    'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
		    'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0', // Tambahkan validasi untuk harga
	    ], [], [
		    'prodi_id' => 'Prodi',
		    'judul' => 'Judul',
		    'edisi' => 'Edisi',
		    'isbn' => 'ISBN',
		    'penerbit' => 'Penerbit',
		    'author' => 'Penulis',
		    'tahun' => 'Tahun',
		    'eksemplar' => 'Eksemplar',
			'diterima' => 'Diterima',
			'harga' => 'Harga',
	    ]);

        // Simpan data pengajuan
        Pengajuan::create($request->all());

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function show(Pengajuan $pengajuan)
    {
		if(Auth::user()->can('view pengajuan')) {
	        // Menampilkan detail pengajuan tertentu
	        return view('pengajuan.show', compact('pengajuan'));
		}else{
			return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki akses untuk melihat detail pengajuan.');
		}
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
            'prodi_id' => 'required|max:100',
            'judul' => 'required|max:255',
            'edisi' => 'nullable|max:50',
            'penerbit' => 'nullable|max:100',
            'author' => 'required|max:100',
            'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
            'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0', // Tambahkan validasi untuk harga
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
        $pengajuan = Pengajuan::findOrFail($id);
        return view('pengajuan.approve', compact('pengajuan'));
    }

    public function storeApproval(Request $request, Pengajuan $pengajuan)
    {
        $request->validate([
            'eksemplar' => 'required|integer|min:1',
			'harga' => 'nullable|numeric|min:0',
    		'reason' => 'required_if:action,reject|max:255', // Reason hanya wajib saat action adalah reject
        ]);

		// Setujui pengajuan
		$store = $pengajuan->update([
			'is_approve' => true,
			'approved_at' => now(),
			'diterima' => (int)$request->eksemplar,
			'harga' => $request->harga, // Simpan harga
			'approved_by' => Auth::user() ? Auth::user()->id : 0, // Sesuaikan dengan id pengguna yang menyetujui
		]);
		// if ($request->action === 'approve') {
		// 	// Logika jika pengajuan disetujui
		// 	$store = $pengajuan->update([
		// 		'is_approve' => true,
		// 		'is_reject' => false, // Pastikan is_reject di-set ke false
		// 		'approved_at' => now(),
		// 		'eksemplar' => (int)$request->eksemplar,
		// 		'harga' => $request->harga, // Simpan harga
		// 		'approved_by' => Auth::user() ? Auth::user()->id : 0, // Sesuaikan dengan id pengguna yang menyetujui
		// 	]);
		// } elseif ($request->action === 'reject') {
		// 	// Logika jika pengajuan ditolak
		// 	$store = $pengajuan->update([
		// 		'is_approve' => false,
		// 		'is_reject' => true, // Set is_reject menjadi true jika ditolak
		// 		'rejected_at' => now(), // Tambahkan timestamp jika pengajuan ditolak
		// 		'rejected_by' => Auth::user() ? Auth::user()->id : 0, // Id pengguna yang menolak
		// 		'reason' => $request->reason, // Tambahkan alasan penolakan jika ada
		// 	]);
		// }

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diproses!');
    }
	
	public function importForm()
	{
		return view('pengajuan.import');
	}
	
	public function import(Request $request)
	{
		$request->validate([
			'file' => 'required|mimes:xlsx,xls,csv',
		]);
		
		$import = new PengajuanImport;
		Excel::import($import, $request->file('file'));
		
		if ($import->failures()->isNotEmpty()) {
			return redirect()->route('pengajuan.importForm')
				->with('failures', $import->failures());
		}
		
		return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diimport.');
	}
	
}
