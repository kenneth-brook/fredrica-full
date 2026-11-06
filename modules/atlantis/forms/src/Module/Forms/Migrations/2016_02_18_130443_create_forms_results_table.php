<?php

/*
 * Migration: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateFormsResultsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('forms_results')) {
      Schema::create('forms_results', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer("set_id");
        $table->integer("form_id");
        $table->text("field_label")->nullable();
        $table->text("field_name")->nullable();
        $table->text("field_value")->nullable();
        $table->string("post_url", 255)->nullable();
        $table->string("IP", 255)->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('forms_results')) {
      Schema::drop('forms_results');
    }
  }

}
