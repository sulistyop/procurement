<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function index()
	{
		$totalBooks = Pengajuan::where('is_approve', 0)
			->when(Auth::user()->prodi_id, function ($query, $prodiId) {
				return $query->where('prodi_id', $prodiId);
			})
			->distinct('isbn')
			->distinct('judul')
			->distinct('prodi_id')
			->count();
		$acceptedBooks = Pengajuan::where('is_approve', 1)
			->when(Auth::user()->prodi_id, function ($query, $prodiId) {
				return $query->where('prodi_id', $prodiId);
			})
			->count();
		$pendingBooks = Pengajuan::where('is_approve', 0)
			->where('is_reject', 0)
			->when(Auth::user()->prodi_id, function ($query, $prodiId) {
				return $query->where('prodi_id', $prodiId);
			})
			->count();
		$rejectBooks = Pengajuan::where('is_reject', 1)
		->when(Auth::user()->prodi_id, function ($query, $prodiId) {
			return $query->where('prodi_id', $prodiId);
		})
		->count();
		
		return view('dashboard.index', compact('totalBooks', 'acceptedBooks', 'pendingBooks', 'rejectBooks'));
	}
}
