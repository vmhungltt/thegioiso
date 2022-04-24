<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TransportFeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('transport_fee', function (Blueprint $table) {
            $table->bigIncrements('id', 20);
            $table->string('city', 255);
            $table->string('district', 255);
            $table->string('ward', 255);
            $table->integer('ship');
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
        Schema::dropIfExists('transport_fee');
    }
}
