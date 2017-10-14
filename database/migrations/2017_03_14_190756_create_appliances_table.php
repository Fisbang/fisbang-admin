<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppliancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appliances', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('building_id')->unsigned();
			$table->string('name');
			$table->string('type');
			$table->string('brand')->nullable();
			$table->string('brand_type')->nullable();
			$table->integer('power');
            $table->timestamps();
        });
		
        Schema::table('appliances', function($table) {
			$table->foreign('building_id')->references('id')->on('buildings')->onDelete('cascade');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::table('appliances', function($table) {
			$table->dropForeign('appliances_building_id_foreign');
		});
		
        Schema::dropIfExists('appliances');
    }
}
