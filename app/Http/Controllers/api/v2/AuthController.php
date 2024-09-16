<?php

namespace App\Http\Controllers\api\v2;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

// use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
        try {
            
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
                'device_name' => 'required',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            return $user->createToken($request->device_name)->plainTextToken;

        } 
        catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 200);
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->validator->errors()], 200);
        }
        
    }

    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            $user->tokens()->delete();

            return 'Tokens are deleted';

        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 200);
        }
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'device_name' => 'required|string', // Add device_name validation
            ]);

            // $request->validate([
            //     'name' => 'required|string|max:255',
            //     'email' => 'required|email|unique:users,email',
            //     'password' => 'required|string|min:6',
            //     'device_name' => 'required|string', // Add device_name validation
            // ]);

            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password')),
            ]);

            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);

            // Issue a token with Sanctum and attach the device_name
            // $token = $user->createToken($request->input('device_name'))->plainTextToken;

            // return response()->json(['message' => 'User registered successfully', 'user' => $user, 'token' => $token], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->errors()], 200);
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Auth $auth)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Auth $auth)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Auth $auth)
    {
        //
    }
}
