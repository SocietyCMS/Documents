<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsObjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents__objects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('uid')->index()->unique();

            $table->string('title')->index();
            $table->string('mimeType')->index();
            $table->string('tag')->index();
            $table->text('description');

            $table->string('parent_uid')->nullable();

            $table->string('originalFilename')->index();
            $table->string('fileExtension');
            $table->string('md5Checksum')->index();
            $table->bigInteger('fileSize')->unsigned()->nullable();

            $table->boolean('shared')->default(false);

            $table->integer('user_id')->unsigned();

            $table->string('pool_uid')->index();

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
        Schema::drop('documents__objects');
    }
}
