<?php

namespace App\Providers;

use App\Events\CustomerApprovalUpdated;
use App\Events\OrderCreated;
use App\Events\SellSubmissionCreated;
use App\Events\ServiceOrderCreated;
use App\Listeners\SendCustomerApprovalNotification;
use App\Listeners\SendOrderCreatedNotification;
use App\Listeners\SendSellSubmissionCreatedNotification;
use App\Listeners\SendServiceOrderCreatedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // FCM — notifikasi push ke admin
        Event::listen(OrderCreated::class,           SendOrderCreatedNotification::class);
        Event::listen(ServiceOrderCreated::class,    SendServiceOrderCreatedNotification::class);
        Event::listen(SellSubmissionCreated::class,  SendSellSubmissionCreatedNotification::class);
        Event::listen(CustomerApprovalUpdated::class, SendCustomerApprovalNotification::class);
    }
}
