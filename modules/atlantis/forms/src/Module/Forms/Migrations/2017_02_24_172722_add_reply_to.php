<?php

use Illuminate\Database\Migrations\Migration;

class AddReplyTo extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms_items', 'use_reply_to')) {
      Schema::table('forms_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->integer("use_reply_to")->default(0);
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    
    if (Schema::hasColumn('forms_items', 'use_reply_to')) {
      Schema::table('forms_items', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("use_reply_to");
      });
    }
  }

}
