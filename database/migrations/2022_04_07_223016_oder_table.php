<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('oder', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->bigInteger('user_id');
            $table->integer('transport_fee');
            $table->integer('discount_code');
            $table->string('note');
            $table->string('name');
            $table->string('phone_number');
            $table->string('address_detail');
            $table->integer('total');
            $table->integer('active');
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
        Schema::dropIfExists('oder');
    }
}
