<?php

namespace App\Exports;

use App\Models\Pengajuan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PengajuanExport implements FromCollection, WithHeadings, WithStyles, WithEvents, WithColumnFormatting
{
	public function collection()
	{
		$pengajuan = Pengajuan::all();
		return $pengajuan->map(function ($item, $key) {
			return [
				'No' => $key + 1,
				'Prodi' => $item->prodi,
				'ISBN' => $item->isbn,
				'Judul' => $item->judul,
				'Edisi' => $item->edisi,
				'Penerbit' => $item->penerbit,
				'Author' => $item->author,
				'Tahun' => $item->tahun,
				'Eksemplar' => $item->eksemplar,
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
			'Author',
			'Tahun',
			'Eksemplar',
		];
	}
	
	public function styles(Worksheet $sheet)
	{
		return [
			1 => ['font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']]],
			'A1:I1' => ['fill' => ['fillType' => 'solid', 'startColor' => ['argb' => '4CAF50']]],
		];
	}
	
	public function registerEvents(): array
	{
		return [
			\Maatwebsite\Excel\Events\AfterSheet::class => function (\Maatwebsite\Excel\Events\AfterSheet $event) {
				$event->sheet->getDelegate()->freezePane('A2');
				$event->sheet->getDelegate()->getStyle('A1:I1')->getFont()->setBold(true);
				
				// Auto-size all columns
				foreach (range('A', 'I') as $column) {
					$event->sheet->getDelegate()->getColumnDimension($column)->setAutoSize(true);
				}
			},
		];
	}
	
	public function columnFormats(): array
	{
		return [
			'H' => NumberFormat::FORMAT_NUMBER,
		];
	}
}