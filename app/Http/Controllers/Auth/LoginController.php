<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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


    public function redirectToProvider($driver){
        try {

            return Socialite::driver($driver)->redirect();

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {

            Log::channel('errorlog')->error($e->getMessage());
            return redirect()->route('login')->with('error','Try again after refreshing the page');

        }
    }

    public function handleProviderCallback($driver)
    {
        try {

            $user = Socialite::driver($driver)->user();
            $this->_registerOrLoginUser($user);
            return redirect()->intended('home');

        } catch (\Laravel\Socialite\Two\InvalidStateException $e) {

            Log::channel('errorlog')->error($e->getMessage());
            return redirect()->route('login')->with('error','Try again after refreshing the page');

        }

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
