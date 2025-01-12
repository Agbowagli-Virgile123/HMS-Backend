<?php

namespace App\Http\Controllers\Users;
use App\Models\Users\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;


class UserController extends Controller
{
    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'employeeId' => 'required|string|unique:users,employeeId', // Ensure employeeId is unique
            'role_id' => 'required|exists:roles,id', // Role must exist in the roles table
            'department_id' => 'nullable|exists:departments,id', // Department must exist in the departments table, or can be null
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email', // Ensure email is unique
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|', // Password must match the confirmation
            'status' => 'required|in:inactive,active,terminated', // Valid statuses
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Create user
        $user = User::create([
            'employeeId' => $request->employeeId,
            'role_id' => $request->role_id,
            'department_id' => $request->department_id,
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'status' => $request->status,
        ]);

          // Generate a Sanctum token
        $token = $user->createToken($request->employeeId.'Auth-Token')->plainTextToken;

        // Return the token and user details in the response
        return response()->json([
            'massage'=> "Registration Sucessfully",
            'token_type'=> 'Bearer',
            'token' => $token,
            // 'user' => $user,
        ], 201);


    }

    public function login(Request $request)
{
    // Validate the input
    $request->validate([
        'employeeId' => 'required|string',  // Ensure employeeId is a string
        'password' => 'required|string',    // Ensure password is a string
    ]);

    // Find the user by employeeId
    $user = User::where('employeeId', $request->employeeId)->first();

    // Check if the user exists and the password matches
    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // Generate a Sanctum token
    $token = $user->createToken($request->employeeId.'Auth-Token')->plainTextToken;

    // Return the token and user details in the response
    return response()->json([
        'message' => "Logged in Successfully",
        'token_type'=> 'Bearer',
        'token' => $token,
        'employeeId' => $user->employeeId,
        'firstName' => $user->firstName,
        'lastName' => $user->lastName,
        'department' => $user->department ? $user->department->departmentName : null,  // Safe access to department
        'role' => $user->role ? $user->role->roleName : null,  // Safe access to role
        'profileImg' => $user->userInfo ? $user->userInfo->profileImg : null,  // Safe access to profileImg in user_info
    ], 200);
}
public function logout(Request $request)
{
    // Revoke the token that was used to authenticate the current request
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Logged out successfully'], 200);
}

}
