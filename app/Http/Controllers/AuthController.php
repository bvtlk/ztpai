<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'signupEmail' => 'required|string|unique:users,username',
            'signupPassword' => 'required|string|min:8|confirmed',
        ]);

        // Utworzenie użytkownika
        $user = User::create([
            'username' => $validated['signupEmail'],
            'password' => Hash::make($validated['signupPassword']),
            'role_id' => $request->role_id
        ]);

        return response()->json(['success' => true, 'user' => $user], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        $user = User::where('username', $credentials['username'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            $this->setUserSession($user);
            $this->setUserCookies($user);

            return redirect()->back();
        }

        return redirect()->back()->withErrors(['error' => 'Invalid username or password.']);
    }

    private function setUserSession(User $user): void
    {
        session([
            'user_id' => $user->id,
            'username' => $user->username,
            'role_id' => $user->role_id,
        ]);
    }

    private function setUserCookies(User $user): void
    {
        $expiration = 60 * 24; // 1 dzień (w minutach)

        // Ustaw ciasteczka bez flagi HttpOnly
        cookie()->queue(cookie('user_id', $user->id, $expiration, '/', null, false, false, false));
        cookie()->queue(cookie('username', $user->username, $expiration, '/', null, false, false, false));
        cookie()->queue(cookie('role_id', $user->role_id, $expiration, '/', null, false, false, false));
    }

    public function logout(Request $request): RedirectResponse
    {
        Session::flush();

        $this->clearUserCookies();

        return redirect()->back();
    }

    private function clearUserCookies(): void
    {
        cookie()->queue(cookie()->forget('user_id'));
        cookie()->queue(cookie()->forget('username'));
        cookie()->queue(cookie()->forget('role_id'));
    }
}
