<?php
	
namespace App\Import;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PengajuanImport implements ToModel, WithHeadingRow
{
	public function model(array $row)
	{
		return new Pengajuan([
			'prodi' => $row['prodi'],
			'judul' => $row['judul'],
			'edisi' => $row['edisi'],
			'penerbit' => $row['penerbit'],
			'author' => $row['author'],
			'tahun' => $row['tahun'],
			'eksemplar' => $row['eksemplar'],
			'isbn' => $row['isbn'],
			'is_approve' => $row['is_approve'],
			'approved_at' => $row['approved_at'],
			'approved_by' => $row['approved_by'],
		]);
	}
}