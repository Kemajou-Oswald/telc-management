<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ArchiveOldSessions extends Command
{
    protected $signature = 'sessions:archive-old';
    protected $description = 'Archive les sessions de plus de 2 ans';

    public function handle()
    {
        $this->info("Début de l'archivage des anciennes sessions...");

        $twoYearsAgo = Carbon::now()->subYears(2);

        $sessionsToArchive = DB::table('exam_sessions')
            ->where('exam_date', '<', $twoYearsAgo)
            ->whereNull('archived_at')
            ->get();

        if ($sessionsToArchive->isEmpty()) {
            $this->info('Aucune session à archiver.');
            return 0;
        }

        $count = 0;
        foreach ($sessionsToArchive as $session) {
            DB::table('exam_sessions')
                ->where('id', $session->id)
                ->update(['archived_at' => now()]);

            $count++;
            $this->line("Session archivée: {$session->title} ({$session->exam_date})");
        }

        $this->info("{$count} sessions ont été archivées avec succès.");
        return $count;
    }
}