<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('comment', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->bigInteger('product_id');
            $table->bigInteger('parent_id');
            $table->integer('vote');
            $table->longText('content');
            $table->string('name', 255);
            $table->string('number_phone', 255);
            $table->string('email', 255);
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
        //
        Schema::dropIfExists('comment');
    }
}
