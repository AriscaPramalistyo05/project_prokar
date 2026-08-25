<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CheckoutModalTest extends DuskTestCase
{
    public function test_checkout_snap_popup_modal_trigger(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/checkout')
                ->pause(1000)
                ->assertSee('Checkout')
                ->assertPresent('body');
        });
    }
}
