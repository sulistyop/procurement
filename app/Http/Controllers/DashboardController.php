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
        
        $filterYear = $request->input('year');
        $filterProdi = $request->input('prodi');
        
        $monthlyBooks = Pengajuan::selectRaw('EXTRACT(MONTH FROM created_at) as month, SUM(diterima) as total')
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
            ->keyBy('month'); 
        $monthlyData = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyData[$month] = $monthlyBooks->get($month)->total ?? 0; 
        }

        $booksPerProdi = Pengajuan::selectRaw('prodi_id, SUM(diterima) as total')
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
        
        $totalBooks = Pengajuan::when($userProdiId, function ($query) use ($userProdiId) {
            return $query->where('prodi_id', $userProdiId);
        })
            ->when($filterYear, function ($query) use ($filterYear) {
                return $query->whereYear('created_at', $filterYear);
            })
            ->when($filterProdi, function ($query) use ($filterProdi) {
                return $query->where('prodi_id', $filterProdi);
            })
            ->count('judul');
        
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
            ->count('judul');
        
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
            ->count('judul');
        
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
            ->count('judul');
        
        $years = Pengajuan::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->where('is_approve', 1) // Filter approved records
            ->distinct()
            ->pluck('year')
            ->sort();
        
        $prodis = Prodi::all();
        
        return view('admin.dashboard.index', compact('totalBooks', 'acceptedBooks', 'pendingBooks', 'rejectBooks', 'monthlyData', 'booksPerProdi', 'years', 'prodis'));
    }
}
