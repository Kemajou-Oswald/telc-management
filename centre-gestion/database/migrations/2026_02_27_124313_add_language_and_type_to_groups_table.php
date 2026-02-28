<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLanguageAndTypeToGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Ajout de la langue si elle manque
            if (!Schema::hasColumn('groups', 'language')) {
                $table->string('language')->nullable()->after('name');
            }

            // Ajout du type (Standard, Vorbereitung...) si il manque
            if (!Schema::hasColumn('groups', 'type')) {
                $table->string('type')->nullable()->after('language');
            }
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['language', 'type']);
        });
    }
}
