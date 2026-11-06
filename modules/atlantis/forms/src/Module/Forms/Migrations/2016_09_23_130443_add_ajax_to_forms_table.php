<?php


/*
 * Migration: Add Ajax To Forms Table
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class AddAjaxToFormsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('forms') || Schema::hasColumn('forms', 'submit_with_ajax')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->tinyInteger('submit_with_ajax')->nullable();
        });
    }

    public function down() {

        if (!Schema::hasTable('forms') || !Schema::hasColumn('forms', 'submit_with_ajax')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->dropColumn("submit_with_ajax");
        });



    }
}