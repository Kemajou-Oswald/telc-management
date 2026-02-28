<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCourseFieldsToTuitionFees extends Migration
{
    public function up()
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            $table->decimal('inscription_fee', 10, 2)->default(10000)->after('total_amount');
            $table->unsignedSmallInteger('duration_weeks')->nullable()->after('currency');
            $table->string('duration_label', 100)->nullable()->after('duration_weeks');
            $table->string('course_type', 50)->default('standard')->after('duration_label');
        });
    }

    public function down()
    {
        Schema::table('tuition_fees', function (Blueprint $table) {
            $table->dropColumn(['inscription_fee', 'duration_weeks', 'duration_label', 'course_type']);
        });
    }
}
