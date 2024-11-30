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
        'user_id',
    ];

    public function parents()
    {
        return $this->belongsToMany(ParentPengajuan::class, 'approve_keuangan_parent_pengajuan', 'approve_keuangan_id', 'parent_pengajuan_id');
    }
}
