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

    protected $parentPengajuanId;

    public function __construct($parentPengajuanId)
    {
        $this->parentPengajuanId = $parentPengajuanId; // Menyimpan parent_pengajuan_id
    }

    public function model(array $row)
    {
        // Ambil pengguna saat ini
        $userId = auth()->id();

        // Ambil ParentPengajuan
        $parentPengajuan = ParentPengajuan::find($this->parentPengajuanId);

        // Tentukan Prodi berdasarkan ParentPengajuan atau nama pada file Excel
        $prodi = $parentPengajuan->prodi ?? Prodi::firstOrCreate(
            ['nama' => $row['prodi'] ?? 'Unknown'],
            ['deskripsi' => '']
        );

        // Membuat record Pengajuan
        return new Pengajuan([
            'parent_pengajuan_id' => $this->parentPengajuanId, // Set parent_pengajuan_id
            'prodi_id' => $prodi->id,                         // Prodi yang ditentukan
            'judul' => $row['judul'],                         // Judul dari file Excel
            'author' => $row['author'],                       // Penulis dari file Excel
            'tahun' => $row['tahun'] ?? null,                 // Tahun (opsional)
            'eksemplar' => $row['eksemplar'],                 // Eksemplar
            'diterima' => $row['diterima'] ?? null,           // Diterima (opsional)
            'is_approve' => 0,                                // Default tidak disetujui
            'approved_at' => now(),                           // Waktu persetujuan
            'approved_by' => $userId,                         // Disetujui oleh pengguna saat ini
        ]);
    }

    public function rules(): array
    {
        return [
            '*.judul' => 'required',
            '*.author' => 'required',
            '*.eksemplar' => 'required|numeric|min:1',
        ];
    }

    /**
     * Tangani kegagalan validasi.
     */
    public function onFailure(Failure ...$failures)
    {
        // Log kegagalan
        foreach ($failures as $failure) {
            \Log::error("Import failed", [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'errors' => $failure->errors(),
            ]);
        }
        session()->flash('import_errors', $failures);
    }
}
