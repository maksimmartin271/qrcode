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
        Schema::table('qr_statistics', function (Blueprint $table) {
            $table->foreign('qr_code_id')
                ->references('id')
                ->on('qr_codes')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_statistics', function (Blueprint $table) {
            //
        });
    }
};
