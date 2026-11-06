<?php

use Illuminate\Database\Migrations\Migration;

class AddRedirectError extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('forms', 'redirect_url_error')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string("redirect_url_error", 255)->nullable();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    
    if (Schema::hasColumn('forms', 'redirect_url_error')) {
      Schema::table('forms', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("redirect_url_error");
      });
    }
  }

}
