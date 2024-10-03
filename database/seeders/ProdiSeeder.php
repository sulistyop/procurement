<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProdiSeeder extends Seeder
{
	public function run()
	{
		$prodis = [
			'Perpustakaan',
			'Lembaga Pengembangan Studi Islam',
			'Bahasa dan Sastra Arab S1',
			'Ilmu Hadist S1',
			'Pendidikan Agama Islam S1',
			'Perbankan Syariah S1',
			'Psikologi S1',
			'Ekonomi Pembangunan S1',
			'Manajemen S1',
			'Akuntansi S1',
			'Bisnis Jasa Makanan D4',
			'Sastra Inggris S1',
			'Sastra Indonesia S1',
			'Ilmu Komunikasi S1',
			'Hukum S1',
			'Bimbingan dan Konseling S1',
			'Pendidikan Bahasa dan Sastra Indonesia S1',
			'Pendidikan Bahasa Inggris S1',
			'Pendidikan Pancasila dan Kewarganegaraan S1',
			'Pendidikan Matematika S1',
			'Pendidikan Biologi S1',
			'Pendidikan Fisika S1',
			'Pendidikan Guru Sekolah Dasar S1',
			'Pendidikan Guru PAUD S1',
			'Pendidikan Vokasional Teknologi Otomotif S1',
			'Pendidkan Vokasional Teknik Elektro S1',
			'Matematika S1',
			'Sistem Informasi S1',
			'Fisika S1',
			'Biologi S1',
			'Teknik Industri S1',
			'Informatika S1',
			'Teknik Elektro S1',
			'Teknik Kimia S1',
			'Teknologi Pangan S1',
			'Farmasi S1',
			'Kesehatan Masyarakat S1',
			'Gizi S1',
			'Kedokteran S1',
			'Pendidikan Profesi Guru',
			'Pendidikan Profesi Apoteker',
			'Pendidikan Profesi Dokter',
			'Pendidikan Profesi Psikologi',
			'Pendidikan Agama Islam S2',
			'Pendidikan Fisika S2',
			'Pendidikan Guru Vokasi S2',
			'Pendidikan Bahasa Inggris S2',
			'Pendidikan Matematika S2',
			'Bimbingan dan Konseling S2',
			'Manajemen Pendidikan S2',
			'Psikologi S2',
			'Farmasi S2',
			'Kesehatan Masyarakat S2',
			'Informatika S2',
			'Teknik Kimia S2',
			'Teknik Elektro S2',
			'Hukum S2',
			'Ilmu Farmasi S3',
			'Pendidikan S3',
			'Informatika S3'
		];
		
		foreach ($prodis as $prodi) {
			Prodi::updateOrCreate(['nama' => $prodi], ['deskripsi' => '']);
		}
	}
}
