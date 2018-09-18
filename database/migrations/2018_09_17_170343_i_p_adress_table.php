<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class IPAdressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("ip_adresses", function(Blueprint $table){
            $table->increments("id");
            $table->string("adress")->index();
            $table->string("hostname")->default("")->index();
            $table->integer("subnet")->unsigned();
            $table->string("status")->default("created");
            $table->text("open_ports");
            $table->integer("lastFound")->default(0);
            $table->timestamps();
            $table->foreign("subnet")->references("id")->on("ip_subnets");

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("ip_adresses");
    }
}
