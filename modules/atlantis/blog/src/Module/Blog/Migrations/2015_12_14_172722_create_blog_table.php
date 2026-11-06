<?php

use Illuminate\Database\Migrations\Migration;

class CreateBlogTable extends Migration
{

    /**
     * Run the migrations.
     */
    public function up()
    {
        //
        if (!Schema::hasTable('blog'))
        {

            Schema::create('blog', function (\Illuminate\Database\Schema\Blueprint $table)
            {
                $table->increments('id');
                $table->string("title", 255);
                $table->text("body");
                $table->text("blurb");
                $table->integer("user_id")->nullable();
                $table->string("url", 255);
                $table->string("status", 255);
                $table->string("nickname", 255);
                $table->dateTime("posted_date")->nullable();
                $table->integer("allow_comments")->default(1);
                $table->integer("use_blurb")->default(0);
                $table->integer("body_words");
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {

        if (Schema::hasTable('blog'))
        {
            Schema::drop('blog');
            \Atlantis\Models\Repositories\TagRepository::deleteTags('blog');
        }
    }

}
