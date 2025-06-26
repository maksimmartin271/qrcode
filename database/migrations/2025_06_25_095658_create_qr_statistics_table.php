<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('qr_statistics', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('qr_code_id');
            // другие поля
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('qr_statistics');
    }
};
