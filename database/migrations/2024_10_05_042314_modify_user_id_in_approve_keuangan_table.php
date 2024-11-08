<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up()
	{
		Schema::table('approve_keuangan', function (Blueprint $table) {
			// Drop the foreign key constraint
			$table->dropForeign(['user_id']);
			// Add a new temporary column
			$table->unsignedBigInteger('user_id_temp')->nullable()->default(1);
		});
		
		// Copy data from old user_id to user_id_temp
		DB::table('approve_keuangan')->update(['user_id_temp' => DB::raw('user_id')]);
		
		Schema::table('approve_keuangan', function (Blueprint $table) {
			// Drop the old user_id column
			$table->dropColumn('user_id');
			// Rename user_id_temp to user_id
			$table->renameColumn('user_id_temp', 'user_id');
		});
	}
	
	public function down()
	{
		Schema::table('approve_keuangan', function (Blueprint $table) {
			// Add the old user_id column back
			$table->unsignedBigInteger('user_id')->nullable(false)->default(null);
		});
		
		// Copy data back from user_id to user_id_temp
		DB::table('approve_keuangan')->update(['user_id' => DB::raw('user_id_temp')]);
		
		Schema::table('approve_keuangan', function (Blueprint $table) {
			// Rename user_id back to user_id_temp
			$table->renameColumn('user_id', 'user_id_temp');
			// Drop the user_id_temp column
			$table->dropColumn('user_id_temp');
		});
	}
};
