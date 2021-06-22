<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRoundplayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roundplayers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gameid');
            $table->unsignedBigInteger('roundid');
            $table->unsignedBigInteger('playerid');
            $table->string('cat')->nullable();
            $table->unsignedBigInteger('no');
            $table->json('detail')->nullable();
            $table->integer('active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roundplayers');
    }
}
