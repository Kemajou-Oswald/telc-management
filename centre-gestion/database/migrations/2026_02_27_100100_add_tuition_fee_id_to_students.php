<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTuitionFeeIdToStudents extends Migration
{
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('tuition_fee_id')
                ->nullable()
                ->after('group_id')
                ->constrained('tuition_fees')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['tuition_fee_id']);
        });
    }
}
