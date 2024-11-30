<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Models\ParentPengajuan;
use App\Services\PengajuanService;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $pengajuanService;

    public function __construct(PengajuanService $pengajuanService)
    {
        $this->pengajuanService = $pengajuanService;
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        // Ambil nilai parent_pengajuan_id dan prodi_id dari request
        $parentPengajuanId = $request->input('parent_pengajuan_id');
        $prodiId = $request->input('prodi_id');
        $search = $request->input('search'); // Ambil nilai pencarian
    
        // Ambil data berdasarkan parent_pengajuan_id dan prodi_id
        $pengajuan = Pengajuan::where('parent_pengajuan_id', $parentPengajuanId)
                              ->where('prodi_id', $prodiId);
    
        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $pengajuan = $pengajuan->where(function($query) use ($search) {
                $query->where('isbn', 'like', '%' . $search . '%')
                      ->orWhere('judul', 'like', '%' . $search . '%')
                      ->orWhere('author', 'like', '%' . $search . '%')
                      // Gunakan is_approve atau is_reject jika Anda ingin mencocokkan status
                      ->orWhere('is_approve', 'like', '%' . $search . '%')   // Misalnya pencarian untuk status disetujui
                      ->orWhere('is_reject', 'like', '%' . $search . '%');    // Misalnya pencarian untuk status ditolak
            });
        }
    
        // Ambil data prodi (misalnya, untuk ditampilkan di dropdown atau lainnya)
        $prodi = Prodi::all();
    
        // Cek apakah ingin melakukan ekspor
        if ($request->has('export')) {
            return $this->pengajuanService->exportPengajuan($pengajuan);
        }
    
        // Kirim data ke view
        return view('home', compact('pengajuan', 'prodi'));
    }
    
    public function create()
    {
        // Menampilkan form untuk menambah pengajuan baru
		// $user = Auth::user();
		// $prodi = Prodi::when($user->prodi_id, function($query) use ($user){
		// 	return $query->where('id', $user->prodi_id);
 		//  });
		// return view('pengajuan.create', compact('prodi'));
        return view('home');
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
	    $pengajuan = Pengajuan::create($request->all());
	    
	    $this->setLogActivity('Membuat pengajuan', $pengajuan);
		
        return redirect()->route('home')->with('success', 'Pengajuan berhasil ditambahkan.');
    }


    public function edit(Pengajuan $pengajuan)
    {
        // Menampilkan form untuk mengedit pengajuan
        return view('home', compact('pengajuan'));
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

        return redirect()->route('home')->with('success', 'Pengajuan berhasil diupdate.');
    }

    public function destroy(Pengajuan $pengajuan)
    {
        // Hapus data pengajuan
	    $dump = $pengajuan;
        $pengajuan->delete();
		$this->setLogActivity('Menghapus pengajuan', $dump);
        return redirect()->route('home')->with('success', 'Pengajuan berhasil dihapus.');
    }

    public function show(Pengajuan $pengajuan)
    {
        $user = auth()->user();
        
        // Ambil ParentPengajuan yang sesuai dengan prodi yang dimiliki oleh user
        $parentPengajuans = ParentPengajuan::where('prodi_id', $user->prodi_id)->get(); 

		return view('user.show', compact('pengajuan', 'parentPengajuans'));
    }
    
    
}
