<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('groups', function (Blueprint $table) {
            // Ajout de la capacité si elle manque
            if (!Schema::hasColumn('groups', 'capacity')) {
                $table->integer('capacity')->default(20)->after('teacher_id');
            }
            
            // Ajout des jours si ils manquent
            if (!Schema::hasColumn('groups', 'days')) {
                $table->text('days')->nullable()->after('capacity');
            }

            // Ajout heure début si elle manque
            if (!Schema::hasColumn('groups', 'start_time')) {
                $table->time('start_time')->nullable()->after('days');
            }

            // Ajout heure fin si elle manque
            if (!Schema::hasColumn('groups', 'end_time')) {
                $table->time('end_time')->nullable()->after('start_time');
            }
        });
    }

    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['capacity', 'days', 'start_time', 'end_time']);
        });
    }
};