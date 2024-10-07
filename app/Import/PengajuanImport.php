<?php
namespace App\Import;

use Exception;
use App\Models\User;
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
        // Ambil pengguna yang mengupload
        $userId = auth()->id();
        $user = User::find($userId);

        // Misalnya User punya relasi ke Prodi (User -> belongsTo -> Prodi)
        $prodi = $user->prodi; // Ambil prodi dari user yang sedang login

        // Jika tidak ada prodi, coba cari berdasarkan nama yang diimpor
        if (!$prodi) {
            $prodi = Prodi::where('nama', 'like', '%' . $row['prodi'] . '%')->first();
        }

        // Jika masih tidak ditemukan, buat Prodi baru
        if (!$prodi) {
            $prodi = Prodi::create([
                'nama' => $row['prodi'],
                'deskripsi' => '',
            ]);
        }

        // Membuat record pengajuan
        return new Pengajuan([
            'prodi_id' => $prodi->id,
            'judul' => $row['judul'],
            'author' => $row['author'],
            'tahun' => now()->year,
            'eksemplar' => $row['eksemplar'],
            'diterima' => $row['diterima'] ?? NULL,
            'is_approve' => 0,
            'approved_at' => now(),
            'approved_by' => $userId,
        ]);
    }

    public function rules(): array
    {
        return [
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
