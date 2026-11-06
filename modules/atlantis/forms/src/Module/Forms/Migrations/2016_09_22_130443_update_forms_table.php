<?php


/*
 * Migration: UpdateForms
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class UpdateFormsTable extends Migration {

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('forms') || Schema::hasColumn('forms', 'hide_form_upon_success')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->tinyInteger('hide_form_upon_success')->nullable();
        });
    }

    public function down() {

        if (!Schema::hasTable('forms') || !Schema::hasColumn('forms', 'hide_form_upon_success')) {
            return;

        }
        Schema::table('forms', function ($table) {
            $table->dropColumn("hide_form_upon_success");
        });



    }
}