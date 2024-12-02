<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use App\Services\PengajuanService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengajuanUserController extends Controller
{
    protected $pengajuanService;

    public function __construct(PengajuanService $pengajuanService)
    {
        $this->pengajuanService = $pengajuanService;
        $this->middleware('auth');
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
		
		$pengajuan = $pengajuanQuery->paginate(10); // Menampilkan 10 data per halaman
		
		return view('user.pengajuan.index', [
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
	
		$parent_id = $request->query('parent_pengajuan_id');
	
		$prodi_id = $request->query('prodi_id'); 

		$parents = ParentPengajuan::where('prodi_id', $prodi_id)->get();

		$parent = ParentPengajuan::find($parent_id);
		
		// Kirim data ke view
		return view('user.pengajuan.create', [
			'parents' => $parents,
			'parent_id' => $parent_id, 
			'prodi' => $prodi,          
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
		return redirect()->route('home-index', ['parent_pengajuan_id' => $request->parent_pengajuan_id])
						->with('success', 'Pengajuan berhasil ditambahkan.');
	}

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id); // Mengambil satu pengajuan berdasarkan ID
        $prodi = Prodi::all();
        return view('user.pengajuan.edit', compact('pengajuan', 'prodi'));
    }
    
	public function update(Request $request, Pengajuan $pengajuan)
	{
		// Validasi input
		$request->validate([
			'prodi_id' => 'required|exists:prodis,id|max:100',
			'judul' => 'required|max:255',
			'edisi' => 'nullable|max:50',
			'penerbit' => 'nullable|max:100',
			'author' => 'required|max:100',
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
			'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0',
		]);
	
		// Update data pengajuan
		$pengajuan->update($request->only(['prodi_id', 'judul', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar', 'diterima', 'harga']));
	
		// Log aktivitas
		$this->setLogActivity('Mengubah pengajuan', $pengajuan);
	
		// Redirect ke halaman pengajuan dengan parent_pengajuan_id
		$parentPengajuanId = $request->parent_pengajuan_id ?? 1; // Default ke 1 jika tidak ada
	
		return redirect()->route('home-index', ['parent_pengajuan_id' => $parentPengajuanId])
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
		return redirect()->route('home-index', ['parent_pengajuan_id' => $parentPengajuanId])
						 ->with('success', 'Pengajuan berhasil dihapus.');
	}
	
	public function show(Pengajuan $pengajuan)
	{
		$user = auth()->user();
	
		$parentPengajuan = ParentPengajuan::where('id', $pengajuan->parent_pengajuan_id)
										  ->where('prodi_id', $user->prodi_id)
										  ->first();
	
		if (!$parentPengajuan) {
			return redirect()->route('home')->with('error', 'Parent pengajuan tidak ditemukan.');
		}
	
		return view('user.pengajuan.show', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $parentPengajuan,
		]);
	}	
}
