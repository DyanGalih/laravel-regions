<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('indonesia_regencies', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('province_id');
            $table->string('name')->index();

            $table->foreign('province_id')
                ->references('id')
                ->on('indonesia_provinces')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('indonesia_regencies');
    }
};
