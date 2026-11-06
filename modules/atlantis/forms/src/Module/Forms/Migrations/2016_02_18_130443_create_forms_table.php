<?php

/*
 * Migration: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateFormsTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasTable('forms')) {
      Schema::create('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->string("name", 255)->nullable();
        $table->text("message")->nullable();
        $table->integer("email_check")->default(0);
        $table->string("email_from", 255)->nullable();
        $table->text("emails")->nullable();
        $table->string("form_class", 255)->nullable();
        $table->string("btn_value", 255)->nullable();
        $table->string("btn_class", 255)->nullable();
        $table->integer("captcha")->default(0);
        $table->text("captcha_namespace")->nullable();
        $table->integer("ga")->default(0);
        $table->text("before_form_text")->nullable();
        $table->text("after_form_text")->nullable();
        $table->integer("use_custom_form")->default(0);
        $table->text("custom_form")->nullable();
        $table->string("redirect_url", 255)->nullable();
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasTable('forms')) {
      Schema::drop('forms');
    }
  }

}
