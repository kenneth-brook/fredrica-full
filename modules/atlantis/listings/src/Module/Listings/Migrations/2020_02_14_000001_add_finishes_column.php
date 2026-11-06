<?php

use Illuminate\Database\Migrations\Migration;

class AddFinishesColumn extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('listings', 'finishes')) {
      Schema::table('listings', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("finishes")->nullable()->after('floor_plan');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('listings', 'finishes')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("finishes");
      });
    }
  }
  
  
}
