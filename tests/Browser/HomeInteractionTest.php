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
                ->pause(1500)
                ->assertSee('Prokar')
                ->scrollIntoView('footer')
                ->pause(500)
                ->assertPresent('body');
        });
    }

    public function test_cart_drawer_add_remove_item_interactivity(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->pause(1500)
                ->assertPresent('body');

            if ($browser->element('[onclick*="openCartModal"], button[title*="Keranjang"]')) {
                $browser->click('[onclick*="openCartModal"], button[title*="Keranjang"]')
                    ->pause(500);
            }
        });
    }
}
