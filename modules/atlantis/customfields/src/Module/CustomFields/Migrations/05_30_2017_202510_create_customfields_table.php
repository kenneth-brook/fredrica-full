<?php

/*
 * Migration: CustomFields
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {

        if (!Schema::hasTable('customfields'))
        {

            Schema::create('customfields', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->increments('id');
                $table->string("field_label");
                $table->string("field_type");
                $table->string('field_slur');
                $table->integer('category_id');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('customfields_values'))
        {

            Schema::create('customfields_values', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->increments('id');
                $table->integer('page_id');
                $table->integer('version_id');
                $table->integer("field_id");
                $table->text("field_value");
                $table->string('lang');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('customfields_categories'))
        {

            Schema::create('customfields_categories', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->increments('id');
                $table->string("category_name");
                $table->string("template");
                $table->timestamps();
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('customfields');
        Schema::dropIfExists('customfields_values');
        Schema::dropIfExists('customfields_categories');
    }

}
