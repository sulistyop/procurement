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
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->unsignedBigInteger('parent_pengajuan_id')->nullable();
            $table->foreign('parent_pengajuan_id')->references('id')->on('parent_pengajuan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['parent_pengajuan_id']);
            $table->dropColumn('parent_pengajuan_id');
        });
    }

};
