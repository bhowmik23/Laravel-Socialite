<?php

namespace App\Http\Controllers\Auth;

Use App\Models\User;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $socialiteUser = Socialite::driver($provider)->user();
        // dd($socialiteUse);

        $user = User::firstOrCreate(
            [
                'provider' => $provider,
                'provider_id' => $socialiteUser->getId(),
            ],
            [
                'name' => $socialiteUse->getName(),
                'email' => $socialiteUse->getEmail(),
            ],
        );

        // $user = User::where('provider_id', $socialiteUse->getId())->first();

        // // Create a new user in our database
        // if(! $user){
        //     $user = User::create([
        //         'email' => $socialiteUse->getEmail(),
        //         'name' => $socialiteUse->getName(),
        //         'provider_id' => $socialiteUse->getId(),
        //     ]);
        // }
        // dd($user);

        // Login the user
        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('dashboard');
    }
}