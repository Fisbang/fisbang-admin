<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserRoleToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
			$table->integer('role_id')->unsigned()->default(1);
		});
		
        Schema::table('users', function($table) {
			$table->foreign('role_id')->references('id')->on('user_roles');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
			$table->dropForeign('users_role_id_foreign');
			
			$table->dropColumn('role_id');
		});
    }
}
