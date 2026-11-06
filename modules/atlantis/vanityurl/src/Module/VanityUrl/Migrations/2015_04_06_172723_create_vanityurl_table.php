<?php

/*
 * Migration: VanityUrl
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateVanityurlTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('vanityurl')) {
      Schema::create('vanityurl', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->string("source_url");
        $table->string("dest_url");
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('vanityurl')) {
      Schema::drop('vanityurl');
    }
  }

}
