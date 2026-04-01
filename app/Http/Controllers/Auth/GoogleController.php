<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Store;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        // $googleUser = Socialite::driver('google')->user();

        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->email)->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'email_verified_at' => now(),
                'password' => bcrypt('password123'),
            ]);

            $user->assignRole('admin');
        }

        if ($user->hasRole('admin')) {
            $store = Store::create([
                'name' => $googleUser->name."'s Store",
                'owner_id' => $user->id,
                'slug' => $this->generateUniqueSlug($$googleUser->name.'-store'),
            ]);
            $user->store_id = $store->id;
            $user->save();
        }

        Auth::login($user);

        return redirect('/dashboard');
    }

    private function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = Store::where('slug', 'LIKE', $slug.'%')->count();

        return $count ? "{$slug}-{$count}" : $slug;
    }
}
