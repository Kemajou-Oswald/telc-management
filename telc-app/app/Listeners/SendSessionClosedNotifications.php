<?php

namespace App\Listeners;

use App\Events\ExamSessionClosed;
use App\Support\NotificationService;

class SendSessionClosedNotifications
{
    public function handle(ExamSessionClosed $event): void
    {
        NotificationService::notifySecretariesOfSessionClosed($event->session);
    }
}
