<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddAdminControlFieldsToSessionsUsersAndRegistrations extends Migration
{
    public function up()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->string('city')->nullable()->after('level');
        });

        DB::statement('ALTER TABLE exam_sessions MODIFY closes_at DATE NULL');

        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_blocked')->default(false)->after('role');
            $table->timestamp('blocked_at')->nullable()->after('is_blocked');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->string('status')->default('active')->after('paid_at');
            $table->timestamp('cancelled_at')->nullable()->after('status');
            $table->text('cancel_reason')->nullable()->after('cancelled_at');
        });
    }

    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn(['status', 'cancelled_at', 'cancel_reason']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_blocked', 'blocked_at']);
        });

        DB::statement('ALTER TABLE exam_sessions MODIFY closes_at DATE NOT NULL');

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropColumn('city');
        });
    }
}
