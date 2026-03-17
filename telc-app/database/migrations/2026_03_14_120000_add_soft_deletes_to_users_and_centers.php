<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToUsersAndCenters extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        if (! Schema::hasColumn('centers', 'deleted_at')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('users', 'deleted_at')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        if (Schema::hasColumn('centers', 'deleted_at')) {
            Schema::table('centers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
}
