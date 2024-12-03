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
        Schema::create('approve_keuangan_parent_pengajuan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('approve_keuangan_id')->constrained('approve_keuangan')->onDelete('cascade');
            $table->foreignId('parent_pengajuan_id')->constrained('parent_pengajuan')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('approve_keuangan_parent_pengajuan');
    }
    
};
