<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsFileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents__file', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('mimeType');
            $table->text('description');

            $table->string('originalFilename');
            $table->string('fileExtension');
            $table->string('md5Checksum');
            $table->bigInteger('fileSize')->unsigned();

            $table->boolean('shared');

            $table->integer('user_id')->unsigned();

            $table->integer('pool_id')->unsigned();

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
        Schema::drop('documents__file');
    }
}
