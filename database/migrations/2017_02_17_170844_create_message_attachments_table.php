<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessageAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('message_attachments', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('message_id')->unsigned();
			$table->string('path');
            $table->timestamps();
        });
		
        Schema::table('message_attachments', function($table) {
			$table->foreign('message_id')->references('id')->on('messages')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('message_attachments', function($table) {
			$table->dropForeign('message_attachments_message_id_foreign');
		});
		
        Schema::dropIfExists('message_attachments');
    }
}
