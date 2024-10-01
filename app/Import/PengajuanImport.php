<?php
	
namespace App\Import;

use Exception;
use App\Models\Prodi;
use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PengajuanImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
	use SkipsFailures;
	
	public function model(array $row)
	{
		$prodi = Prodi::where('nama', 'like', '%'.$row['prodi'].'%')->first();
		if(!$prodi) {
			$prodi = Prodi::create(['nama' => $row['prodi']]);
		}
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
			'*.author' => 'required',
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