<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
	public function index()
	{
		$totalBooks = Pengajuan::where('is_approve', 1)
			->distinct('isbn')
			->distinct('judul')
			->distinct('prodi_id')
			->count();
		$pendingBooks = Pengajuan::where('is_approve', 0)->count();
		
		return view('dashboard.index', compact('totalBooks', 'pendingBooks'));
	}
}
