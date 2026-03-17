<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('exam_sessions')
            ->whereNotIn('level', ['B1', 'B2', 'C1'])
            ->update(['level' => 'B1']);

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE exam_sessions MODIFY level ENUM('B1', 'B2', 'C1') NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE exam_sessions MODIFY level VARCHAR(255) NOT NULL");
        }
    }
};