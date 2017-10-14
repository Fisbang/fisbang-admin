<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class AddSuperAdminToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        App\User::create([
			'name' => 'Admin',
			'email' => 'gio@fisbang.com',
			'password' => Hash::make('fisbang098765'),
			'role_id' => 2
		]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        App\User::where('email', 'gio@fisbang.com')->delete();
    }
}
