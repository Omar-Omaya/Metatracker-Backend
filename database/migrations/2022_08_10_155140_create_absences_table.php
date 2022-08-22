<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->boolean('pending');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('company_id');

           
            $table->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onDelete('cascade');

            $table->date('Day');
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
        Schema::dropIfExists('absences');
    }
}
