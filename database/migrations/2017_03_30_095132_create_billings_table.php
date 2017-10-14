<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('owner_id')->unsigned();
			$table->integer('total');
			$table->integer('power_consumption');
			$table->date('billing_date');
            $table->timestamps();
        });
		
        Schema::table('billings', function($table) {
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
		Schema::table('billings', function($table) {
			$table->dropForeign('billings_owner_id_foreign');
		});
		
        Schema::dropIfExists('billings');
    }
}
