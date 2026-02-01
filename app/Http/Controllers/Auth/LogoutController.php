<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController
{
    public function __invoke(Request $request)
    {
        $user = $request->user();

        // Clear the last_activity_at timestamp
        if ($user) {
            $user->updateQuietly(['last_activity_at' => null]);
        }

        // Log out the user
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
