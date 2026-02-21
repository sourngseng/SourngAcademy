<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Azate\LaravelTelegramLoginAuth\TelegramLoginAuth;

class SocialAuthController extends Controller
{
    /**
     * Redirect to the provider's authentication page.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirect($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            return redirect()->route('login')->with('error', 'Invalid social provider.');
        }

        return Socialite::driver($provider)->redirect();
    }

    /**
     * Handle the provider's callback.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback($provider)
    {
        try {
            if ($provider == 'telegram') {
                $data = $this->validateTelegramCallback();
                $providerId = $data->get('id');
                $email = $data->get('id') . '@telegram.user'; 
                $name = $data->get('first_name') . ' ' . $data->get('last_name');
            } elseif (in_array($provider, ['google', 'facebook'])) {
                $socialUser = Socialite::driver($provider)->user();
                $providerId = $socialUser->getId();
                $email = $socialUser->getEmail();
                $name = $socialUser->getName();
            } else {
                return redirect()->route('login')->with('status', 'Invalid social provider.');
            }

            // If user is already logged in, LINK the account
            if (Auth::check()) {
                $user = Auth::user();
                
                // Check if this social ID is already taken by another user
                $existingUser = User::where($provider . '_id', $providerId)->first();
                if ($existingUser && $existingUser->id !== $user->id) {
                    return redirect()->route('profile.edit')->with('error', 'This ' . ucfirst($provider) . ' account is already linked to another user.');
                }

                // Link the account
                $user->forceFill([
                    $provider . '_id' => $providerId
                ])->save();

                return redirect()->route('profile.edit')->with('status', 'social-account-linked');
            }

            // Login or Register flow
            if ($provider == 'telegram') {
                 // Re-using the logic from handleTelegramCallback which returns a User object
                 // But wait, my previous code for handleTelegramCallback returned a User object
                 // let's adjust to be consistent.
                 $user = $this->findOrCreateTelegramUser($data);
            } else {
                 $user = $this->findOrCreateUser($socialUser, $provider);
            }

            Auth::login($user);

            return redirect()->intended('/dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('status', 'Social login failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Telegram Widget Callback
     */
    /**
     * Validate Telegram Widget Callback
     */
    protected function validateTelegramCallback()
    {
        $telegramLoginAuth = new TelegramLoginAuth(config('services.telegram-login.bot_token'));
        $data = $telegramLoginAuth->validate(request()->all());

        if (!$data) {
             throw new \Exception('Telegram login validation failed.');
        }
        
        return $data;
    }

    protected function findOrCreateTelegramUser($data)
    {
        $telegramId = $data->get('id');
        $name = $data->get('first_name') . ' ' . $data->get('last_name');
        $email = $telegramId . '@telegram.user'; 

        $user = User::where('telegram_id', $telegramId)->first();

        if (!$user) {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make(Str::random(24)),
                'telegram_id' => $telegramId,
                'role' => 'student', // Default role
            ]);
        }
        
        return $user;
    }


    /**
     * Find or create a user based on social provider data.
     *
     * @param \Laravel\Socialite\Contracts\User $socialUser
     * @param string $provider
     * @return \App\Models\User
     */
    protected function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // Update existing user's provider ID if needed
            if ($provider == 'google') {
                $user->google_id = $socialUser->getId();
            } elseif ($provider == 'facebook') {
                $user->facebook_id = $socialUser->getId();
            }
            $user->save();
        } else {
            // Create new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                'role' => 'student', // Default role for social login
                'google_id' => $provider == 'google' ? $socialUser->getId() : null,
                'facebook_id' => $provider == 'facebook' ? $socialUser->getId() : null,
            ]);
        }

        return $user;
    }
    /**
     * Disconnect a social provider from the user.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disconnect($provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'telegram'])) {
            return back()->with('error', 'Invalid provider.');
        }

        $user = Auth::user();
        
        $user->forceFill([
            $provider . '_id' => null
        ])->save();

        return back()->with('status', 'social-account-disconnected');
    }
}
