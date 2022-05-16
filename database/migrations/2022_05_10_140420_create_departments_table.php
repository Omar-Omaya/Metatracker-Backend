<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\department;
use App\Model\Employee;

class CreateDepartmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     * 
     */

        //      'D_name',
        // 'const_Arrival_time',
        // 'const_Leave_time',
        // 'lat',
        // 'lng'
    public function up()
    {
        Schema::create('departments', function (Blueprint $table) {
            $table->id('id');
            $table->string('dep_name');
            $table->string('Const_Arrival_time');
            $table->string('Const_Leave_time');
            $table->string('Position');
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
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
        Schema::dropIfExists('departments');
    }
}
