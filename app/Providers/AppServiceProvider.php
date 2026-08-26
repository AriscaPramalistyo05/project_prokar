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
        // Force HTTPS URL generation in production or when accessed via HTTPS proxy / SSL
        if ($this->app->environment('production') || request()->header('X-Forwarded-Proto') === 'https' || str_starts_with(config('app.url'), 'https://')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // Auto-create required storage directories if missing with write permissions
        $directories = [
            storage_path('app/private/livewire-tmp'),
            storage_path('app/livewire-tmp'),
            storage_path('app/public/livewire-tmp'),
            storage_path('app/public/settings'),
            storage_path('app/public/settings/hero'),
            storage_path('app/public/settings/hero3card'),
            storage_path('app/public/settings/service'),
            storage_path('app/public/products'),
            storage_path('app/public/services'),
            storage_path('app/public/service_images'),
            storage_path('app/public/sell-submissions'),
            storage_path('app/public/sell_submissions'),
            storage_path('app/public/avatars'),
            storage_path('app/firebase'),
            storage_path('app/private/firebase'),
        ];

        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            @chmod($dir, 0777);
        }

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
