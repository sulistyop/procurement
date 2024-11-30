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
            $table->unsignedBigInteger('parent_pengajuan_id')->nullable(); // Menambahkan kolom parent_pengajuan_id
            $table->foreign('parent_pengajuan_id')->references('id')->on('pengajuan')->onDelete('set null');
        });
    }
    
    public function down()
    {
        Schema::table('approve_keuangan', function (Blueprint $table) {
            $table->dropForeign(['parent_pengajuan_id']);
            $table->dropColumn('parent_pengajuan_id');
        });
    }
    
};
