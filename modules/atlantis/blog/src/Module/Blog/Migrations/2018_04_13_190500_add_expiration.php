<?php

use Illuminate\Database\Migrations\Migration;

class AddExpiration extends Migration
{
    /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('blog', 'expire_date')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dateTime("expire_date")->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('blog', 'expire_date')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("expire_date");
      });
    }
  }    

}
