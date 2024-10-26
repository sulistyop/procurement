<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use App\Exports\PengajuanExport;
use Maatwebsite\Excel\Facades\Excel;

class RekapPengajuanController extends Controller
{
	public function index(Request $request)
	{
		// Ambil tahun yang tersedia untuk filter
		$years = Pengajuan::selectRaw('YEAR(created_at) as year')
			->where('is_approve', 1) // Filter approved records
			->distinct()
			->pluck('year')
			->sort();
	
		// Ambil semua prodi untuk filter
		$prodis = Prodi::all();
	
		// Ambil semua pengajuan yang sudah disetujui
		$pengajuanQuery = Pengajuan::haveProdi()
			->selectRaw('judul, isbn, penerbit, edisi, SUM(diterima) as total_eksemplar, MAX(created_at) as latest_created_at')
			->where('is_approve', 1) // Filter approved records
			->groupBy('judul', 'isbn', 'penerbit', 'edisi'); // Pastikan semua kolom non-agregat ada di sini
	
		// Jika ada filter tahun, tambahkan kondisi
		if ($request->filled('year')) {
			$pengajuanQuery->whereYear('created_at', $request->year);
		}
	
		// Jika ada filter prodi, tambahkan kondisi
		if ($request->filled('prodi')) {
			$pengajuanQuery->where('prodi_id', $request->prodi);
		}
	
		$pengajuan = $pengajuanQuery->get();
	
		// Mapping untuk menambahkan total eksemplar
		$pengajuan = $pengajuan->map(function ($item) {
			// Mendapatkan entry terbaru berdasarkan judul, isbn, penerbit, dan edisi
			$latestEntry = Pengajuan::where('judul', $item->judul)
				->where('is_approve', 1)
				->where('isbn', $item->isbn)
				->where('penerbit', $item->penerbit)
				->where('edisi', $item->edisi)
				->orderBy('created_at', 'desc')
				->first();
	
			// Menjumlahkan eksemplar untuk entry terbaru
			$summary = Pengajuan::where('judul', $item->judul)
				->where('is_approve', 1)
				->where('isbn', $item->isbn)
				->where('penerbit', $item->penerbit)
				->where('edisi', $item->edisi)
				->sum('diterima'); // Menghitung jumlah eksemplar
	
			// Menambahkan total eksemplar ke entry terbaru
			$latestEntry->eksemplar = $summary;
	
			return $latestEntry;
		});
	
		// Export ke Excel jika diminta
		if ($request->has('export')) {
			$excelReport = new PengajuanExport($pengajuan);
			$fileName = 'rekap_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
			return Excel::download($excelReport, $fileName);
		}
	
		return view('admin.rekapPengajuan.index', compact('pengajuan', 'years', 'prodis'));
	}
	

}
