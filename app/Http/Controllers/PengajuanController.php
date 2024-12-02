<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Import\PengajuanImport;
use App\Models\ParentPengajuan;
use App\Exports\PengajuanExport;
use App\Services\PengajuanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanController extends Controller
{
	protected $pengajuanService;
	
	public function __construct(PengajuanService $pengajuanService)
	{
		$this->pengajuanService = $pengajuanService;
	}
	
	public function index(Request $request)
	{
		// Ambil parent_pengajuan_id dari query string
		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); // Temukan ParentPengajuan berdasarkan ID jika ada
		
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.index')->with('error', 'Parent tidak ditemukan.');
		}
		
		// Ambil prodi_id dari ParentPengajuan yang dipilih
		$prodi = Prodi::when($selectedParent, function($query) use ($selectedParent) {
			return $query->where('id', $selectedParent->prodi_id); // Filter Prodi berdasarkan prodi_id dari ParentPengajuan
		})->get();
	
		// Query untuk mengambil data Pengajuan sesuai dengan parent_pengajuan_id
		$pengajuanQuery = Pengajuan::with('prodi');
		
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		
		$pengajuan = $pengajuanQuery->get();
		
		return view('admin.pengajuan.index', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => ParentPengajuan::all(),
			'prodi' => $prodi,
			'idParent' => $idParent, // Kirim idParent ke view untuk dipakai di select
		]);
	}
	
	public function create(Request $request)
	{
		$user = Auth::user();
		$prodi = Prodi::when($user->prodi_id, function($query) use ($user){
			return $query->where('id', $user->prodi_id);
		})->get();
		
		// Ambil parent_id dari query parameter
		$parent_id = $request->query('parent_pengajuan_id');
		
		// Ambil prodi_id yang dipilih
		$prodi_id = $request->query('prodi_id'); // Bisa juga dari request form jika ada
		
		// Ambil ParentPengajuan berdasarkan prodi_id
		$parents = ParentPengajuan::where('prodi_id', $prodi_id)->get();
		
		// Ambil data ParentPengajuan berdasarkan parent_id
		$parent = ParentPengajuan::find($parent_id);
		
		// Kirim data ke view
		return view('admin.pengajuan.create', [
			'parents' => $parents,
			'parent_id' => $parent_id,  // Kirimkan parent_id
			'prodi' => $prodi,          // Kirim data prodi jika diperlukan
			'parent' => $parent,
		]);
	}
	
	public function store(Request $request)
	{
		// Validasi input
		$request->validate([
			'prodi_id' => 'required|exists:prodi,id',
			'judul' => 'required|max:255',
			'edisi' => 'nullable|max:50',
			'isbn' => 'nullable|max:20',
			'penerbit' => 'nullable|max:100',
			'author' => 'required|max:100',
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
			'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0',
			'parent_pengajuan_id' => 'nullable|exists:parent_pengajuan,id',  // Validasi parent_id
		]);
	
		// Ambil data request
		$data = $request->all();
	
		// Simpan pengajuan dengan parent_pengajuan_id
		$pengajuan = Pengajuan::create($data);

		// Log activity
		$this->setLogActivity('Membuat pengajuan', $pengajuan);

		// Redirect kembali ke halaman pengajuan dengan success, tambahkan query string parent_pengajuan_id
		return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $request->parent_pengajuan_id])
						->with('success', 'Pengajuan berhasil ditambahkan.');
	}
	
    public function show(Pengajuan $pengajuan)
    {
		if(Auth::user()->can('view pengajuan')) {
	        // Menampilkan detail pengajuan tertentu
	        return view('admin.pengajuan.show', compact('pengajuan'));
		}else{
			return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki akses untuk melihat detail pengajuan.');
		}
    }

    public function edit(Pengajuan $pengajuan)
    {
        // Menampilkan form untuk mengedit pengajuan
        return view('admin.pengajuan.edit', compact('pengajuan'));
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
	
		// Log aktivitas
		$this->setLogActivity('Mengubah pengajuan', $pengajuan);
	
		// Redirect ke halaman pengajuan dengan parent_pengajuan_id
		$parentPengajuanId = $request->parent_pengajuan_id ?? 1; // Default ke 1 jika tidak ada
	
		return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $parentPengajuanId])
						 ->with('success', 'Pengajuan berhasil diupdate.');
	}
	
	public function destroy(Request $request, Pengajuan $pengajuan)
	{
		// Ambil parent_pengajuan_id sebelum data dihapus
		$parentPengajuanId = $request->query('parent_pengajuan_id') ?? $pengajuan->parent_pengajuan_id ?? 1;
	
		// Validasi keberadaan parent_pengajuan_id
		$isParentExists = DB::table('parent_pengajuan')->where('id', $parentPengajuanId)->exists();
	
		if (!$isParentExists) {
			return redirect()->route('pengajuan.index')
							 ->with('error', 'Parent pengajuan tidak ditemukan.');
		}
	
		// Hapus data pengajuan
		$dump = $pengajuan;
		$pengajuan->delete();
	
		$this->setLogActivity('Menghapus pengajuan', $dump);
	
		// Redirect dengan menambahkan parent_pengajuan_id ke URL
		return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $parentPengajuanId])
						 ->with('success', 'Pengajuan berhasil dihapus.');
	}
	
	public function storeApproval(Request $request, Pengajuan $pengajuan)
	{
		// Validasi input
		$request->validate([
			'harga' => 'required_if:action,approve|numeric',
			'reason' => 'required_if:action,reject|max:255', // Reason hanya wajib saat action adalah reject
			'isbn' => 'nullable|max:20',
			'judul' => 'nullable|max:255', // Validasi untuk judul
			'edisi' => 'nullable|max:50', // Validasi untuk edisi
			'penerbit' => 'nullable|max:100', // Validasi untuk penerbit
			'author' => 'nullable|max:100', // Validasi untuk author
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'), // Validasi untuk tahun
			'eksemplar' => 'nullable|integer', // Validasi untuk eksemplar
			'diterima' => 'nullable|integer',
		]);
	
		// Array untuk menyimpan data yang akan diupdate
		$data = [
			'is_approve' => $request->action === 'approve',
			'is_reject' => $request->action === 'reject',
			'diterima' => $request->action === 'approve' ? (int)$request->diterima : 0,
			'approved_by' => $request->action === 'approve' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'rejected_by' => $request->action === 'reject' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'reason' => $request->action === 'reject' ? $request->reason : null,
		];
	
		// Jika pengajuan disetujui
		if ($request->action === 'approve') {
			$data['approved_at'] = now();
			$data['harga'] = $request->harga; // Simpan harga
		} 
		// Jika pengajuan ditolak
		elseif ($request->action === 'reject') {
			$data['rejected_at'] = now(); // Tambahkan timestamp jika pengajuan ditolak
		}
	
		// Melakukan pembaruan
		$pengajuan->update(array_merge($data, $request->only(['judul', 'isbn', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar'. 'diterima'])));
	
		// Menetapkan aktivitas log
		$this->setLogActivity($request->action === 'approve' ? 'Menyetujui pengajuan' : 'Menolak pengajuan', $pengajuan);
		
		return response()->json(['message' => $request->action === 'approve' ? 'Pengajuan berhasil disetujui!' : 'Pengajuan berhasil ditolak!']);
	}
	
	public function importForm()
	{
		return view('admin.pengajuan.import');
	}
	
	public function import(Request $request)
	{
		try {
			$parentPengajuanId = $request->get('parent_pengajuan_id');

			Excel::import(new PengajuanImport($parentPengajuanId), $request->file('file'));
			$this->setLogActivity('Import data pengajuan', new Pengajuan());
	
			return redirect()->back()->with('success', 'Import berhasil.');
		} catch (\Exception $e) {
			return redirect()->back()->with('error', 'Terjadi kesalahan saat import.');
		}
	}	
	
	public function proses(Request $request)
	{    $parents = ParentPengajuan::all();
		$prodi = Prodi::all();
		
		// Ambil parent_pengajuan_id dari query string
		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); // Temukan ParentPengajuan berdasarkan ID jika ada
		
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.proses')->with('error', 'Parent tidak ditemukan.');
		}
		
		// Query untuk mengambil data Pengajuan sesuai dengan parent_pengajuan_id dan is_approve = 0
		$pengajuanQuery = Pengajuan::with('prodi')
        ->where('is_approve', 0)  // Status belum diproses
        ->where('is_reject', 0);  // Status tidak ditolak
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		
		$pengajuan = $pengajuanQuery->get();
		
		return view('admin.pengajuan.proses', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => $parents,
			'prodi' => $prodi,
			'idParent' => $idParent, // Kirim idParent ke view untuk dipakai di select
		]);
	}
	public function tolak(Request $request)
	{
		$parents = ParentPengajuan::all();
		$prodi = Prodi::all();
	  
		// Ambil parent_pengajuan_id dari query string
		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); // Temukan ParentPengajuan berdasarkan ID jika ada
	  
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.proses')->with('error', 'Parent tidak ditemukan.');
		}
	  
		// Filter tanggal (default ke tanggal sekarang)
		$fromDate = $request->query('from_date', Carbon::now()->startOfDay()); // Tanggal mulai
		$toDate = $request->query('to_date', Carbon::now()->endOfDay()); // Tanggal akhir
	  
		// Pastikan fromDate dan toDate adalah objek Carbon
		$fromDate = Carbon::parse($fromDate);
		$toDate = Carbon::parse($toDate);
	  
		// Query untuk mengambil data Pengajuan sesuai dengan parent_pengajuan_id, is_approve = 0 dan is_reject = 1
		$pengajuanQuery = Pengajuan::with('prodi')
			->where('is_approve', 0) // Status Proses
			->where('is_reject', 1); // Status Ditolak
	  
		// Filter berdasarkan Parent Pengajuan jika ada
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
	  
		// Filter berdasarkan rentang tanggal
		$pengajuanQuery->whereBetween('created_at', [$fromDate, $toDate]);
	  
		// Gunakan paginate (misalnya 10 per halaman)
		$pengajuan = $pengajuanQuery->paginate(10);
	  
		return view('admin.pengajuan.tolak', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => $parents,
			'prodi' => $prodi,
			'idParent' => $idParent, // Kirim idParent ke view untuk dipakai di select
			'fromDate' => $fromDate->format('Y-m-d'), // Kirim fromDate ke view
			'toDate' => $toDate->format('Y-m-d'), // Kirim toDate ke view
		]);
	}
	
}
