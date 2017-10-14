<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_recipients', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('article_id')->unsigned();
            $table->timestamps();
        });
		
        Schema::table('article_recipients', function($table) {
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
			$table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('article_recipients', function($table) {
			$table->dropForeign('article_recipients_user_id_foreign');
			$table->dropForeign('article_recipients_article_id_foreign');
		});
		
        Schema::dropIfExists('article_recipients');
    }
}
