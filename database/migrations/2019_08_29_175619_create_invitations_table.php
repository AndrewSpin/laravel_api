<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 50);
            $table->string('description', 255)->nullable();
            $table->integer('sender_id');
            $table->integer('invited_id');
            $table->smallInteger('status')->default(\App\Invitation::STATUS_PENDING);
            $table->timestamps();

            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('invited_id')->references('id')->on('users')->onDelete('cascade');
        });

        \Illuminate\Support\Facades\DB::table('invitations')->insert([
            ['title' => 't1','sender_id'=>1, 'invited_id' => 2],
            ['title' => 't2','sender_id'=>1, 'invited_id' => 3],
            ['title' => 't3','sender_id'=>2, 'invited_id' => 1],
            ['title' => 't4','sender_id'=>3, 'invited_id' => 1],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
