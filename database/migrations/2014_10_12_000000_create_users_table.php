<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Str;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('auth_token')->unique();
            $table->timestamps();
        });

        \Illuminate\Support\Facades\DB::table('users')->insert([
            ['name' => 'user1','auth_token' => 'jc1NoH4vjaOLba8xfwbwLl4xkxanMpeVKPJ32r2fPYFverU3E3hcMfa6HNcz'],
            ['name' => 'user2','auth_token' => Str::random(60)],
            ['name' => 'user3','auth_token' => Str::random(60)],
            ['name' => 'user4','auth_token' => Str::random(60)]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
