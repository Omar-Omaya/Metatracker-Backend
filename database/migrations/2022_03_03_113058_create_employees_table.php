<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedBigInteger('department_id');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('path_image')->nullable();
            $table->integer('phone');
            $table->string('gender');
            $table->integer('Arrival_time');
            $table->integer('Leave_time');
            $table->integer('absence_day')->nullable();
            $table->string('position');
            $table->boolean('Is_Here')->default(false);
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('api_token')->default('');
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
        Schema::dropIfExists('employees');
    }
}
