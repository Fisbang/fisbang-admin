<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\UserRole;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('user_roles');
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role_name',20);
        });
		
		$role_names = ['Customer', 'Admin'];
        foreach ($role_names as $role_name) 
	        App\UserRole::create(['role_name' => $role_name]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
