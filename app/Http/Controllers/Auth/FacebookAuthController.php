<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookAuthController extends Controller
{
    /**
     * Redirect to Facebook login
     */
    public function redirect()
    {
        $facebookAppId = config('social.facebook.app_id');
        $redirectUrl = config('social.facebook.redirect_url');
        $permissions = implode(',', config('social.facebook.permissions', ['email', 'public_profile']));

        $url = "https://www.facebook.com/v18.0/dialog/oauth?" . http_build_query([
            'client_id' => $facebookAppId,
            'redirect_uri' => $redirectUrl,
            'scope' => $permissions,
            'response_type' => 'code',
            'auth_type' => 'rerequest',
        ]);

        return redirect($url);
    }

    /**
     * Handle Facebook callback
     */
    public function callback()
    {
        try {
            $code = request('code');
            $state = request('state');
            $error = request('error');
            $errorReason = request('error_reason');

            // Check for error
            if ($error) {
                Log::warning('Facebook auth error', [
                    'error' => $error,
                    'error_reason' => $errorReason,
                ]);
                return redirect()->route('login')->with('error', 'Facebook লগইন ব্যর্থ হয়েছে।');
            }

            if (!$code) {
                Log::warning('Facebook auth: No code received');
                return redirect()->route('login')->with('error', 'Facebook থেকে অনুমোদন কোড পাওয়া যায়নি।');
            }

            // Get access token
            $facebookAppId = config('social.facebook.app_id');
            $facebookAppSecret = config('social.facebook.app_secret');
            $redirectUrl = config('social.facebook.redirect_url');

            $tokenResponse = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
                'client_id' => $facebookAppId,
                'client_secret' => $facebookAppSecret,
                'redirect_uri' => $redirectUrl,
                'code' => $code,
            ]);

            if (!$tokenResponse->successful()) {
                Log::error('Facebook token error', [
                    'response' => $tokenResponse->json(),
                ]);
                return redirect()->route('login')->with('error', 'Facebook এ অ্যাক্সেস টোকেন পাওয়া যায়নি।');
            }

            $accessToken = $tokenResponse->json('access_token');

            // Get user info from Facebook
            $userResponse = Http::get('https://graph.facebook.com/me', [
                'fields' => 'id,name,email,picture.width(100).height(100)',
                'access_token' => $accessToken,
            ]);

            if (!$userResponse->successful()) {
                Log::error('Facebook user info error', [
                    'response' => $userResponse->json(),
                ]);
                return redirect()->route('login')->with('error', 'Facebook ব্যবহারকারী তথ্য পাওয়া যায়নি।');
            }

            $facebookUser = $userResponse->json();
            $facebookId = $facebookUser['id'];
            $name = $facebookUser['name'] ?? 'Unknown';
            $email = $facebookUser['email'] ?? null;
            $picture = $facebookUser['picture']['data']['url'] ?? null;

            // Find or create user
            $user = User::where('facebook_user_id', $facebookId)->first();

            if (!$user) {
                // Create new user
                $user = new User();
                $user->name = $name;
                $user->email = $email ?? 'facebook_' . $facebookId . '@sajebbd.news';
                $user->facebook_user_id = $facebookId;
                $user->is_active = true;
                $user->password = bcrypt(bin2hex(random_bytes(16))); // Random password
                
                // Download and save profile picture
                if ($picture) {
                    try {
                        $picturePath = $this->downloadProfilePicture($picture, $facebookId);
                        $user->avatar = $picturePath;
                    } catch (\Exception $e) {
                        Log::error('Facebook picture download error', [
                            'error' => $e->getMessage(),
                            'facebook_id' => $facebookId,
                        ]);
                    }
                }

                $user->save();

                Log::info('New user created via Facebook', [
                    'user_id' => $user->id,
                    'facebook_id' => $facebookId,
                ]);
            } else {
                // Update existing user
                $user->update([
                    'name' => $name,
                    'is_active' => true,
                ]);

                Log::info('User logged in via Facebook', [
                    'user_id' => $user->id,
                    'facebook_id' => $facebookId,
                ]);
            }

            // Login user
            Auth::login($user, true);

            return redirect()->intended(route('home'))->with('success', 'Facebook এর মাধ্যমে সফলভাবে লগইন করা হয়েছে।');

        } catch (\Exception $e) {
            Log::error('Facebook auth exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect()->route('login')->with('error', 'লগইন প্রক্রিয়ায় একটি ত্রুটি হয়েছে। পরে আবার চেষ্টা করুন।');
        }
    }

    /**
     * Download and save Facebook profile picture
     */
    private function downloadProfilePicture($pictureUrl, $facebookId)
    {
        try {
            $imageContent = file_get_contents($pictureUrl);
            $filename = 'facebook_' . $facebookId . '_' . time() . '.jpg';
            $path = 'profiles/' . $filename;

            if (!is_dir(storage_path('app/public/profiles'))) {
                mkdir(storage_path('app/public/profiles'), 0755, true);
            }

            file_put_contents(storage_path('app/public/' . $path), $imageContent);

            return $path;
        } catch (\Exception $e) {
            Log::error('Failed to download Facebook profile picture', [
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'লগআউট করা হয়েছে।');
    }
}
