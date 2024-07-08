<?php

namespace App\Http\Controllers;

use Illuminate\Validation\Rules\Password;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create() {
        return view('auth.register');
    }

    public function store() {
        $attrs = request()->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password'=> ['required', 'confirmed', Password::min(6)]
            ,
        ]);

        $user = User::create($attrs);

        Auth::login($user);

        return redirect('/jobs');
    }


}
