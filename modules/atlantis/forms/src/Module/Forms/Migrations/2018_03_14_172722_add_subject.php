<?php

use Illuminate\Database\Migrations\Migration;

class AddSubject extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms', 'subject')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string("subject");
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    
    if (Schema::hasColumn('forms', 'subject')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("subject");
      });
    }
  }

}
