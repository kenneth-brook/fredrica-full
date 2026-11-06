<?php

use Illuminate\Database\Migrations\Migration;

class AddEmailFrom extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms_items', 'use_email_from')) {
      Schema::table('forms_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->integer("use_email_from")->default(0);
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    
    if (Schema::hasColumn('forms_items', 'use_email_from')) {
      Schema::table('forms_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("use_email_from");
      });
    }
  }

}
