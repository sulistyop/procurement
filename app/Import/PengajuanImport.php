<?php
	
namespace App\Import;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PengajuanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
	use SkipsFailures;
	
	public function model(array $row)
	{
		return new Pengajuan([
			'prodi' => $row['prodi'],
			'isbn' => $row['isbn'],
			'judul' => $row['judul'],
			'edisi' => $row['edisi'],
			'penerbit' => $row['penerbit'],
			'author' => $row['author'],
			'tahun' => $row['tahun'],
			'eksemplar' => $row['eksemplar'],
			'is_approve' => 1,
			'approved_at' => now(),
			'approved_by' => auth()->id(),
		]);
	}
	
	public function rules(): array
	{
		return [
			'*.prodi' => 'required|max:100',
			'*.judul' => 'required|max:255',
			'*.edisi' => 'nullable|max:50',
			'*.isbn' => 'required|max:20',
			'*.penerbit' => 'required|max:100',
			'*.author' => 'required|max:100',
			'*.tahun' => 'required|integer|min:1900|max:' . date('Y'),
			'*.eksemplar' => 'required|integer',
		];
	}
}