<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function register(Request $request)
    {
        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'email' => $attr['email'],
            'password' => Hash::make($attr['password']),
        ]);
        Auth::login($user);

        $token = $user->createToken($user->name)->plainTextToken;
        return response(['token' => $token, 'user' => $user]);
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|exists:users|email|',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $attr['email'])->first();

        if (!$user || !Hash::check($attr['password'], $user->password) || !Auth::attempt($attr)) {
            throw ValidationException::withMessages([
                'email' => ['Incorrect credentials.'],
            ]);
        }

        $user = auth('sanctum')->user();
        if ($user) {
            $token = $user->createToken($user->name)->plainTextToken;
//            return response(['token' => $token, 'user' => $user]);
            return response(['token' => $token, 'user' => $user]);
        }

//        return response()->json(['message' => 'success']);
        return response()->json(['error' => 'Something went wrong!'], 401);
    }


    public function logout(): \Illuminate\Http\JsonResponse
    {
        auth()->user()->tokens()->delete();
//        auth('sanctum')->logout();

        return response()->json(['message' => 'Success!']);
    }
}
