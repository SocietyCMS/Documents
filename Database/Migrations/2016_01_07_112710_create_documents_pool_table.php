<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsPoolTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents__pool', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('description');

            $table->bigInteger('quota')->unsigned();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('documents__pool');
    }
}
