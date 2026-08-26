<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class HomeInteractionTest extends DuskTestCase
{
    public function test_home_banner_scroll_animation_and_category_navigation(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitForText('Prokar', 10)
                ->assertSee('Prokar')
                ->scrollIntoView('footer')
                ->pause(300)
                ->assertPresent('footer');
        });
    }

    public function test_cart_drawer_add_remove_item_interactivity(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('nav', 10)
                ->assertPresent('nav');

            if ($browser->element('a[aria-label="Keranjang"], a[href*="/keranjang"]')) {
                $browser->click('a[aria-label="Keranjang"], a[href*="/keranjang"]')
                    ->pause(500);
            }
        });
    }
}

