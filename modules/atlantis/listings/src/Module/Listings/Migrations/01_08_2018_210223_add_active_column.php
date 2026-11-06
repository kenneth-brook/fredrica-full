<?php

/*
 * Migration: Listings
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class AddActiveColumn extends Migration {

        /**
        * Run the migrations.
        */
        public function up()
        {
                if (!Schema::hasColumn('listings', 'active')) {
                        Schema::table('listings', function(\Illuminate\Database\Schema\Blueprint $table)
                        {
                                $table->integer("active")->nullable()->default(1);
                                $table->integer("weight")->nullable()->default(1);
                        });

                }
        }
        public function down()
        {
                
        }

}
