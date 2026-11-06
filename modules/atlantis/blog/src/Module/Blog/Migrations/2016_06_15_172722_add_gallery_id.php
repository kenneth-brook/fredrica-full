<?php

use Illuminate\Database\Migrations\Migration;

class AddGalleryId extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasColumn('blog', 'gallery_id')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->integer("gallery_id");
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
    if (Schema::hasColumn('blog', 'gallery_id')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("gallery_id");
      });
    }
  }

}
