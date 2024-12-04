<?php
	
namespace App\Services;

use App\Models\Prodi;
use App\Models\Pengajuan;
use App\Exports\PengajuanExport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class PengajuanService
{
	public function getPengajuan()
	{
		// Menampilkan semua data pengajuan
		$pengajuan = Pengajuan::haveProdi()->get();
		
		return $pengajuan->map(function ($item) {
			// jika isbn pernah diajukan sebelum tahun sekarang berdasarkan created_at, jika diajukan lagi berikan mark bahwa buku tersebut pernah diajukan
			$item->is_diajukan = Pengajuan::where('isbn', $item->isbn)
					->where('isbn', '!=', null)
					->where('isbn', '!=', '-')
					->where('isbn', '!=', ' ')
					->where('prodi_id', $item->prodi_id)
					->count() > 1;
			if($item->is_diajukan){
				$item->date_pernah_diajukan = Pengajuan::where('isbn', $item->isbn)
					->orderBy('created_at','desc')
					->first()
					->created_at ?? null;
			}
			$item->nama_prodi = $item->prodi->nama;
			$item->prodi_id = $item->prodi->id;
			return $item;
		});
	}
	
	public function getProdi()
	{
		$user = Auth::user();
		return Prodi::when($user->prodi_id, function($query) use ($user){
			return $query->where('id', $user->prodi_id);
		})->get();
	}
	
	public function exportPengajuan($pengajuan)
	{
		$pengajuan = Pengajuan::all(); 

		$excelReport = new PengajuanExport($pengajuan);
	
		$fileName = 'daftar_pengajuan_' . date('Y-m-d_H-i-s') . '.xlsx';

		return Excel::download($excelReport, $fileName);
	}
}