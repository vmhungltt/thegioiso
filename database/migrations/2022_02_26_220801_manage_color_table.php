<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ManageColorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manage_color', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->bigInteger('product_id');
            $table->string('name_color');
            $table->integer('price');
            $table->integer('price_sale');
            $table->integer('active');
            $table->string('thumb', 255);
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
        Schema::dropIfExists('manage_color');
    }
}
