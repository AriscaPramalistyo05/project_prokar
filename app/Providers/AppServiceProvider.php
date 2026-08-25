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
use App\Models\Product;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->environment('local') && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register ProductObserver
        Product::observe(ProductObserver::class);

        // FCM — notifikasi push ke admin
        Event::listen(OrderCreated::class,           SendOrderCreatedNotification::class);
        Event::listen(ServiceOrderCreated::class,    SendServiceOrderCreatedNotification::class);
        Event::listen(SellSubmissionCreated::class,  SendSellSubmissionCreatedNotification::class);
        Event::listen(CustomerApprovalUpdated::class, SendCustomerApprovalNotification::class);

        // Sync session cart to database when user logs in
        Event::listen(\Illuminate\Auth\Events\Login::class, function ($event) {
            app(\App\Services\CartService::class)->syncSessionToDatabase();
        });

        // Custom branded email notification for Password Reset
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $resetUrl = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Atur Ulang Kata Sandi Akun Prokar Elektronik')
                ->view('emails.reset-password', [
                    'user' => $notifiable,
                    'url' => $resetUrl,
                    'token' => $token,
                ]);
        });
    }
}
