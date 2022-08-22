<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->bigincrements('id');
            $table->unsignedBigInteger('company_id');

            // $table->unsignedBigInteger('admin_id');
            // $table->unsignedBigInteger('department_id');
            // $table->unsignedBigInteger('employee_id');

            $table->string('text');
            // $table->foreign('employee_id')
            // ->references('id')
            // ->on('employees')
            // ->onDelete('cascade');
            // $table->foreign('company_id')
            // ->references('id')
            // ->on('companies')
            // ->onDelete('cascade');
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
        Schema::dropIfExists('messages');
    }
}
