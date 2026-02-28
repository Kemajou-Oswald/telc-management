<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLevelAndGroupToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            // On ajoute level_id s'il n'existe pas
            if (!Schema::hasColumn('students', 'level_id')) {
                $table->foreignId('level_id')->nullable()->constrained('levels')->onDelete('set null');
            }

            // On ajoute group_id s'il n'existe pas
            if (!Schema::hasColumn('students', 'group_id')) {
                $table->foreignId('group_id')->nullable()->constrained('groups')->onDelete('set null');
            }

            // On s'assure aussi que tuition_fee_id existe
            if (!Schema::hasColumn('students', 'tuition_fee_id')) {
                $table->foreignId('tuition_fee_id')->nullable()->constrained('tuition_fees')->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['level_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['tuition_fee_id']);
            $table->dropColumn(['level_id', 'group_id', 'tuition_fee_id']);
        });
    }
}
