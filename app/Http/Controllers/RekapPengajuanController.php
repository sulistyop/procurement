<?php

namespace App\Http\Controllers;

use App\Exports\PengajuanExport;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class RekapPengajuanController extends Controller
{
	public function index()
	{
		$pengajuan = Pengajuan::haveProdi()
			->selectRaw('judul, isbn, penerbit, edisi, SUM(eksemplar) as total_eksemplar, MAX(created_at) as latest_created_at')
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
				->sum('diterima');
			
			$latestEntry->eksemplar = $summary;
			return $latestEntry;
		});
		
		if(request()->has('export')) {
			$excelReport = new PengajuanExport($pengajuan);
			$fileName = 'rekap_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
			return Excel::download($excelReport, $fileName);
		}
		
		return view('rekapPengajuan.index', compact('pengajuan'));
	}
}
