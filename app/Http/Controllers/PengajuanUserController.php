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
        $idParent = $request->query('parent_pengajuan_id');
        $selectedParent = ParentPengajuan::find($idParent);

        if ($idParent && !$selectedParent) {
            return redirect()->route('pengajuan.index')->with('error', 'Parent tidak ditemukan.');
        }

        $prodi = Prodi::when($selectedParent, function($query) use ($selectedParent) {
            return $query->where('id', $selectedParent->prodi_id);
        })->get();

        $pengajuanQuery = Pengajuan::with('prodi');

        if ($idParent) {
            $pengajuanQuery->where('parent_pengajuan_id', $idParent);
        }

        $pengajuan = $pengajuanQuery->paginate(10);

        return view('user.pengajuan.index', [
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

        return view('user.pengajuan.create', [
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
            'parent_pengajuan_id' => 'nullable|exists:parent_pengajuan,id',
        ]);

        $data = $request->all();
        $pengajuan = Pengajuan::create($data);

        $this->setLogActivity('Membuat pengajuan', $pengajuan);

        return redirect()->route('home-index', ['parent_pengajuan_id' => $request->parent_pengajuan_id])
                         ->with('success', 'Pengajuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pengajuan = Pengajuan::findOrFail($id);
        $prodi = Prodi::all();
        return view('user.pengajuan.edit', compact('pengajuan', 'prodi'));
    }

    public function update(Request $request, Pengajuan $pengajuan)
    {
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

        $pengajuan->update($request->only(['prodi_id', 'judul', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar', 'diterima', 'harga']));

        $this->setLogActivity('Mengubah pengajuan', $pengajuan);

        $parentPengajuanId = $request->parent_pengajuan_id ?? 1;

        return redirect()->route('home-index', ['parent_pengajuan_id' => $parentPengajuanId])
                         ->with('success', 'Pengajuan berhasil diupdate.');
    }

    public function destroy(Request $request, Pengajuan $pengajuan)
    {
        $parentPengajuanId = $request->query('parent_pengajuan_id') ?? $pengajuan->parent_pengajuan_id ?? 1;

        $isParentExists = DB::table('parent_pengajuan')->where('id', $parentPengajuanId)->exists();

        if (!$isParentExists) {
            return redirect()->route('pengajuan.index')
                             ->with('error', 'Parent pengajuan tidak ditemukan.');
        }

        $dump = $pengajuan;
        $pengajuan->delete();

        $this->setLogActivity('Menghapus pengajuan', $dump);

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
