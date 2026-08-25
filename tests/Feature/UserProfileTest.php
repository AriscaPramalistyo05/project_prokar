<?php

namespace Tests\Feature;

use App\Livewire\Frontend\UserProfile;
use App\Livewire\Frontend\UserSettings;
use App\Models\Order;
use App\Models\ServiceOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;
use Tests\TestCase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_or_settings_page(): void
    {
        $this->get(route('user.profile'))->assertRedirect(route('login'));
        $this->get(route('user.settings'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_and_settings_pages(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('user.profile'))
            ->assertOk();

        $this->actingAs($user)
            ->get(route('user.settings'))
            ->assertOk();
    }

    public function test_livewire_user_profile_can_update_profile_information(): void
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com',
            'phone' => '081234567890',
        ]);

        Livewire::actingAs($user)
            ->test(UserProfile::class)
            ->set('name', 'Updated Name')
            ->set('phone', '089876543210')
            ->call('saveProfile')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'phone' => '089876543210',
        ]);
    }

    public function test_livewire_user_settings_can_update_password(): void
    {
        $user = User::factory()->create([
            'password' => Hash::make('oldpassword123'),
        ]);

        Livewire::actingAs($user)
            ->test(UserSettings::class)
            ->set('current_password', 'oldpassword123')
            ->set('password', 'newpassword123')
            ->set('password_confirmation', 'newpassword123')
            ->call('updatePassword')
            ->assertHasNoErrors();

        $this->assertTrue(Hash::check('newpassword123', $user->fresh()->password));
    }

    public function test_user_can_view_order_and_service_history(): void
    {
        $user = User::factory()->create();

        $order = Order::factory()->create(['user_id' => $user->id]);
        $service = ServiceOrder::factory()->create(['user_id' => $user->id]);

        Livewire::actingAs($user)
            ->test(UserProfile::class)
            ->set('selectedTab', 'orders')
            ->assertSee($order->order_code)
            ->set('selectedTab', 'services')
            ->assertSee($service->service_code);
    }
}
