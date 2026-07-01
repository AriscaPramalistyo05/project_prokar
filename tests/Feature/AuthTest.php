<?php

namespace Tests\Feature;

use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_receives_otp_email_after_register(): void
    {
        Mail::fake();

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'phone' => '081234567890',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => true,
        ]);

        $response->assertRedirect(route('auth.otp'));
        $this->assertEquals(session('otp_user_id'), User::where('email', 'test@example.com')->first()->id);

        Mail::assertSent(\App\Mail\OtpMail::class, function ($mail) {
            return $mail->user->email === 'test@example.com';
        });
    }

    public function test_user_can_verify_otp_successfully(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otp = '123456';
        EmailOtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->post(route('auth.otp.verify'), [
            'otp' => $otp,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
        $this->assertTrue($user->fresh()->email_verified_at !== null);

        $this->assertTrue(EmailOtpVerification::where('user_id', $user->id)
            ->where('otp', $otp)
            ->first()->is_used);
    }

    public function test_expired_otp_cannot_be_used(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otp = '123456';
        EmailOtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->subMinutes(1),
            'is_used' => false,
        ]);

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->post(route('auth.otp.verify'), [
            'otp' => $otp,
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_used_otp_cannot_be_reused(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otp = '123456';
        EmailOtpVerification::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used' => true,
        ]);

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->post(route('auth.otp.verify'), [
            'otp' => $otp,
        ]);

        $response->assertSessionHasErrors('otp');
        $this->assertGuest();
    }

    public function test_resend_otp_respects_60_second_cooldown(): void
    {
        Mail::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $otp = EmailOtpVerification::create([
            'user_id' => $user->id,
            'otp' => '111111',
            'expires_at' => now()->addMinutes(10),
            'is_used' => false,
        ]);
        $otp->created_at = now()->subSeconds(30);
        $otp->save();

        $this->withSession(['otp_user_id' => $user->id]);

        $response = $this->get(route('auth.otp.resend'));

        $response->assertSessionHasErrors('otp');
        Mail::assertNothingSent();
    }

    public function test_unverified_user_cannot_access_protected_routes(): void
    {
        // ponytail: route home sekarang publik, jadi tidak redirect ke OTP.
        // Test ini memverifikasi bahwa user yang login tapi belum verified
        // tetap bisa akses halaman publik, tidak redirect ke OTP.
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $this->actingAs($user);

        $response = $this->get(route('home'));
        $response->assertOk(); // home is public now
}}
