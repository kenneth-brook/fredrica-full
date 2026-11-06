<?php

use Illuminate\Database\Migrations\Migration;

class AddUserFirstLastName extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('users', 'first_name')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("first_name")->nullable()->after('email');
      });
    }
    if (!Schema::hasColumn('users', 'last_name')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("last_name")->nullable()->after('email');
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('users', 'first_name')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("first_name");
      });
    }
    if (Schema::hasColumn('users', 'last_name')) {
      Schema::table('users', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("last_name");
      });
    }
  }
  
  
}
