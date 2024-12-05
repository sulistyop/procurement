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
        $years = Pengajuan::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->where('is_approve', 1)
            ->distinct()
            ->pluck('year')
            ->sort();
    
        $prodis = Prodi::all();
    
        $pengajuanQuery = Pengajuan::haveProdi()
            ->selectRaw('prodi_id, judul, isbn, penerbit, edisi, MAX(diterima) as diterima, MAX(created_at) as latest_created_at')
            ->where('is_approve', 1)
            ->groupBy('prodi_id','judul', 'isbn', 'penerbit', 'edisi');
    
        if ($request->filled('year')) {
            $pengajuanQuery->whereYear('created_at', $request->year);
        }
    
        $startDate = $request->start_date ?: Carbon::now()->startOfDay();
        $endDate = $request->end_date ?: Carbon::now()->endOfDay();
    
        $pengajuanQuery->whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate);
    
        if ($request->filled('prodi')) {
            $pengajuanQuery->where('prodi_id', $request->prodi);
        }
    
        $pengajuan = $pengajuanQuery->paginate(10)->appends($request->except('page'));
    
        $pengajuan->getCollection()->transform(function ($item) {
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
    
            $latestEntry->diterima = $summary;
    
            return $latestEntry;
        });
    
        if ($request->has('export')) {
            $excelReport = new PengajuanExport($pengajuan);
            $fileName = 'rekap_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download($excelReport, $fileName);
        }
    
        return view('admin.rekapPengajuan.index', compact('pengajuan', 'years', 'prodis'));
    }
    
    public function indexUser(Request $request)
    {
        $years = Pengajuan::selectRaw('EXTRACT(YEAR FROM created_at) as year')
            ->where('is_approve', 1)
            ->distinct()
            ->pluck('year')
            ->sort();
    
        $prodis = Prodi::all();
    
        $defaultStartDate = now()->startOfDay()->toDateString();
        $defaultEndDate = now()->endOfDay()->toDateString();
    
        $startDate = $request->get('start_date', $defaultStartDate);
        $endDate = $request->get('end_date', $defaultEndDate);
    
        $search = $request->get('search');
    
        $pengajuanQuery = Pengajuan::haveProdi()
            ->selectRaw('prodi_id, judul, isbn, penerbit, edisi, MAX(diterima) as diterima, MAX(created_at) as latest_created_at')
            ->where('is_approve', 1)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('prodi_id', 'judul', 'isbn', 'penerbit', 'edisi');
    
        if ($request->filled('year')) {
            $pengajuanQuery->whereYear('created_at', $request->year);
        }
    
        if ($request->filled('prodi')) {
            $pengajuanQuery->where('prodi_id', $request->prodi);
        }
    
        if ($search) {
            $pengajuanQuery->where(function($q) use ($search) {
                $q->where('judul', 'like', "%$search%")
                  ->orWhere('author', 'like', "%$search%")
                  ->orWhere('isbn', 'like', "%$search%");
            });
        }
    
        $pengajuan = $pengajuanQuery->get();
    
        if ($request->has('export')) {
            $excelReport = new PengajuanExport($pengajuan);
            $fileName = 'rekap_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';
            return Excel::download($excelReport, $fileName);
        }
    
        return view('user.rekap', compact('pengajuan', 'years', 'prodis', 'startDate', 'endDate', 'search'));
    }
}
