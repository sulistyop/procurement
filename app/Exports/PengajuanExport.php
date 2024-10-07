<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class PengajuanExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
	protected $pengajuan;
	public function __construct(Collection $pengajuan)
	{
		$this->pengajuan = $pengajuan;
	}
	
	public function collection()
	{
		// dd($this->pengajuan);
		return $this->pengajuan->map(function ($item, $key) {
			$totalHarga = $item->harga * ($item->diterima ?? 0);
			return [
				'No' => $key + 1,
				'Prodi' => $item->prodi->nama,
				'ISBN' => $item->isbn,
				'Judul' => $item->judul,
				'Edisi' => $item->edisi,
				'Penerbit' => $item->penerbit,
				'Author' => $item->author,
				'Tahun' => $item->tahun,
				'Usulan' => $item->eksemplar,
				'Diterima' => (string)($item->diterima ?? "-"),
				'Harga' => $item->harga ?? "-",
				'Total Harga' => $totalHarga ?? "-" ,
				'alasan' => $item->reason ?? "-",
			];
		});
	}
	
	public function headings(): array
	{
		return [
			'No',
			'Prodi',
			'ISBN',
			'Judul',
			'Edisi',
			'Penerbit',
			'Pengarang',
			'Tahun',
			'Usulan',
			'Diterima',
			'Harga',
			'Total Harga',
			'Alasan Ditolak',
		];
	}
	
	public function styles(Worksheet $sheet)
	{
		return [
			1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]],
			'A1:M1' => ['fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '4CAF50']]],
		];
	}
	
	public function registerEvents(): array
	{
		return [
			\Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
				$event->sheet->getDelegate()->freezePane('A2');
				$event->sheet->getDelegate()->getStyle('A1:M1')->getFont()->setBold(true);
				
				// Auto-size all columns
				foreach (range('A', 'M') as $column) {
					$event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
				}
			},
		];
	}
	
	public function columnFormats(): array
	{
		return [
			'H' => NumberFormat::FORMAT_NUMBER,
			// 'I' => NumberFormat::FORMAT_NUMBER,
			// 'J' => NumberFormat::FORMAT_NUMBER,
			// 'K' => NumberFormat::FORMAT_NUMBER,
		];
	}
}