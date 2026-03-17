<?php

namespace App\Listeners;

use App\Events\ExamSessionCreated;
use App\Support\NotificationService;

class SendSessionCreatedNotifications
{
    public function handle(ExamSessionCreated $event): void
    {
        NotificationService::notifySecretariesOfRegistrationPeriod($event->session);
    }
}
