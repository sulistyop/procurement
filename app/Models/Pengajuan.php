<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    protected $table = 'pengajuan';
	
    protected $fillable = [
		'prodi_id',
	    'judul',
	    'edisi',
	    'penerbit',
	    'author',
	    'tahun',
	    'eksemplar',
	    'isbn',
	    'is_approve',
	    'approved_at',
	    'approved_by'
    ];
	
	public function prodi()
	{
		return $this->belongsTo(Prodi::class);
	}
}
