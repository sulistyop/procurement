<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approve_keuangan', function (Blueprint $table) {
            $table->id();
            $table->string('nomorSurat'); 
            $table->string('surat'); 
            $table->string('nomorBukti');
            $table->string('buktiTransaksi'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approve_keuangan');
    }
};
