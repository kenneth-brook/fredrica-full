<?php


/*
 * Migration: AddFormAttributes
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class AddFormAttributes extends Migration {

    /**
     * Run the migrations.
     */
    public function up()
    {

        if (!Schema::hasTable('forms') || Schema::hasColumn('forms', 'custom_attributes')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->text('custom_attributes')->nullable();
        });
    }

    public function down() {

        if (!Schema::hasTable('forms') || !Schema::hasColumn('forms', 'custom_attributes')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->dropColumn("custom_attributes");
        });



    }
}