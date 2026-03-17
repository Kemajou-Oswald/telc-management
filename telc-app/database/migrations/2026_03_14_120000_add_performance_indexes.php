<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            $table->index('payment_status', 'registrations_payment_status_idx');
            $table->index('status', 'registrations_status_idx');
            $table->index('registered_at', 'registrations_registered_at_idx');
            $table->index(['center_id', 'payment_status'], 'registrations_center_payment_idx');
            $table->index(['center_id', 'status'], 'registrations_center_status_idx');
            $table->index(['exam_session_id', 'status'], 'registrations_session_status_idx');
        });

        Schema::table('bank_deposits', function (Blueprint $table) {
            $table->index('status', 'bank_deposits_status_idx');
            $table->index('deposit_date', 'bank_deposits_deposit_date_idx');
            $table->index('validated_at', 'bank_deposits_validated_at_idx');
            $table->index(['center_id', 'status'], 'bank_deposits_center_status_idx');
            $table->index(['center_id', 'deposit_date'], 'bank_deposits_center_date_idx');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->index('status', 'exam_sessions_status_idx');
            $table->index('exam_date', 'exam_sessions_exam_date_idx');
            $table->index('opens_at', 'exam_sessions_opens_at_idx');
            $table->index('closes_at', 'exam_sessions_closes_at_idx');
        });

        Schema::table('registration_audits', function (Blueprint $table) {
            $table->index('user_id', 'registration_audits_user_idx');
            $table->index('created_at', 'registration_audits_created_at_idx');
        });

        Schema::table('bank_deposit_audits', function (Blueprint $table) {
            $table->index('user_id', 'bank_deposit_audits_user_idx');
            $table->index('created_at', 'bank_deposit_audits_created_at_idx');
        });

        Schema::table('login_audits', function (Blueprint $table) {
            $table->index('user_id', 'login_audits_user_idx');
            $table->index('created_at', 'login_audits_created_at_idx');
        });

        Schema::table('user_audits', function (Blueprint $table) {
            $table->index('performed_by', 'user_audits_performed_by_idx');
            $table->index('created_at', 'user_audits_created_at_idx');
        });

        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id', 'activity_logs_user_idx');
            $table->index('created_at', 'activity_logs_created_at_idx');
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex('activity_logs_user_idx');
            $table->dropIndex('activity_logs_created_at_idx');
        });

        Schema::table('user_audits', function (Blueprint $table) {
            $table->dropIndex('user_audits_performed_by_idx');
            $table->dropIndex('user_audits_created_at_idx');
        });

        Schema::table('login_audits', function (Blueprint $table) {
            $table->dropIndex('login_audits_user_idx');
            $table->dropIndex('login_audits_created_at_idx');
        });

        Schema::table('bank_deposit_audits', function (Blueprint $table) {
            $table->dropIndex('bank_deposit_audits_user_idx');
            $table->dropIndex('bank_deposit_audits_created_at_idx');
        });

        Schema::table('registration_audits', function (Blueprint $table) {
            $table->dropIndex('registration_audits_user_idx');
            $table->dropIndex('registration_audits_created_at_idx');
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropIndex('exam_sessions_status_idx');
            $table->dropIndex('exam_sessions_exam_date_idx');
            $table->dropIndex('exam_sessions_opens_at_idx');
            $table->dropIndex('exam_sessions_closes_at_idx');
        });

        Schema::table('bank_deposits', function (Blueprint $table) {
            $table->dropIndex('bank_deposits_status_idx');
            $table->dropIndex('bank_deposits_deposit_date_idx');
            $table->dropIndex('bank_deposits_validated_at_idx');
            $table->dropIndex('bank_deposits_center_status_idx');
            $table->dropIndex('bank_deposits_center_date_idx');
        });

        Schema::table('registrations', function (Blueprint $table) {
            $table->dropIndex('registrations_payment_status_idx');
            $table->dropIndex('registrations_status_idx');
            $table->dropIndex('registrations_registered_at_idx');
            $table->dropIndex('registrations_center_payment_idx');
            $table->dropIndex('registrations_center_status_idx');
            $table->dropIndex('registrations_session_status_idx');
        });
    }
}
