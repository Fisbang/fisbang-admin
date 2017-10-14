<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->boolean('is_answered');
			$table->boolean('is_premium');
            $table->timestamps();
        });
		
        Schema::table('questions', function($table) {
			$table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('questions', function($table) {
			$table->dropForeign('questions_owner_id_foreign');
		});
		
        Schema::dropIfExists('questions');
    }
}
