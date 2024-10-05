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
            // Ubah kolom user_id untuk mengizinkan null dan set default ke 1
            $table->unsignedBigInteger('user_id')->nullable()->default(1)->change();
        });
    }

    public function down()
    {
        Schema::table('approve_keuangan', function (Blueprint $table) {
            // Kembalikan perubahan ke kondisi semula (jika perlu)
            $table->unsignedBigInteger('user_id')->nullable(false)->default(null)->change();
        });
    }
};
