<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApproveKeuanganParentPengajuan extends Model
{
    use HasFactory;

    protected $table = 'approve_keuangan_parent_pengajuan';

    protected $fillable = ['parent_pengajuan_id'];

    public function parentPengajuan()
    {
        return $this->belongsTo(ParentPengajuan::class, 'parent_pengajuan_id');
    }
}