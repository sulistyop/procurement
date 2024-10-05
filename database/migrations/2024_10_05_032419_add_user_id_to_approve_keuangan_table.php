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
            $table->foreignId('user_id')->constrained()->after('buktiTransaksi');
        });
    }

    public function down()
    {
        Schema::table('approve_keuangan', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

};
