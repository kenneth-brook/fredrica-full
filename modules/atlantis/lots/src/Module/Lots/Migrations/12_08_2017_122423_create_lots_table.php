<?php

/*
 * Migration: Lots
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateLotsTable extends Migration {

        /**
        * Run the migrations.
        */
        public function up()
        {
                if (!Schema::hasTable('lots')) {
                        Schema::create('lots', function(\Illuminate\Database\Schema\Blueprint $table)
                        {
                                $table->increments('id');
                                $table->string("lot_number")->nullable();
                                $table->integer("sold")->nullable();
                                $table->string("location")->nullable();
                                $table->string("price")->nullable();
                                $table->string("acreage")->nullable();
                                $table->string("sitemap")->nullable();
                                $table->string("map_category")->nullable();
                                $table->string("lat")->nullable();
                                $table->string("lng")->nullable();
                                $table->text("description")->nullable();
                                $table->string("view_orientation")->nullable();
                                $table->string("frontage")->nullable();
                                $table->string("lot_number_ext")->nullable();
                                $table->string("imgs")->nullable();
                                $table->timestamps();
                        });

                }
        }

        /**
        * Reverse the migrations.
        */
        public function down()
        {
                Schema::dropIfExists('lots');
        }

}
