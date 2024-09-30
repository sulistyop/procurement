<?php
	
namespace App\Import;

use App\Models\Pengajuan;
use App\Models\Prodi;
use Exception;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

class PengajuanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
	use SkipsFailures;
	
	public function model(array $row)
	{
		$prodi = Prodi::where('nama', 'like', '%'.$row['prodi'].'%')->first();
		return new Pengajuan([
			'prodi_id' => $prodi->id,
			'isbn' => $row['isbn'],
			'judul' => $row['judul'],
			'edisi' => $row['edisi'],
			'penerbit' => $row['penerbit'],
			'author' => $row['author'],
			'tahun' => $row['tahun'],
			'eksemplar' => $row['eksemplar'],
			'diterima' => $row['eksemplar'],
			'is_approve' => 1,
			'approved_at' => now(),
			'approved_by' => auth()->id(),
		]);
	}
	
	public function rules(): array
	{
		return [
			'*.prodi' => 'required',
			'*.judul' => 'required',
			'*.edisi' => 'nullable',
			'*.isbn' => 'required',
			'*.penerbit' => 'required',
			'*.author' => 'required',
			'*.tahun' => 'required|integer|min:1900|max:' . date('Y'),
			'*.eksemplar' => 'required|integer',
		];
	}
	
	/**
	 * @throws Exception
	 */
	public function onFailure(Failure ...$failures)
	{
		// Handle the failures how you'd like.
		session()->flash('import_errors', $failures);
		throw new Exception('Import failed');
	}
}