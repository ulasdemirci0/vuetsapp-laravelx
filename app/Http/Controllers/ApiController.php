<?php

namespace App\Http\Controllers;

use App\Models\User;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiController extends Controller
{
    public function login(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $user->setRememberToken(Str::random(60));
            $user->save();
            return response([
                "token" => $user->getRememberToken(),
                "uid" => $user->getAuthIdentifier()
            ]);

        }

    }


    public function register(Request $request)
    {
        $user = User::firstOrCreate(
            [
                'email' => $request->email,
                'name' => $request->username,
            ],
            [
                'password' => Hash::make($request->password)
            ]);
        return response($user);
    }
}
