<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeekEndsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('week_ends', function (Blueprint $table) {
            $table->id();
            $table->boolean('saturday')->default('0');
            $table->boolean('sunday')->default('0');
            $table->boolean('monday')->default('0');
            $table->boolean('tuesday')->default('0');
            $table->boolean('wednesday')->default('0');
            $table->boolean('thursday')->default('0');
            $table->boolean('friday')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('week_ends');
    }
}
