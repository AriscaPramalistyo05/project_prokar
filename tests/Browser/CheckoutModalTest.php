<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutModalTest extends DuskTestCase
{
    public function test_checkout_snap_popup_modal_trigger(): void
    {
        $user = User::where('email', 'customer_checkout_dusk@prokar.id')->first();
        if (!$user) {
            $user = User::factory()->create([
                'email' => 'customer_checkout_dusk@prokar.id',
                'name' => 'Dusk Customer',
                'email_verified_at' => now(),
            ]);
        }

        $this->browse(function (Browser $browser) use ($user) {
            $browser->loginAs($user)
                ->visit('/checkout')
                ->waitFor('#checkoutForm', 15)
                ->assertPresent('#checkoutForm')
                ->assertSee('PENGIRIMAN');
        });
    }
}


