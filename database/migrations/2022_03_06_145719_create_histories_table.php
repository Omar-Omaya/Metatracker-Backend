<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('company_id');
            $table->string('Start_time')->nullable();
            $table->string('End_time')->nullable();
            $table->boolean('Out_of_zone');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->string('Out_of_zone_time')->nullable();
            $table->boolean('is_absence');
            $table->timestamps();
            $table->foreign('employee_id')
            ->references('id')
            ->on('employees')
            ->onDelete('cascade');
            $table->foreign('company_id')
            ->references('id')
            ->on('companies')
            ->onDelete('cascade');
            
            
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histories');
    }
}
