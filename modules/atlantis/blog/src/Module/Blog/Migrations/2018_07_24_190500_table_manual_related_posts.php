<?php

use Illuminate\Database\Migrations\Migration;

class tableManualRelatedPosts extends Migration
{
    /**
   * Run the migrations.
   */
  public function up() {
    if (!Schema::hasTable('blog_related_posts')) {
      Schema::create('blog_related_posts', function(\Illuminate\Database\Schema\Blueprint $table) {
        $table->increments('id');
        $table->integer("blog_id");
        $table->integer("related_blog_id");
        $table->timestamps();
      });
    }
  }

  /**
   * Reverse the migrations.
   */
  public function down() {
   
  }    

}
