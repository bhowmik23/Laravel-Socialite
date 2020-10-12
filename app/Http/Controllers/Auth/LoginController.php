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
    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback()
    {
        $githubUser = Socialite::driver('github')->user();
        // dd($gitHubUser);

        $user = User::firstOrCreate(
            [
                'provider_id' => $githubUser->getId(),
            ],
            [
                'name' => $githubUser->getName(),
            ],
            [
                'email' => $githubUser->getEmail(),
            ]
        );

        // $user = User::where('provider_id', $githubUser->getId())->first();

        // // Create a new user in our database
        // if(! $user){
        //     $user = User::create([
        //         'email' => $githubUser->getEmail(),
        //         'name' => $githubUser->getName(),
        //         'provider_id' => $githubUser->getId(),
        //     ]);
        // }
        // dd($user);

        // Login the user
        auth()->login($user, true);

        // Redirect to dashboard
        return redirect('dashboard');
    }
}