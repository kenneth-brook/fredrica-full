<?php

/*
 * Migration: Lots
 * @Atlantis CMS
 * v 1.0
 */

use Illuminate\Database\Migrations\Migration;

class AddVideoColumn extends Migration {

        public function up() {
                if (!Schema::hasColumn('lots', 'video')) {
                        Schema::table('lots', function(\Illuminate\Database\Schema\Blueprint $table) {
                                $table->text("video")->after('imgs')->nullable();
                        });
                }
        }
        public function down() {
                if (Schema::hasColumn('lots', 'video')) {
                        Schema::table('lots', function(\Illuminate\Database\Schema\Blueprint $table) {
                                $table->dropColumn("video");
                        });
                }
        }

}
