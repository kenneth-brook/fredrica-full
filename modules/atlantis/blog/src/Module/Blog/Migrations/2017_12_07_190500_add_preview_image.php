<?php

use Illuminate\Database\Migrations\Migration;

class AddPreviewImage extends Migration
{
    /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('blog', 'imgs')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->text("imgs");
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('blog', 'imgs')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("imgs");
      });
    }
  }    

}
