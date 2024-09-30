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
            $table->timestamp('reject_at')->nullable()->after('is_reject');
            $table->string('reject_by')->nullable()->after('reject_at');
        });
    }

    public function down()
    {
        Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropColumn(['reject_at', 'reject_by']);
        });
    }
};
