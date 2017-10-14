<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->string('name');
			$table->string('type');
			$table->integer('max_power');
            $table->timestamps();
        });
		
        Schema::table('buildings', function($table) {
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
		Schema::table('buildings', function($table) {
			$table->dropForeign('buildings_owner_id_foreign');
		});
		
        Schema::dropIfExists('buildings');
    }
}
