<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApproveKeuangan extends Model
{
    use HasFactory;
    protected $table = 'approve_keuangan'; 
    protected $fillable = [
        'nomorSurat',
        'surat',
        'nomorBukti',
        'buktiTransaksi',
        'user_id', // Jika Anda menyimpan ID pengguna yang mengajukan
    ];
}
