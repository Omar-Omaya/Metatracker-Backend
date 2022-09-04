<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonthlyProductivtiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_productivties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onDelete('cascade');
            $table->integer('total_working_hours');
            $table->integer('actual_working_hours');
            $table->integer('delay_hours');
            $table->integer('overtime_hours');
            $table->integer('absent_days');
            $table->integer('salary');
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
        Schema::dropIfExists('monthly_productivties');
    }
}
