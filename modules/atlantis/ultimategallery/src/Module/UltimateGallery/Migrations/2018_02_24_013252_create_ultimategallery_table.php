<?php

/*
 * Migration: UltimateGallery
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateUltimateGalleryTable extends Migration {

        /**
        * Run the migrations.
        */
        public function up()
        {
                if (!Schema::hasTable('ultimategallery')) {
                        Schema::create('ultimategallery', function(\Illuminate\Database\Schema\Blueprint $table)
                        {
                                $table->increments('id');
                                $table->string("name");
                                $table->string("description");
                                $table->text("options");
                                $table->text("css");
                                $table->integer("gallery_id");
                                $table->string("view");
                                $table->string("imgs");
                                $table->timestamps();
                        });
                }
        }

        /**
        * Reverse the migrations.
        */
        public function down()
        {
                if (Schema::hasTable('ultimategallery')) {
                        Schema::drop('ultimategallery');
                }
        }

}
