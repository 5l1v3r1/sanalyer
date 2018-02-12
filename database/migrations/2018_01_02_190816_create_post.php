<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePost extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('content');
            $table->text('image')->nullable();
            $table->string('video')->nullable();
            $table->integer('author')->references('id')->on('users')
                ->onDelete('cascade');
            $table->integer('status');
            $table->integer('views')->default(0);
            $table->integer('type')->default(0);
            $table->integer('category');
            $table->integer('location');
            $table->string('tag');
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
        Schema::dropIfExists('posts');
    }
}
