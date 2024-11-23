<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Import\PengajuanImport;
use App\Models\ParentPengajuan;
use App\Exports\PengajuanExport;
use App\Services\PengajuanService;
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
		$parents = ParentPengajuan::all();
		$user = Auth::user();
		$prodi = Prodi::when($user->prodi_id, function($query) use ($user){
			return $query->where('id', $user->prodi_id);
		})->get();
		
		// Ambil parent_pengajuan_id dari query string
		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); // Temukan ParentPengajuan berdasarkan ID jika ada
		
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.index')->with('error', 'Parent tidak ditemukan.');
		}
	
		// Query untuk mengambil data Pengajuan sesuai dengan parent_pengajuan_id
		$pengajuanQuery = Pengajuan::with('prodi');
		
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		
		$pengajuan = $pengajuanQuery->get();
		
		return view('admin.pengajuan.index', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => $parents,
			'prodi' => $prodi,
			'idParent' => $idParent, // Kirim idParent ke view untuk dipakai di select
		]);
	}
	
	public function create(Request $request)
	{
		// Ambil data Prodi yang ada
		$prodi = Prodi::all();
		
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
	
		// Redirect kembali ke halaman pengajuan dengan success
		return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil ditambahkan.');
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
		
		$this->setLogActivity('Mengubah pengajuan', $pengajuan);

        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil diupdate.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Hapus data pengajuan
	    $dump = $pengajuan;
        $pengajuan->delete();
		$this->setLogActivity('Menghapus pengajuan', $dump);
        return redirect()->route('pengajuan.index')->with('success', 'Pengajuan berhasil dihapus.');
    }
    public function approve($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        return view('admin.pengajuan.approve', compact('pengajuan'));
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
			Excel::import(new PengajuanImport, $request->file('file'));
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
        ->where(function($query) {
            $query->where('is_approve', 0) // Status Proses
                  ->orWhere('is_reject', 1); // Status Ditolak
        });
		
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
}
