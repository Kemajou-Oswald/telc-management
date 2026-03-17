<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->timestamp('archived_at')->nullable()->after('notes');
            
            // Index pour optimiser les requêtes sur les sessions archivées
            $table->index('archived_at');
        });
    }

    public function down()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex(['archived_at']);
            $table->dropColumn('archived_at');
        });
    }
};
