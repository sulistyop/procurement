<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Services\PengajuanService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	protected $pengajuanService;
    
    public function __construct(PengajuanService $pengajuanService)
	{
		$this->pengajuanService = $pengajuanService;
		$this->middleware('auth');
	}
	
    public function index()
    {
		$pengajuan = $this->pengajuanService->getPengajuan();
		$prodi = $this->pengajuanService->getProdi();
	    if(request()->has('export')) {
		    return $this->pengajuanService->exportPengajuan($pengajuan);
	    }
        return view('home', compact('pengajuan', 'prodi'));
    }
	
	// show
	public function show(Pengajuan $pengajuan)
	{
		if(Auth::user()->can('view pengajuan')) {
			// Menampilkan detail pengajuan tertentu
			return view('user.show', compact('pengajuan'));
		}else{
			return redirect()->route('pengajuan.index')->with('error', 'Anda tidak memiliki akses untuk melihat detail pengajuan.');
		}
	}
}
