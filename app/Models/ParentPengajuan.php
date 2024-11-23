<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentPengajuan extends Model
{
    use HasFactory;
    protected $table = 'parent_pengajuan'; 
    protected $fillable = ['nama', 'prodi_id'];

    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'parent_pengajuan_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }


}
