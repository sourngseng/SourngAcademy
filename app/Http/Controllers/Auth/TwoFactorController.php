<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use PragmaRX\Google2FA\Google2FA;

class TwoFactorController extends Controller
{
    /**
     * Store the 2FA secret and recovery codes.
     */
    public function enable(Request $request)
    {
        $user = $request->user();
        if ($user->two_factor_secret) {
            return back()->with('status', 'Two-factor authentication is already enabled.');
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        // Store in session temporarily until confirmed
        $request->session()->put('two_factor_secret', $secret);

        $qrCode = QrCode::size(200)->generate($google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        ));

        return back()->with('qrCode', $qrCode);
    }

    /**
     * AJAX Endpoint to get QR Code for enabling 2FA
     */
    public function showQrCode(Request $request)
    {
        $user = $request->user();
        
        if ($user->two_factor_confirmed_at) {
             return response()->json(['status' => 'enabled']);
        }

        $google2fa = new Google2FA();
        $secret = $google2fa->generateSecretKey();
        
        $request->session()->put('two_factor_secret', $secret);

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $secret
        );
        
        $qrCode = QrCode::size(200)->generate($qrCodeUrl);

        return response()->json([
            'svg' => (string) $qrCode,
            'secret' => $secret,
            'url' => $qrCodeUrl
        ]);
    }

    /**
     * Confirm 2FA with OTP
     */
    public function confirm(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);

        $user = $request->user();
        $secret = $request->session()->get('two_factor_secret');

        if (! $secret) {
            return back()->withErrors(['code' => 'Two factor authentication has not been enabled.']);
        }

        $google2fa = new Google2FA();

        if ($google2fa->verifyKey($secret, $request->code)) {
            $user->forceFill([
                'two_factor_secret' => encrypt($secret),
                'two_factor_recovery_codes' => encrypt(json_encode(
                    \Illuminate\Support\Collection::times(8, function () {
                        return \Illuminate\Support\Str::random(10) . '-' . \Illuminate\Support\Str::random(10);
                    })->all()
                )),
                'two_factor_confirmed_at' => now(),
            ])->save();

            $request->session()->forget('two_factor_secret');

            return back()->with('status', 'two-factor-authentication-enabled');
        }

        return back()->withErrors(['code' => 'The provided two factor authentication code was invalid.']);
    }

    /**
     * Disable 2FA
     */
    public function disable(Request $request)
    {
        // Require password confirmation before disabling
        if (! Hash::check($request->password, $request->user()->password)) {
             throw ValidationException::withMessages([
                'password' => [__('The provided password does not match your current password.')],
            ]);
        }

        $request->user()->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        return back()->with('status', 'two-factor-authentication-disabled');
    }

    /**
     * Get Recovery Codes (Ensure password confirm first)
     */
    public function recoveryCodes(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
             return response()->json(['error' => 'Invalid password'], 403);
        }

        return response()->json([
            'codes' => json_decode(decrypt($request->user()->two_factor_recovery_codes))
        ]);
    }
    
    /**
     * Regenerate Recovery Codes
     */
    public function regenerateRecoveryCodes(Request $request)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
             throw ValidationException::withMessages([
                'password' => [__('The provided password does not match your current password.')],
            ]);
        }

        $request->user()->forceFill([
            'two_factor_recovery_codes' => encrypt(json_encode(
                \Illuminate\Support\Collection::times(8, function () {
                    return \Illuminate\Support\Str::random(10) . '-' . \Illuminate\Support\Str::random(10);
                })->all()
            )),
        ])->save();

        return back()->with('status', 'recovery-codes-regenerated');
    }
}
