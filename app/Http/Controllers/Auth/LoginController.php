<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //google login
    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }
    //google callback
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }

    //github login
    public function redirectToGithub(){
        return Socialite::driver('github')->redirect();
    }
    //github callback
    public function handleGithubCallback()
    {
        $user = Socialite::driver('github')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }


    //facebook login
    public function redirectToFacebook(){
        return Socialite::driver('facebook')->redirect();
    }
    //facebook callback
    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $this->_registerOrLoginUser($user);
        return redirect()->route('home');
    }
    protected function _registerOrLoginUser($data){
        $user = User::where('email',$data->email)->first();
        if( $user ) {
            // update the avatar and provider that might have changed
            $user->update([
                'avatar' => $data->avatar,
                'provider_id' => $data->id,
                'access_token' => $data->token
            ]);
        } else {
            // create a new user
            $user = User::create([
                'name' => $data->getName(),
                'email' => $data->getEmail(),
                'avatar' => $data->getAvatar(),
                'provider_id' => $data->getId(),
                'access_token' => $data->token,
            ]);
        }
        Auth::login($user,true);
    }
}
