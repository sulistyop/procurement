<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $years = Pengajuan::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->where('is_approve', 1)
            ->distinct()
            ->pluck('year')
            ->sort();
    
        // Ambil semua prodi untuk filter
        $prodis = Prodi::all();
    
        // Ambil semua pengajuan yang sudah disetujui
        $pengajuanQuery = Pengajuan::haveProdi()
            ->selectRaw('judul, isbn, penerbit, edisi, MAX(diterima) as diterima, MAX(created_at) as latest_created_at')
            ->where('is_approve', 1)
            ->groupBy('judul', 'isbn', 'penerbit', 'edisi');
    
        // Filter berdasarkan tahun jika ada
        if ($request->filled('year')) {
            $pengajuanQuery->whereYear('created_at', $request->year);
        }
    
        // Filter berdasarkan tanggal jika ada
        $startDate = $request->start_date ?: Carbon::now()->startOfDay(); // Default tanggal mulai
        $endDate = $request->end_date ?: Carbon::now()->endOfDay(); // Default tanggal akhir
    
        $pengajuanQuery->whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate);
    
        // Jika ada filter prodi, tambahkan kondisi
        if ($request->filled('prodi')) {
            $pengajuanQuery->where('prodi_id', $request->prodi);
        }
    
        // Ambil data pengajuan dengan pagination
        $pengajuan = $pengajuanQuery->paginate(10)->appends($request->except('page'));
    
        // Mapping untuk menambahkan total eksemplar setelah pagination
        $pengajuan->getCollection()->transform(function ($item) {
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
            $latestEntry->diterima = $summary;
    
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
    
    public function indexUser(Request $request)
    {
        // Ambil tahun yang tersedia untuk filter
        $years = Pengajuan::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->where('is_approve', 1) // Filter approved records
            ->distinct()
            ->pluck('year')
            ->sort();
    
        // Ambil semua prodi untuk filter
        $prodis = Prodi::all();
    
        // Default tanggal awal dan akhir adalah hari ini
        $defaultStartDate = now()->startOfDay()->toDateString();
        $defaultEndDate = now()->endOfDay()->toDateString();
    
        // Ambil rentang tanggal dari request, atau gunakan default
        $startDate = $request->get('start_date', $defaultStartDate);
        $endDate = $request->get('end_date', $defaultEndDate);
    
        // Ambil kata kunci pencarian dari request
        $search = $request->get('search');
    
        // Ambil semua pengajuan yang sudah disetujui
        $pengajuanQuery = Pengajuan::haveProdi()
            ->selectRaw('judul, isbn, penerbit, edisi, MAX(diterima) as diterima, MAX(created_at) as latest_created_at')
            ->where('is_approve', 1) // Filter approved records
            ->whereBetween('created_at', [$startDate, $endDate]) // Filter rentang tanggal
            ->groupBy('judul', 'isbn', 'penerbit', 'edisi');
    
        // Jika ada filter tahun, tambahkan kondisi
        if ($request->filled('year')) {
            $pengajuanQuery->whereYear('created_at', $request->year);
        }
    
        // Jika ada filter prodi, tambahkan kondisi
        if ($request->filled('prodi')) {
            $pengajuanQuery->where('prodi_id', $request->prodi);
        }
    
        // Jika ada pencarian berdasarkan judul, pengarang, atau ISBN, tambahkan kondisi
        if ($search) {
            $pengajuanQuery->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }
    
        // Ambil hasil pengajuan yang sudah difilter dengan pagination
        $pengajuan = $pengajuanQuery->paginate(10); // Menampilkan 10 pengajuan per halaman
    
        // Export ke Excel jika diminta
        if ($request->has('export')) {
            $excelReport = new PengajuanExport($pengajuan);
            $fileName = 'rekap_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download($excelReport, $fileName);
        }
    
        return view('user.rekap', compact('pengajuan', 'years', 'prodis', 'startDate', 'endDate', 'search'));
    }
    
}
