<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\JsonRespondController;

class LoginController extends Controller
{
    use JsonRespondController;

    public function adminlogin(Request $request)
    {
        $validated = $this->validateRequest($request);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->respondUnauthorized('Wrong password and email combination');
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        //check if user is admin
        if (!$user->hasRole('admin')) {
            return $this->respondUnauthorized('Wrong password and email combination');
        }

        $token = $user->createToken('authToken')->plainTextToken;

        return $this->respond([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function adminlogout()
    {
        Auth::user()->currentAccessToken()->delete();

        return response()->json(['message' => 'logged out']);
    }

    public function login(Request $request)
    {
        $validated = $this->validateRequest($request);

        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->respondUnauthorized('Wrong password and email combination');
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $token = $user->createToken('authToken')->plainTextToken;

        return $this->respond([
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return respond(['message' => 'logged out']);
    }

    /**
     * Validate the request
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|true
     */
    private function validateRequest(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

    }
}