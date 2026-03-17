<?php

namespace App\Providers;

use App\Events\BankDepositPendingCreated;
use App\Events\BankDepositValidated;
use App\Events\ExamSessionClosed;
use App\Events\ExamSessionCreated;
use App\Events\ExamSessionOpened;
use App\Listeners\SendDepositPendingNotifications;
use App\Listeners\SendDepositValidatedNotifications;
use App\Listeners\SendSessionClosedNotifications;
use App\Listeners\SendSessionCreatedNotifications;
use App\Listeners\SendSessionOpenedNotifications;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        BankDepositPendingCreated::class => [
            SendDepositPendingNotifications::class,
        ],
        BankDepositValidated::class => [
            SendDepositValidatedNotifications::class,
        ],
        ExamSessionCreated::class => [
            SendSessionCreatedNotifications::class,
        ],
        ExamSessionClosed::class => [
            SendSessionClosedNotifications::class,
        ],
        ExamSessionOpened::class => [
            SendSessionOpenedNotifications::class,
        ],
    ];

    public function boot()
    {
        //
    }
}
