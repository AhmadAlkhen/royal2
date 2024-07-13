<?php

namespace App\Providers;

use App\Events\TransferEmployeeCreated;
use App\Events\LeaveEmployeeCreated;
use App\Events\NewEmployeeCreated;
use App\Events\ResignationEmployeeCreated;
use App\Events\TerminationEmployeeCreated;
use App\Events\EmployeeDiscountCreated;
use App\Events\SalaryIncrementCreated;
use App\Listeners\SendNewEmployeeCreatedNotification;
use App\Listeners\SendLeaveEmployeeCreatedNotification;
use App\Listeners\SendTransferEmployeeCreatedNotification;
use App\Listeners\SendResignationEmployeeCreatedNotification;
use App\Listeners\SendTerminationEmployeeCreatedNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Channels\WhatsAppChannel;
use App\Events\LiquidationEmployeeCreated;
use App\Listeners\SendEmployeeDiscountCreatedNotification;
use App\Listeners\SendLiquidationEmployeeCreatedNotification;
use App\Listeners\SendSalaryIncrementCreatedNotification;
use Illuminate\Support\Facades\Notification;


class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        NewEmployeeCreated::class => [
            SendNewEmployeeCreatedNotification::class
        ],
        TransferEmployeeCreated::class => [
            SendTransferEmployeeCreatedNotification::class
        ],
        LeaveEmployeeCreated::class => [
            SendLeaveEmployeeCreatedNotification::class
        ],
        ResignationEmployeeCreated::class => [
            SendResignationEmployeeCreatedNotification::class
        ],
        TerminationEmployeeCreated::class => [
            SendTerminationEmployeeCreatedNotification::class
        ],
        LiquidationEmployeeCreated::class => [
            SendLiquidationEmployeeCreatedNotification::class
        ],
        EmployeeDiscountCreated::class => [
            SendEmployeeDiscountCreatedNotification::class
        ],
        SalaryIncrementCreated::class => [
            SendSalaryIncrementCreatedNotification::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Notification::extend('whatsapp', function ($app) {
            return new WhatsAppChannel();
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
