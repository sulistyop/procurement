<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder;

class ProdiSeeder extends Seeder
{
    public function run()
    {
        $prodis = [
            ['id' => 1, 'nama' => 'Perpustakaan'],
            ['id' => 2, 'nama' => 'Lembaga Pengembangan Studi Islam'],
            ['id' => 3, 'nama' => 'Kantor Universitas'],
            ['id' => 4, 'nama' => 'Kantor Kerjasama dan Urusan Internasional'],
            ['id' => 5, 'nama' => 'Biro Sumber Daya Manusia'],
            ['id' => 6, 'nama' => 'Badan Penjaminan Mutu'],
            ['id' => 7, 'nama' => 'Museum Muhammadiyah'],
            ['id' => 8, 'nama' => 'Badan Perencanaan dan Pengembangan Universitas'],
            ['id' => 9, 'nama' => 'Badan Penjaminan Mutu'],
            ['id' => 10, 'nama' => 'Kantor Urusan Bisnis dan Investasi'],
            ['id' => 11, 'nama' => 'Biro Akademik dan Admisi'],
            ['id' => 12, 'nama' => 'Lembaga Pengembangan Pendidikan'],
            ['id' => 13, 'nama' => 'Lembaga Penelitian dan Pengabdian kepada Masyarakat'],
            ['id' => 14, 'nama' => 'Biro Keuangan dan Anggaran'],
            ['id' => 15, 'nama' => 'Biro Sarana dan Prasarana'],
            ['id' => 16, 'nama' => 'Biro Sistem Informasi'],
            ['id' => 17, 'nama' => 'Biro Kemahasiswaan dan Alumni'],
            ['id' => 18, 'nama' => 'Lembaga Sertifikasi Profesi'],
            ['id' => 19, 'nama' => 'Center for Integrated Research and Innovation'],
            ['id' => 20, 'nama' => 'Pusat Studi Dinamika Sosial'],
            ['id' => 21, 'nama' => 'Pusat Studi Gender'],
            ['id' => 22, 'nama' => 'Pusat Studi Mitigasi dan Penanggulangan Bencana'],
            ['id' => 23, 'nama' => 'Pusat Studi Keselamatan dan Kesehatan Kerja'],
            ['id' => 24, 'nama' => 'Pusat Studi Astronomi'],
            ['id' => 25, 'nama' => 'Pusat Studi Analisis Kebijakan Nasional'],
            ['id' => 26, 'nama' => 'Pusat Studi Ekonomi Kreatif dan Pariwisata'],
            ['id' => 27, 'nama' => 'Pusat Studi Energi dan Lingkungan'],
            ['id' => 28, 'nama' => 'Ahmad Dahlan Drug Information and Crises Center'],
            ['id' => 29, 'nama' => 'Children and Family Education Center'],
            ['id' => 30, 'nama' => 'Ahmad Dahlan Halal Center'],
            ['id' => 31, 'nama' => 'Pusat Studi dan Layanan Disabilitas Ahmad Dahlan'],
            ['id' => 32, 'nama' => 'Andalucia Corner'],
            ['id' => 62201, 'nama' => 'Akuntansi S1'],
            ['id' => 79203, 'nama' => 'Bahasa dan Sastra Arab S1'],
            ['id' => 86201, 'nama' => 'Bimbingan dan Konseling S1'],
            ['id' => 46201, 'nama' => 'Biologi S1'],
            ['id' => 93312, 'nama' => 'Bisnis Jasa Makanan D4'],
            ['id' => 60201, 'nama' => 'Ekonomi Pembangunan S1'],
            ['id' => 48201, 'nama' => 'Farmasi S1'],
            ['id' => 45201, 'nama' => 'Fisika S1'],
            ['id' => 13211, 'nama' => 'Gizi S1'],
            ['id' => 74201, 'nama' => 'Hukum S1'],
            ['id' => 76231, 'nama' => 'Ilmu Hadist S1'],
            ['id' => 70201, 'nama' => 'Ilmu Komunikasi S1'],
            ['id' => 55201, 'nama' => 'Informatika S1'],
            ['id' => 11201, 'nama' => 'Kedokteran S1'],
            ['id' => 13201, 'nama' => 'Kesehatan Masyarakat S1'],
            ['id' => 61201, 'nama' => 'Manajemen S1'],
            ['id' => 44201, 'nama' => 'Matematika S1'],
            ['id' => 70234, 'nama' => 'Pendidikan Agama Islam S1'],
            ['id' => 88201, 'nama' => 'Pendidikan Bahasa dan Sastra Indonesia S1'],
            ['id' => 88203, 'nama' => 'Pendidikan Bahasa Inggris S1'],
            ['id' => 84205, 'nama' => 'Pendidikan Biologi S1'],
            ['id' => 84203, 'nama' => 'Pendidikan Fisika S1'],
            ['id' => 86207, 'nama' => 'Pendidikan Guru PAUD S1'],
            ['id' => 86206, 'nama' => 'Pendidikan Guru Sekolah Dasar S1'],
            ['id' => 84202, 'nama' => 'Pendidikan Matematika S1'],
            ['id' => 87205, 'nama' => 'Pendidikan Pancasila dan Kewarganegaraan S1'],
            ['id' => 86218, 'nama' => 'Pendidikan Vokasional Teknologi Otomotif S1'],
            ['id' => 83202, 'nama' => 'Pendidikan Vokasional Teknik Elektro S1'],
            ['id' => 61206, 'nama' => 'Perbankan Syariah S1'],
            ['id' => 73201, 'nama' => 'Psikologi S1'],
            ['id' => 79201, 'nama' => 'Sastra Indonesia S1'],
            ['id' => 79202, 'nama' => 'Sastra Inggris S1'],
            ['id' => 57201, 'nama' => 'Sistem Informasi S1'],
            ['id' => 20201, 'nama' => 'Teknik Elektro S1'],
            ['id' => 26201, 'nama' => 'Teknik Industri S1'],
            ['id' => 24201, 'nama' => 'Teknik Kimia S1'],
            ['id' => 41221, 'nama' => 'Teknologi Pangan S1'],
            ['id' => 48901, 'nama' => 'Pendidikan Profesi Apoteker'],
            ['id' => 11901, 'nama' => 'Pendidikan Profesi Dokter'],
            ['id' => 86904, 'nama' => 'Pendidikan Profesi Guru'],
            ['id' => 73103, 'nama' => 'Pendidikan Profesi Psikologi'],
            ['id' => 86101, 'nama' => 'Bimbingan dan Konseling S2'],
            ['id' => 48101, 'nama' => 'Farmasi S2'],
            ['id' => 74101, 'nama' => 'Hukum S2'],
            ['id' => 55102, 'nama' => 'Informatika S2'],
            ['id' => 13101, 'nama' => 'Kesehatan Masyarakat S2'],
            ['id' => 61101, 'nama' => 'Manajemen Pendidikan S2'],
            ['id' => 70134, 'nama' => 'Pendidikan Agama Islam S2'],
            ['id' => 88103, 'nama' => 'Pendidikan Bahasa Inggris S2'],
            ['id' => 84103, 'nama' => 'Pendidikan Fisika S2'],
            ['id' => 83101, 'nama' => 'Pendidikan Guru Vokasi S2'],
            ['id' => 84102, 'nama' => 'Pendidikan Matematika S2'],
            ['id' => 73101, 'nama' => 'Psikologi S2'],
            ['id' => 20101, 'nama' => 'Teknik Elektro S2'],
            ['id' => 86009, 'nama' => 'Teknik Kimia S2'],
            ['id' => 48001, 'nama' => 'Ilmu Farmasi S3'],
            ['id' => 86009, 'nama' => 'Informatika S3'],
            ['id' => 86009, 'nama' => 'Pendidikan S3'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::updateOrCreate(
                ['id' => $prodi['id']], // Kriteria pencarian berdasarkan ID
                ['nama' => $prodi['nama'], 'deskripsi' => ''] // Data yang akan diupdate atau dibuat
            );
        }
    }
}
