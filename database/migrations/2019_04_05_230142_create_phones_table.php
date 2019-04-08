<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->bigInteger('contact_id')->unsigned()->index();
            $table->string('number');
            $table->string('label');
            $table->timestamps();
        });

        Schema::table('phones', function (Blueprint $table){
            $table->primary(['contact_id', 'number']);
            $table->foreign('contact_id')->references('id') ->on('contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phones');
    }
}
