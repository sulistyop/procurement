<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
    protected $fillable = ['prodi', 'judul', 'edisi', 'penerbit', 'author', 'tahun', 'eksemplar', 'isbn', 'is_approve', 'approved_at', 'approved_by' ];
}
