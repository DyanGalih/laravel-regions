<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('indonesia_districts', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->unsignedBigInteger('regency_id');
            $table->string('name')->index();

            $table->foreign('regency_id')
                ->references('id')
                ->on('indonesia_regencies')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('indonesia_districts');
    }
};
