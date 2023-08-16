<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Traits\JsonRespondController;

class RegisterController extends Controller
{
    use JsonRespondController;

    public function register(Request $request)
    {
        $validated = $this->validateRequest($request);
        // $post_data = $request->validate([
        //     'name' => ['required', 'string'],
        //     'email' => ['required', 'string', 'email', 'unique:users'],
        //     'password' => ['required', 'min:8'],
        // ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('authToken')->plainTextToken;

        // return response()->json([
        //     'access_token' => $token,
        //     'token_type' => 'Bearer',
        // ]);
        return $this->respond([
            'token' => $token,
            'user' => $user,
        ]);
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
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'unique:users'],
            'password' => ['required', 'min:8'],
        ]);

    }
}