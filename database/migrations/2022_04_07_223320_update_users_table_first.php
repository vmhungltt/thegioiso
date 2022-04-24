<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableFirst extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            //  $table->integer('business_platform_id');
              $table->string('city_id', 10);
              $table->string('district_id', 10);
              $table->string('wards_id', 10);
              $table->string('detail_address', 255);
              $table->string('phone_number', 18);
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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('city_id');
            $table->dropColumn('district_id');
            $table->dropColumn('wards_id');
            $table->dropColumn('detail_address');
            $table->dropColumn('phone_number');
        });
    }
}
