<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:' . User::class],
            'name' => ['required', 'string', 'max:255'],
            'angkatan' => ['required', 'digits:4'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                'regex:/^[\w\.-]+@polimedia\.ac\.id$/i'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'email.regex' => 'Hanya dapat registrasi menggunakan email @polimedia.ac.id',
        ]);

        $user = User::create([
            'username' => strtolower($request->username),
            'angkatan' => $request->angkatan,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('posts.index', absolute: false))
            ->with('success', 'Akun berhasil dibuat. Selamat datang, ' . $user->name . '!');
    }
}
