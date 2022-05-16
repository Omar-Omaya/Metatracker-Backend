<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Model\department;
use App\Model\Employee;


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
            $table->bigInteger('department_id')->nullable();
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
            // $table->foreign('department_id')
            //         ->references('id')
            //         ->on('department');
            $table->timestamps();

           
        });
//         Schema::create('employees',function(Blueprint $table){

//             $table->unsignedBigInteger('role_id');
//             $table->foreign('role_id')->references('id')->on('departments');
//             $table->string('permission');

// });
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
