<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengajuan', function (Blueprint $table) {
            $table->id();
            $table->string('prodi', 100);
            $table->string('judul', 255);
            $table->string('edisi', 50)->nullable();
            $table->string('isbn', 20);
            $table->string('penerbit', 100);
            $table->string('author', 100);
            $table->year('tahun');
            $table->integer('eksemplar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan');
    }
};
