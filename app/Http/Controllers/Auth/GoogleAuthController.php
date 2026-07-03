<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        $clientId = config('social.google.client_id');
        $redirectUrl = config('social.google.redirect_url');

        $url = 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUrl,
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'select_account',
        ]);

        return redirect($url);
    }

    public function callback()
    {
        try {
            $code = request('code');
            $error = request('error');

            if ($error) {
                Log::warning('Google auth error', ['error' => $error]);
                return redirect()->route('login')->with('error', 'Google লগইন ব্যর্থ হয়েছে।');
            }

            if (!$code) {
                return redirect()->route('login')->with('error', 'Google থেকে অনুমোদন কোড পাওয়া যায়নি।');
            }

            $tokenResponse = Http::post('https://oauth2.googleapis.com/token', [
                'client_id' => config('social.google.client_id'),
                'client_secret' => config('social.google.client_secret'),
                'redirect_uri' => config('social.google.redirect_url'),
                'grant_type' => 'authorization_code',
                'code' => $code,
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Google token error', ['response' => $tokenResponse->json()]);
                return redirect()->route('login')->with('error', 'Google অ্যাক্সেস টোকেন পাওয়া যায়নি।');
            }

            $accessToken = $tokenResponse->json('access_token');

            $userResponse = Http::withToken($accessToken)
                ->get('https://www.googleapis.com/oauth2/v2/userinfo');

            if (!$userResponse->successful()) {
                Log::error('Google user info error', ['response' => $userResponse->json()]);
                return redirect()->route('login')->with('error', 'Google ব্যবহারকারী তথ্য পাওয়া যায়নি।');
            }

            $googleUser = $userResponse->json();
            $googleId = $googleUser['id'];
            $name = $googleUser['name'] ?? 'Unknown';
            $email = $googleUser['email'] ?? null;
            $picture = $googleUser['picture'] ?? null;

            $user = User::where('google_user_id', $googleId)->first();

            if (!$user && $email) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $user->update(['google_user_id' => $googleId]);
                }
            }

            if (!$user) {
                $user = User::create([
                    'name' => $name,
                    'email' => $email ?? 'google_' . $googleId . '@sajebbd.news',
                    'google_user_id' => $googleId,
                    'is_active' => true,
                    'password' => bcrypt(bin2hex(random_bytes(16))),
                ]);

                if ($picture) {
                    try {
                        $imageContent = file_get_contents($picture);
                        $filename = 'google_' . $googleId . '_' . time() . '.jpg';
                        $dir = storage_path('app/public/profiles');
                        if (!is_dir($dir)) {
                            mkdir($dir, 0755, true);
                        }
                        file_put_contents($dir . '/' . $filename, $imageContent);
                        $user->update(['avatar' => 'profiles/' . $filename]);
                    } catch (\Exception $e) {
                        Log::error('Google picture download error', ['error' => $e->getMessage()]);
                    }
                }
            }

            Auth::login($user, true);

            return redirect()->intended(route('home'))->with('success', 'Google এর মাধ্যমে সফলভাবে লগইন করা হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Google auth exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('login')->with('error', 'লগইন প্রক্রিয়ায় একটি ত্রুটি হয়েছে। পরে আবার চেষ্টা করুন।');
        }
    }
}
