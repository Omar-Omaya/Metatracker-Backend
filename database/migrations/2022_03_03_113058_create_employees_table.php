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
            $table->id();
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('weekend_id');

            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('path_image')->nullable();
            $table->integer('phone');
            $table->string('position');
            $table->integer('paid')->default('0');
            $table->integer('salary');
            
            $table->boolean('Is_Here')->default(false);
            $table->string('api_token')->default('');
            $table->string('mobile_token')->default('');
            // $table->foreign('company_id')
            // ->references('id')
            // ->on('companies')
            // ->onDelete('cascade');
            // $table->foreign('department_id')
            //         ->references('id')
            //         ->on('department');             
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
