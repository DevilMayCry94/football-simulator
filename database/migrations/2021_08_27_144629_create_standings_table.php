<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStandingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('standings', function (Blueprint $table) {
            $table->id();
            $table->integer('position');
            $table->integer('week');
            $table->unsignedBigInteger('league_id');
            $table->unsignedBigInteger('team_id');
            $table->integer('goal_for')->default(0);
            $table->integer('goal_against')->default(0);
            $table->integer('goal_difference')->default(0);
            $table->integer('win')->default(0);
            $table->integer('lost')->default(0);
            $table->integer('draw')->default(0);
            $table->integer('points')->default(0);
            $table->timestamps();

            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standings');
    }
}
