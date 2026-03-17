<?php

namespace App\Observers;

use App\Events\ExamSessionClosed;
use App\Events\ExamSessionCreated;
use App\Events\ExamSessionOpened;
use App\Models\ExamSession;

class ExamSessionObserver
{
    public function created(ExamSession $session): void
    {
        if (ExamSession::supportsMultiCenters()) {
            $session->load('centers');
            if ($session->centers->isEmpty()) {
                return;
            }
        }

        event(new ExamSessionCreated($session));
    }

    public function updated(ExamSession $session): void
    {
        if (! $session->wasChanged('status')) {
            return;
        }

        if ($session->status === ExamSession::STATUS_CLOSED) {
            event(new ExamSessionClosed($session));
        }

        if ($session->status === ExamSession::STATUS_OPEN) {
            event(new ExamSessionOpened($session));
        }
    }
}

