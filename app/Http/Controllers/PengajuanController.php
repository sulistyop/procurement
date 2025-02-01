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
use Vinkla\Hashids\Facades\Hashids;

class PengajuanController extends Controller
{
	protected $pengajuanService;
	
	public function __construct(PengajuanService $pengajuanService)
	{
		$this->pengajuanService = $pengajuanService;
	}
	
	public function index(Request $request)
	{
		// Mendapatkan parent_pengajuan_id dari query parameter
		$idParent = $request->query('parent_pengajuan_id');
		
		// Mencari parent_pengajuan yang sesuai dengan idParent
		$selectedParent = ParentPengajuan::find($idParent); 
	
		// Jika parent tidak ditemukan, redirect ke halaman pengajuan
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.index')->with('error', 'Parent tidak ditemukan.');
		}
	
		// Mengambil data prodi berdasarkan selectedParent jika ada
		$prodi = Prodi::when($selectedParent, function($query) use ($selectedParent) {
			return $query->where('id', $selectedParent->prodi_id); 
		})->get();
	
		// Query untuk mengambil data pengajuan
		$pengajuanQuery = Pengajuan::with('prodi');
		
		// Jika ada idParent, filter pengajuan berdasarkan parent_pengajuan_id
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		
		// Ambil data pengajuan berdasarkan query
		$pengajuan = $pengajuanQuery->get();
	
		// Mapping untuk menambahkan detail dan menandai apakah sudah diajukan
		$pengajuan = $pengajuan->map(function ($item) {
			// Menandai apakah ISBN pernah diajukan
			$item->is_diajukan = Pengajuan::where('isbn', $item->isbn)
				->where('isbn', '!=', null)
				->where('isbn', '!=', '-')
				->where('isbn', '!=', ' ')
				->where('prodi_id', $item->prodi_id)
				->count() > 1;
	
			// Jika sudah diajukan, ambil tanggal pengajuan terakhir
			if ($item->is_diajukan) {
				$item->date_pernah_diajukan = Pengajuan::where('isbn', $item->isbn)
					->orderBy('created_at', 'desc')
					->first()
					->created_at ?? null;
			}
	
			// Menambahkan informasi prodi ke setiap item
			$item->nama_prodi = $item->prodi->nama;
			$item->prodi_id = $item->prodi->id;
			
			return $item;
		})->map(function(Pengajuan $item){
			$item->hashId = Hashids::encode($item->id);
			return $item;
		});

		// Mengecek apakah ada permintaan untuk export
		if ($request->has('export')) {
			$excelReport = new PengajuanExport($pengajuan);
			$fileName = 'pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
			return Excel::download($excelReport, $fileName);
		}
	
		// Kembalikan tampilan dengan data pengajuan dan lainnya
		return view('admin.pengajuan.index', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => ParentPengajuan::all(),
			'prodi' => $prodi,
			'idParent' => $idParent, 
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
		return view('admin.pengajuan.create', [
			'parents' => $parents,
			'parent_id' => $parent_id,  
			'prodi' => $prodi,          
			'parent' => $parent,
		]);
	}
	
	public function store(Request $request)
	{

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

		$data = $request->all();

		$pengajuan = Pengajuan::create($data);

		$this->setLogActivity('Membuat pengajuan', $pengajuan);

		return redirect()->route('pengajuan.index', ['parent_pengajuan_id' => $request->parent_pengajuan_id])
						->with('success', 'Pengajuan berhasil ditambahkan.');
	}
	
	public function show($hashId)
	{
		$id = Hashids::decode($hashId)[0];

		$pengajuan = Pengajuan::find($id);
	
		// Mencari ParentPengajuan berdasarkan kriteria
		$parentPengajuan = ParentPengajuan::where('id', $pengajuan->parent_pengajuan_id)
			->where('prodi_id', $pengajuan->prodi_id)
			->first();

		$parentPengajuan->hashParentId = Hashids::encode($parentPengajuan->id);
	
		// Jika ParentPengajuan tidak ditemukan, bisa redirect atau menampilkan pesan error
		if (!$parentPengajuan) {
			return redirect()->route('pengajuan.index')->with('error', 'Parent Pengajuan tidak ditemukan.');
		}
	
		return view('admin.pengajuan.show', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $parentPengajuan,
		]);
	}
	

    public function edit(Pengajuan $pengajuan)
    {
        return view('admin.pengajuan.edit', compact('pengajuan'));
    }

	public function update(Request $request, $hashId)
	{
		$request->validate([
			'prodi_id' => 'required|max:100',
			'judul' => 'required|max:255',
			'edisi' => 'nullable|max:50',
			'penerbit' => 'nullable|max:100',
			'author' => 'required|max:100',
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
			'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0', 
		]);

		// 1. Decrypt Hash Id menjadi -> id
		$idPengajuan = Hashids::decode($hashId)[0];
		
		// 2. Query ke model dengan id yang sudah didekrip
		$pengajuan = Pengajuan::find($idPengajuan);

		$pengajuan->update($request->all());

		$this->setLogActivity('Mengubah pengajuan', $pengajuan);

		$parentPengajuanId = Hashids::encode($request->parent_pengajuan_id) ?? 1; 
	
		return $this->redirectSuccess('admin.parent-pengajuan.view', 'Pengajuan berhasil diupdate' ,$parentPengajuanId);
	}
	
	public function destroy(Request $request, $hashId)
	{
		$idPengajuan = Hashids::decode($hashId)[0];
		$pengajuan = Pengajuan::find($idPengajuan);

		$parentPengajuanId = $request->query('parent_pengajuan_id') ?? $pengajuan->parent_pengajuan_id ?? 1;

		$isParentExists = DB::table('parent_pengajuan')->where('id', $parentPengajuanId)->exists();
	
		$parentPengajuanId = Hashids::encode($parentPengajuanId);
	
		if (!$isParentExists) {
			return redirect()->route('pengajuan.index')
							 ->with('error', 'Parent pengajuan tidak ditemukan.');
		}

		$dump = $pengajuan;
		$pengajuan->delete();
	
		$this->setLogActivity('Menghapus pengajuan', $dump);

		return $this->redirectSuccess('admin.parent-pengajuan.view', 'Pengajuan berhasil dihapus', $parentPengajuanId);
	}
	
	public function storeApproval(Request $request, $hashId)
	{
		$request->validate([
			'harga' => 'required_if:action,approve|numeric',
			'reason' => 'required_if:action,reject|max:255', 
			'isbn' => 'nullable|max:20',
			'judul' => 'nullable|max:255', 
			'edisi' => 'nullable|max:50', 
			'penerbit' => 'nullable|max:100', 
			'author' => 'nullable|max:100', 
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'), 
			'eksemplar' => 'nullable|integer', 
			'diterima' => 'nullable|integer',
		]);
	
		$data = [
			'is_approve' => $request->action === 'approve',
			'is_reject' => $request->action === 'reject',
			'diterima' => $request->action === 'approve' ? (int)$request->diterima : 0,
			'approved_by' => $request->action === 'approve' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'rejected_by' => $request->action === 'reject' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'reason' => $request->action === 'reject' ? $request->reason : null,
		];

		if ($request->action === 'approve') {
			$data['approved_at'] = now();
			$data['harga'] = $request->harga; 
		} 
		elseif ($request->action === 'reject') {
			$data['rejected_at'] = now(); 
		}
		$idPengajuan = Hashids::decode($hashId)[0];
		$pengajuan = Pengajuan::find($idPengajuan);

		$pengajuan->update(array_merge($data, $request->only(['judul', 'isbn', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar'. 'diterima'])));

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

		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); 
		
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.proses')->with('error', 'Parent tidak ditemukan.');
		}

		$pengajuanQuery = Pengajuan::with('prodi')
        ->where('is_approve', 0)  
        ->where('is_reject', 0);  
		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		
		$pengajuan = $pengajuanQuery->get();
		$pengajuan = $pengajuan->map(function ($item) {
			// Menandai apakah ISBN pernah diajukan
			$item->is_diajukan = Pengajuan::where('isbn', $item->isbn)
				->where('isbn', '!=', null)
				->where('isbn', '!=', '-')
				->where('isbn', '!=', ' ')
				->where('prodi_id', $item->prodi_id)
				->count() > 1;
	
			// Jika sudah diajukan, ambil tanggal pengajuan terakhir
			if ($item->is_diajukan) {
				$item->date_pernah_diajukan = Pengajuan::where('isbn', $item->isbn)
					->orderBy('created_at', 'desc')
					->first()
					->created_at ?? null;
			}
	
			// Menambahkan informasi prodi ke setiap item
			$item->nama_prodi = $item->prodi->nama;
			$item->prodi_id = $item->prodi->id;
			
			return $item;
		});
		return view('admin.pengajuan.proses', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => $parents,
			'prodi' => $prodi,
			'idParent' => $idParent, 
		]);
	}
	public function tolak(Request $request)
	{
		$parents = ParentPengajuan::all();
		$prodi = Prodi::all();

		$idParent = $request->query('parent_pengajuan_id');
		$selectedParent = ParentPengajuan::find($idParent); 

		$prodiId = $request->query('prodi_id'); 
		if ($idParent && !$selectedParent) {
			return redirect()->route('pengajuan.proses')->with('error', 'Parent tidak ditemukan.');
		}

		$fromDate = $request->query('from_date', Carbon::now()->startOfDay()); 
		$toDate = $request->query('to_date', Carbon::now()->endOfDay()); 

		$fromDate = Carbon::parse($fromDate);
		$toDate = Carbon::parse($toDate);
	  
		$pengajuanQuery = Pengajuan::with('prodi')
			->where('is_approve', 0) 
			->where('is_reject', 1); 

		if ($idParent) {
			$pengajuanQuery->where('parent_pengajuan_id', $idParent);
		}
		if ($prodiId) {
			$pengajuanQuery->where('prodi_id', $prodiId);
		}
		$pengajuanQuery->whereBetween('created_at', [$fromDate, $toDate]);

		$pengajuan = $pengajuanQuery->paginate(10);
		
		if ($request->has('export')) {
            $excelReport = new PengajuanExport($pengajuan);
            $fileName = 'pengajuan_tolak_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download($excelReport, $fileName);
        }
		return view('admin.pengajuan.tolak', [
			'pengajuan' => $pengajuan,
			'parentPengajuan' => $selectedParent,
			'parents' => $parents,
			'prodi' => $prodi,
			'idParent' => $idParent, 
			'fromDate' => $fromDate->format('Y-m-d'), 
			'toDate' => $toDate->format('Y-m-d'), 
		]);
	}
	//dashboard
	public function updatedas(Request $request, Pengajuan $pengajuan)
	{
		$request->validate([
			'prodi_id' => 'required|max:100',
			'judul' => 'required|max:255',
			'edisi' => 'nullable|max:50',
			'penerbit' => 'nullable|max:100',
			'author' => 'required|max:100',
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'),
			'eksemplar' => 'required|integer',
			'diterima' => 'nullable|integer',
			'harga' => 'nullable|numeric|min:0', 
		]);

		$pengajuan->update($request->all());

		$this->setLogActivity('Mengubah pengajuan', $pengajuan);

		$parentPengajuanId = $request->parent_pengajuan_id ?? 1; 

		return redirect()->route('pengajuan.proses')
						->with('success', 'Pengajuan berhasil diupdate.');
	} 
	public function destroydas(Request $request, Pengajuan $pengajuan)
	{
		$parentPengajuanId = $request->query('parent_pengajuan_id') ?? $pengajuan->parent_pengajuan_id ?? 1;

		$isParentExists = DB::table('parent_pengajuan')->where('id', $parentPengajuanId)->exists();
	
		if (!$isParentExists) {
			return redirect()->route('pengajuan.proses')
							 ->with('error', 'Parent pengajuan tidak ditemukan.');
		}

		$dump = $pengajuan;
		$pengajuan->delete();
	
		$this->setLogActivity('Menghapus pengajuan', $dump);

		return redirect()->route('pengajuan.proses')
						 ->with('success', 'Pengajuan berhasil dihapus.');
	}
	
	public function storeApprovalDas(Request $request, $hashId)
	{
		$request->validate([
			'harga' => 'required_if:action,approve|numeric',
			'reason' => 'required_if:action,reject|max:255', 
			'isbn' => 'nullable|max:20',
			'judul' => 'nullable|max:255', 
			'edisi' => 'nullable|max:50', 
			'penerbit' => 'nullable|max:100', 
			'author' => 'nullable|max:100', 
			'tahun' => 'nullable|integer|min:1900|max:' . date('Y'), 
			'eksemplar' => 'nullable|integer', 
			'diterima' => 'nullable|integer',
		]);
	
		$data = [
			'is_approve' => $request->action === 'approve',
			'is_reject' => $request->action === 'reject',
			'diterima' => $request->action === 'approve' ? (int)$request->diterima : 0,
			'approved_by' => $request->action === 'approve' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'rejected_by' => $request->action === 'reject' ? (Auth::user() ? Auth::user()->id : 0) : null,
			'reason' => $request->action === 'reject' ? $request->reason : null,
		];

		if ($request->action === 'approve') {
			$data['approved_at'] = now();
			$data['harga'] = $request->harga; 
		} 
		elseif ($request->action === 'reject') {
			$data['rejected_at'] = now(); 
		}
		$idPengajuan = Hashids::decode($hashId)[0];
		$pengajuan = Pengajuan::find($idPengajuan);
		
		$pengajuan->update(array_merge($data, $request->only(['judul', 'isbn', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar'. 'diterima'])));

		$this->setLogActivity($request->action === 'approve' ? 'Menyetujui pengajuan' : 'Menolak pengajuan', $pengajuan);
		
		return response()->json(['message' => $request->action === 'approve' ? 'Pengajuan berhasil disetujui!' : 'Pengajuan berhasil ditolak!']);
	}

	private function redirectSuccess($route, string $messages = null, $id = null)
	{
		return redirect()
			->route($route,	$id)
			->with('success', $messages);
	}
}
