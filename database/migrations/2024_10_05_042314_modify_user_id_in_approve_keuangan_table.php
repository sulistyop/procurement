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
            // Tambahkan kolom baru user_id_temp tanpa identity
            $table->unsignedBigInteger('user_id_temp')->nullable()->default(1);
        });

        // Salin data dari user_id lama ke user_id_temp
        DB::table('approve_keuangan')->update(['user_id_temp' => DB::raw('user_id')]);

        Schema::table('approve_keuangan', function (Blueprint $table) {
            // Hapus kolom user_id yang lama
            $table->dropColumn('user_id');
            // Ganti nama user_id_temp menjadi user_id
            $table->renameColumn('user_id_temp', 'user_id');
        });
    }

    public function down()
    {
        Schema::table('approve_keuangan', function (Blueprint $table) {
            // Hapus kolom user_id
            $table->dropColumn('user_id');
            // Tambahkan kolom user_id yang lama
            $table->unsignedBigInteger('user_id')->nullable(false)->default(null);
        });
    }
};
