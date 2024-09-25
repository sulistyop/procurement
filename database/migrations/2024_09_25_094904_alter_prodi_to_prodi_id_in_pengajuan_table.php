<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
	public function up()
	{
		Schema::table('pengajuan', function (Blueprint $table) {
			$table->renameColumn('prodi', 'prodi_id');
		});
	}
	
	public function down()
	{
		Schema::table('pengajuan', function (Blueprint $table) {
			$table->renameColumn('prodi_id', 'prodi');
		});
	}
};
