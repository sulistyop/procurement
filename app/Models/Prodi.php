<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prodi extends Model
{
    protected $table = 'prodi';
	protected $fillable = ['id','nama', 'deskripsi'];
	
	public function pengajuan()
	{
		return $this->hasMany(Pengajuan::class);
	}
}
