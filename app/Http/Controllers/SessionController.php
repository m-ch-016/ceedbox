<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    public function create() {
        return view('auth.login');
    }

    public function store() {

        $attributes = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required']

        ]);

        if (! Auth::attempt($attributes)) {
            throw ValidationException::withMessages([
                'email' => 'The credentials provided were incorrect. Please try again.'
            ]);
        }

        request()->session()->regenerate();

        return redirect('/jobs');
    }

    public function destroy() {
        Auth::logout();

        return redirect('/');
    }
}
