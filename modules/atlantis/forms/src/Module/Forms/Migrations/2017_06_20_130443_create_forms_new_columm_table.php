<?php

/*
 * Migration: Forms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateFormsNewColummTable extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms', 'use_front_end_validation')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->integer('use_front_end_validation')->default(0);
      });
    }

    if (!Schema::hasColumn('forms', 'error_wrapper_class')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string('error_wrapper_class', 255)->nullable();
      });
    }

    if (!Schema::hasColumn('forms', 'input_error_class')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string('input_error_class', 255)->nullable();
      });
    }

    if (!Schema::hasColumn('forms', 'error_message')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string('error_message', 255)->nullable();
      });
    }
  }

  public function down() {

    if (Schema::hasColumn('forms', 'use_front_end_validation')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("use_front_end_validation");
      });
    }

    if (Schema::hasColumn('forms', 'error_wrapper_class')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("error_wrapper_class");
      });
    }

    if (Schema::hasColumn('forms', 'input_error_class')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("input_error_class");
      });
    }

    if (Schema::hasColumn('forms', 'error_message')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("error_message");
      });
    }
  }

}
