<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostcastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postcasts', function (Blueprint $table) {
            $table->id();
            $table->string('feedUrl');
            $table->string('short_url');
            $table->string('https_cover')->nullable();
            $table->text('keywords')->nullable();
            $table->text('copyright');
            $table->text('long_description')->nullable();
            $table->unsignedBigInteger('downloads');
            $table->string('title');
            $table->string('itunes_cover')->nullable();
            $table->string('author');
            $table->string('type');
            $table->string('email');
            $table->text('tags')->nullable();
            $table->text('link');
            $table->string('key');
            $table->text('description');
            $table->string('cover');
            $table->text('raw_description');
            $table->dateTime('release_date');
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
        Schema::dropIfExists('postcasts');
    }
}
