<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages_employees', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('employee_id')->unsigned();
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')->onDelete('cascade');

            $table->bigInteger('message_id')->unsigned();
            $table->foreign('message_id')
                ->references('id')
                ->on('messages')->onDelete('cascade');
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
        Schema::dropIfExists('messages_employees');
    }
}
