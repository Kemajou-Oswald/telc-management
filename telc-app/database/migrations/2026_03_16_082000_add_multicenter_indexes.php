<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMulticenterIndexes extends Migration
{
    public function up()
    {
        Schema::table('center_user', function (Blueprint $table) {
            $table->index(['user_id', 'center_id'], 'center_user_user_center_idx');
        });

        Schema::table('exam_session_centers', function (Blueprint $table) {
            $table->index(['center_id', 'exam_session_id'], 'exam_session_centers_center_session_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'users_role_idx');
            $table->index('center_id', 'users_center_id_idx');
            $table->index('is_blocked', 'users_is_blocked_idx');
            $table->index('deleted_at', 'users_deleted_at_idx');
        });

        Schema::table('centers', function (Blueprint $table) {
            $table->index('is_active', 'centers_is_active_idx');
            $table->index('city', 'centers_city_idx');
            $table->index('deleted_at', 'centers_deleted_at_idx');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->index('level', 'exam_sessions_level_idx');
            $table->index('center_id', 'exam_sessions_center_id_idx');
        });
    }

    public function down()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex('exam_sessions_level_idx');
            $table->dropIndex('exam_sessions_center_id_idx');
        });

        Schema::table('centers', function (Blueprint $table) {
            $table->dropIndex('centers_is_active_idx');
            $table->dropIndex('centers_city_idx');
            $table->dropIndex('centers_deleted_at_idx');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_role_idx');
            $table->dropIndex('users_center_id_idx');
            $table->dropIndex('users_is_blocked_idx');
            $table->dropIndex('users_deleted_at_idx');
        });

        Schema::table('exam_session_centers', function (Blueprint $table) {
            $table->dropIndex('exam_session_centers_center_session_idx');
        });

        Schema::table('center_user', function (Blueprint $table) {
            $table->dropIndex('center_user_user_center_idx');
        });
    }
}
