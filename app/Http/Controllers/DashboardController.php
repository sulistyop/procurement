<?php

namespace App\Http\Controllers;

use App\Models\Prodi;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
	public function index(Request $request)
	{
		$userProdiId = Auth::user()->prodi_id;
		
		// Filter parameters
		$filterYear = $request->input('year');
		$filterProdi = $request->input('prodi');
		
		// Monthly statistics for books from January to December (1 Year)
		$monthlyBooks = Pengajuan::selectRaw('MONTH(created_at) as month, sum(diterima) as total')
			->when($userProdiId, function ($query) use ($userProdiId) {
				return $query->where('prodi_id', $userProdiId);
			})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->groupBy('month')
			->get()
			->keyBy('month'); // To index by month
		
		// Initialize array for all 12 months (January to December)
		$monthlyData = [];
		for ($month = 1; $month <= 12; $month++) {
			$monthlyData[$month] = $monthlyBooks->get($month)->total ?? 0; // Default to 0 if no data for that month
		}
		
		// Statistics per Prodi
		$booksPerProdi = Pengajuan::selectRaw('prodi_id, sum(diterima) as total')
			->when($userProdiId, function ($query) use ($userProdiId) {
				return $query->where('prodi_id', $userProdiId);
			})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->groupBy('prodi_id')
			->with('prodi')
			->get()
			->map(function ($item) {
				return [
					'nama' => $item->prodi->nama,
					'total' => $item->total
				];
			});
		
		// Other existing statistics
		$totalBooks = Pengajuan::when($userProdiId, function ($query) use ($userProdiId) {
			return $query->where('prodi_id', $userProdiId);
		})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->sum('judul');
		
		$acceptedBooks = Pengajuan::where('is_approve', 1)
			->when($userProdiId, function ($query) use ($userProdiId) {
				return $query->where('prodi_id', $userProdiId);
			})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->sum('judul');
		
		$pendingBooks = Pengajuan::where('is_approve', 0)
			->where('is_reject', 0)
			->when($userProdiId, function ($query) use ($userProdiId) {
				return $query->where('prodi_id', $userProdiId);
			})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->sum('judul');
		
		$rejectBooks = Pengajuan::where('is_reject', 1)
			->when($userProdiId, function ($query) use ($userProdiId) {
				return $query->where('prodi_id', $userProdiId);
			})
			->when($filterYear, function ($query) use ($filterYear) {
				return $query->whereYear('created_at', $filterYear);
			})
			->when($filterProdi, function ($query) use ($filterProdi) {
				return $query->where('prodi_id', $filterProdi);
			})
			->sum('judul');
		
		$years = Pengajuan::selectRaw('YEAR(created_at) as year')
			->where('is_approve', 1) // Filter approved records
			->distinct()
			->pluck('year')
			->sort();
		
		// Ambil semua prodi untuk filter
		$prodis = Prodi::all();
		
		return view('dashboard.index', compact('totalBooks', 'acceptedBooks', 'pendingBooks', 'rejectBooks', 'monthlyData', 'booksPerProdi', 'years', 'prodis'));
	}
}