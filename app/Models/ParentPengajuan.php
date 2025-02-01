<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Vinkla\Hashids\Facades\Hashids;

class ParentPengajuan extends Model
{
    use HasFactory;
    protected $table = 'parent_pengajuan'; 
    protected $fillable = ['nama', 'prodi_id'];
    protected $appends = ['hashId'];


    public function pengajuans()
    {
        return $this->hasMany(Pengajuan::class, 'parent_pengajuan_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }
    public function parents()
    {
        return $this->belongsToMany(ParentPengajuan::class, 'approve_keuangan_parent_pengajuan', 'approve_keuangan_id', 'parent_pengajuan_id');
    }


    public function getHashIdAttribute()
    {
        return Hashids::encode($this->id);
    }
    
}
