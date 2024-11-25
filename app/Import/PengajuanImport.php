<?php
namespace App\Import;

use Exception;
use App\Models\User;
use App\Models\Prodi;
use App\Models\Pengajuan;
use App\Models\ParentPengajuan;
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

        // Mendapatkan ParentPengajuan yang terkait dengan user yang sedang login
        $parent = ParentPengajuan::where('user_id', $userId)->first();

        // Pastikan ada ParentPengajuan yang ditemukan
        if (!$parent) {
            throw new Exception('Parent Pengajuan tidak ditemukan.');
        }

        // Ambil Prodi dari ParentPengajuan
        $prodi = $parent->prodi;

        // Jika Prodi tidak ada, cari berdasarkan nama dari data yang diimpor
        if (!$prodi && isset($row['prodi'])) {
            $prodi = Prodi::where('nama', 'like', '%' . $row['prodi'] . '%')->first();
        }

        // Jika Prodi masih belum ditemukan, buat Prodi baru
        if (!$prodi && isset($row['prodi'])) {
            $prodi = Prodi::create([
                'nama' => $row['prodi'],
                'deskripsi' => '', // Deskripsi bisa dikosongkan atau diisi jika diperlukan
            ]);
        }

        // Jika Prodi tidak ada, beri exception agar tidak ada data yang hilang
        if (empty($row['prodi'])) {
            throw new Exception('Nama Prodi tidak ditemukan di baris impor.');
        }
        

        // Membuat record pengajuan
        return new Pengajuan([
            'prodi_id' => $prodi->id,
            'judul' => $row['judul'],
            'author' => $row['author'],
            'tahun' => $row['tahun'] ?? null,
            'eksemplar' => $row['eksemplar'],
            'diterima' => $row['diterima'] ?? null,
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
        // Menangani kegagalan impor dengan menyimpan kegagalan pada session
        session()->flash('import_errors', $failures);
        throw new Exception('Import failed');
    }
}

