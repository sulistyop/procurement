<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
	
	public static function haveProdi()
	{
		$user = Auth::user();
		return self::when($user->prodi, function ($query) use ($user) {
			$query->where('prodi_id', $user->prodi->id);
		});
	}
}
