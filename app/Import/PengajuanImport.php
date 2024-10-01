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
			$prodi = Prodi::create([
				'nama' => $row['prodi'],
				'deskripsi' => '',
			]);
		}
		return new Pengajuan([
			'prodi_id' => $prodi->id,
			'judul' => $row['judul'],
			'author' => $row['author'],
			'tahun' => now()->year,
			'eksemplar' => $row['eksemplar'],
			'diterima' => $row['diterima']  ?? NULL,
			'is_approve' => 0,
			'approved_at' => now(),
			'approved_by' => auth()->id(),
		]);
		dd($data);
	}
	
	public function rules(): array
	{
		return [
			'*.prodi' => 'required',
			'*.judul' => 'required',
			'*.author' => 'required',
			'*.eksemplar' => 'required',
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