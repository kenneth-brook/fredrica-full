<?php

use Illuminate\Database\Migrations\Migration;

class AddMetaInfo extends Migration {

  /**
   * Run the migrations.
   */
  public function up() {

    if (!Schema::hasColumn('blog', 'seo_title')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string("seo_title");
      });
    }

    if (!Schema::hasColumn('blog', 'meta_description')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->string("meta_description");
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {

    if (Schema::hasColumn('blog', 'seo_title')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("seo_title");
      });
    }

    if (Schema::hasColumn('blog', 'meta_description')) {
      Schema::table('blog', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->dropColumn("meta_description");
      });
    }
  }

}
