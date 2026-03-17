<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddMulticenterAssignmentsAndSessionCenters extends Migration
{
    public function up()
    {
        Schema::create('center_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('center_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['center_id', 'user_id']);
        });

        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->foreignId('center_id')->nullable()->after('title')->constrained()->nullOnDelete();
        });

        $assignedUsers = DB::table('users')
            ->whereNotNull('center_id')
            ->select('center_id', 'id')
            ->get();

        foreach ($assignedUsers as $assignedUser) {
            DB::table('center_user')->updateOrInsert(
                ['center_id' => $assignedUser->center_id, 'user_id' => $assignedUser->id],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }

        $sessions = DB::table('exam_sessions')
            ->select('id', 'city')
            ->whereNull('center_id')
            ->get();

        foreach ($sessions as $session) {
            if (! $session->city) {
                continue;
            }

            $matchingCenterIds = DB::table('centers')
                ->where('city', $session->city)
                ->pluck('id');

            if ($matchingCenterIds->count() === 1) {
                DB::table('exam_sessions')
                    ->where('id', $session->id)
                    ->update(['center_id' => $matchingCenterIds->first()]);
            }
        }
    }

    public function down()
    {
        Schema::table('exam_sessions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('center_id');
        });

        Schema::dropIfExists('center_user');
    }
}
