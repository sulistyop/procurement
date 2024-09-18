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
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->boolean('is_approve')->default(false); // Kolom untuk status approval
            $table->timestamp('approved_at')->nullable();  // Kolom untuk mencatat waktu approval
            $table->unsignedBigInteger('approved_by')->nullable(); // Kolom untuk mencatat siapa yang melakukan approval
        });
    }
    
    public function down(): void
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn('is_approve');
            $table->dropColumn('approved_at');
            $table->dropColumn('approved_by');
        });
    }
};
