<?php

use Illuminate\Database\Migrations\Migration;

class AddPagesLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('pages_log', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->increments('id');
            $table->integer('module_id');
            $table->integer('page_id')->nullable();
            $table->integer('pattern_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('pages_log');
    }
}
