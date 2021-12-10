<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string("address");
            $table->dateTime("date");
            $table->datetime("leaving_the_office");
            $table->dateTime("arrive_office");
            $table->decimal("distance");
            $table->timestamps();
            $table->unsignedBigInteger("contacts_id");
            $table->foreign("contacts_id")->references("id")->on("contacts")->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
