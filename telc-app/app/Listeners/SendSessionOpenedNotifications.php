<?php

namespace App\Listeners;

use App\Events\ExamSessionOpened;
use App\Support\NotificationService;

class SendSessionOpenedNotifications
{
    public function handle(ExamSessionOpened $event): void
    {
        NotificationService::notifySecretariesOfRegistrationPeriod($event->session);
    }
}
