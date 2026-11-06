<?php

/*
 * Migration: Listings
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateListingsTable extends Migration {

        /**
        * Run the migrations.
        */
        public function up()
        {
                if (!Schema::hasTable('listings')) {
                        Schema::create('listings', function(\Illuminate\Database\Schema\Blueprint $table)
                        {
                                $table->increments('id');
                                $table->string("title")->nullable();
                                $table->string("url")->nullable();
                                $table->string("type")->nullable();
                                $table->string("listing_type")->nullable();
                                $table->text("description")->nullable();
                                $table->text("details")->nullable();
                                $table->string("price")->nullable();
                                $table->integer("furnished")->nullable();                                
                                $table->string("meta_desc")->nullable();
                                $table->string("meta_key")->nullable();
                                $table->string("heated_sq_ft")->nullable();
                                $table->string("acres")->nullable();
                                $table->string("bedrooms")->nullable();
                                $table->string("full_baths")->nullable();
                                $table->string("half_baths")->nullable();
                                $table->string("architect")->nullable();
                                $table->string("builder")->nullable();
                                $table->string("lot_type")->nullable();
                                $table->string("video")->nullable();
                                $table->string("map_category")->nullable();
                                $table->string("lat")->nullable();
                                $table->string("lng")->nullable();
                                $table->string("home_site_number")->nullable();
                                $table->text("images")->nullable();
                                $table->string("brochure")->nullable();
                                $table->string("floor_plan")->nullable();
                                $table->timestamps();
                        });

                }
        }

        /**
        * Reverse the migrations.
        */
        public function down()
        {
                Schema::dropIfExists('listings');
        }

}
