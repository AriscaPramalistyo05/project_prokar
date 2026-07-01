<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        $route = $request->user()->hasRole(['super_admin', 'teknisi'])
            ? route('admin.dashboard', absolute: false)
            : route('home', absolute: false);

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended($route)
                    : view('auth.verify-email');
    }
}
