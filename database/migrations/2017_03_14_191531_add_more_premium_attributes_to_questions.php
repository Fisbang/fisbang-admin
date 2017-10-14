<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMorePremiumAttributesToQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function($table) {
			$table->integer('billing')->unsigned()->default(0);
			$table->integer('hours')->unsigned()->default(0);
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
			$table->dropColumn('billing');		
			$table->dropColumn('hours');
		});
    }
}
