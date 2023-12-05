<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\UserProvider;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Events\Frontend\UserRegistered;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo()
    {
        $redirectTo = request()->redirectTo;

        if ($redirectTo) {
            return $redirectTo;
        } else {
            return RouteServiceProvider::HOME;
        }
    }

    /**
     * Redirect the user to the Provider (Facebook, Google, GitHub...) authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        if(env('APP_ENV') == 'testing') return view('auth.mockOAuth');
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from Provider (Facebook, Google, GitHub...).
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback(Request $request, $provider)
    {
        $user = [];
        if (env('APP_ENV') == 'testing'){
            $email = $request->query('email');
            $user = (object)[
                'id' => $email,
                'name' => 'mock OAuth User',
                'email' => $email
            ];
        }
        else $user = Socialite::driver($provider)->user();

        $response = Http::post(env('API_URL').'social-login/'.$provider, [
            'id' => $user->id, 
            'name' => $user->name, 
            "email" => $user->email
        ]);

        if($response->failed()) {
            $errors = $response->json()["errors"];
            return back()->withErrors($errors ?? null)->onlyInput('email');
        }else {
            $data = $response->json()["data"];
            session(['api_token' => $data['token'], 'user' => $data['user']]);     
            return redirect()->route('home');
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Return user if exists; create and return if doesn't.
     *
     * @param $githubUser
     * @return User
     */
    private function findOrCreateUser($socialUser, $provider)
    {
        if ($authUser = UserProvider::where('provider_id', $socialUser->getId())->first()) {
            $authUser = User::findOrFail($authUser->user->id);

            return $authUser;
        } elseif ($authUser = User::where('email', $socialUser->getEmail())->first()) {
            UserProvider::create([
                'user_id'     => $authUser->id,
                'provider_id' => $socialUser->getId(),
                'avatar'      => 'img/default-avatar.jpg',
                'provider'    => $provider,
            ]);

            return $authUser;
        } else {
            $name = $socialUser->getName();

            $name_parts = $this->split_name($name);
            $first_name = $name_parts[0];
            $last_name = $name_parts[1];
            $email = $socialUser->getEmail();

            if ($email == '') {
                Log::error('Social Login does not have email!');

                flash('Email address is required!')->error()->important();

                return redirect()->intended(RouteServiceProvider::HOME);
            }

            $user = User::create([
                'first_name'  => $first_name,
                'last_name'   => $last_name,
                'name'        => $name,
                'email'       => $email,
            ]);

            $media = $user->addMediaFromUrl($socialUser->getAvatar())->toMediaCollection('users');
            // $user->avatar = $media->getUrl();
            $user->save();

            event(new UserRegistered($user));

            UserProvider::create([
                'user_id'     => $user->id,
                'provider_id' => $socialUser->getId(),
                'avatar'      => 'img/default-avatar.jpg',
                'provider'    => $provider,
            ]);

            return $user;
        }
    }

    /**
     * Split Name into first name and last name.
     */
    public function split_name($name)
    {
        $name = trim($name);

        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim(preg_replace('#'.$last_name.'#', '', $name));

        return [$first_name, $last_name];
    }
}
