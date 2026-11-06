<?php

use Illuminate\Database\Migrations\Migration;

class AddFloodControl extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms', 'use_flood_control')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->integer("use_flood_control")->default(0);
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    
    if (Schema::hasColumn('forms', 'use_flood_control')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("use_flood_control");
      });
    }
  }

}
