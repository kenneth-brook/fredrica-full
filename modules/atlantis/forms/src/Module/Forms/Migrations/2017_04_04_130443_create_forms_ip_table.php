<?php

/*
 * Migration: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateFormsIpTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('forms_ip')) {
      Schema::create('forms_ip', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer("form_id");
        $table->string("ip", 255)->nullable();
        $table->integer("banned")->default(0);
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('forms_ip')) {
      Schema::drop('forms_ip');
    }
  }

}
