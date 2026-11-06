<?php

/*
 * Migration: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateFormsItemsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('forms_items')) {
      Schema::create('forms_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer("form_id");
        $table->string("label", 255)->nullable();
        $table->string("field_type", 255)->nullable();
        $table->string("field_name", 255)->nullable();
        $table->string("validation", 255)->nullable();
        $table->text("attributes")->nullable();
        $table->text("validation_msg")->nullable();
        $table->text("field_value")->nullable();
        $table->integer("weight");
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('forms_items')) {
      Schema::drop('forms_items');
    }
  }

}
