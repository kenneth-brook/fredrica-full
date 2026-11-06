<?php

use Illuminate\Database\Migrations\Migration;

class AddBlogCategories extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasTable('blog_categories'))
        {
            Schema::create('blog_categories', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->increments('id');
                $table->string("category_name", 255);

                $table->timestamps();
            });
        }

        if (!Schema::hasTable('blog_categories_lu'))
        {
            Schema::create('blog_categories_lu', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->integer("blog_id");
                $table->integer('category_id');
            });
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasTable('blog_categories'))
        {
            Schema::drop('blog_categories');
        }

        if (Schema::hasTable('blog_categories_lu'))
        {
            Schema::drop('blog_categories_lu');
        }

    }

}
