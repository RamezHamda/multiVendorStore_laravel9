<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function index($provider){
        $user = auth()->user();

        $user_provider = Socialite::driver($provider)->userFromToken($user->provider_token);
        dd($user_provider);
    }
}
