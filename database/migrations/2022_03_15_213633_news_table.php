<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('news', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('title', 255);
            $table->string('description', 255);
            $table->longText('content');
            $table->string('slug', 255);
            $table->string('thumb', 255);
            $table->bigInteger('product_id');
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

        Schema::dropIfExists('news');
    }
}
