<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
			$table->integer('sender_id')->unsigned();
			$table->integer('question_id')->unsigned();
			$table->text('content');
            $table->timestamps();
        });
		
        Schema::table('messages', function($table) {
			$table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('messages', function($table) {
			$table->dropForeign('messages_sender_id_foreign');
			$table->dropForeign('messages_question_id_foreign');
		});
		
        Schema::dropIfExists('messages');
    }
}
