<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IPSubnets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ip_subnets", function(Blueprint $table) {
            $table->increments("id");
            $table->string("name")->index();
            $table->string("subnet")->index();
            $table->string("start")->default("");
            $table->string("end")->default("");
            $table->integer("creator")->unsigned();
            $table->integer("size")->unsigned()->default(0);
            $table->timestamps();

            $table->foreign("creator")->references("uid")->on("users");


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("ip_subnets");
    }
}
