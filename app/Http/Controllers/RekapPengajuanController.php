<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use Illuminate\Http\Request;

class RekapPengajuanController extends Controller
{
	public function index()
	{
		$pengajuan = Pengajuan::selectRaw('judul, isbn, penerbit, edisi, SUM(eksemplar) as total_eksemplar, MAX(created_at) as latest_created_at')
			->where('is_approve', 1) // Add condition to filter approved records
			->groupBy('judul', 'isbn', 'penerbit', 'edisi')
			->get();
		
		$pengajuan = $pengajuan->map(function ($item) {
			$latestEntry = Pengajuan::where('judul', $item->judul)
				->where('is_approve', 1)
				->where('isbn', $item->isbn)
				->where('penerbit', $item->penerbit)
				->where('edisi', $item->edisi)
				->orderBy('created_at', 'desc')
				->first();
			$summary = Pengajuan::where('judul', $item->judul)
				->where('is_approve', 1)
				->where('isbn', $item->isbn)
				->where('penerbit', $item->penerbit)
				->where('edisi', $item->edisi)
				->sum('eksemplar');
			
			$latestEntry->eksemplar = $summary;
			return $latestEntry;
		});
		
		return view('rekapPengajuan.index', compact('pengajuan'));
	}
}
