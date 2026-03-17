<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('exam_session_centers')) {
            return;
        }

        if (! Schema::hasColumn('exam_sessions', 'center_id')) {
            return;
        }

        $sessions = DB::table('exam_sessions')
            ->whereNotNull('center_id')
            ->select('id', 'center_id')
            ->get();

        foreach ($sessions as $session) {
            DB::table('exam_session_centers')->updateOrInsert(
                [
                    'exam_session_id' => $session->id,
                    'center_id' => $session->center_id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }

    public function down()
    {
        // No rollback needed for backfill.
    }
};