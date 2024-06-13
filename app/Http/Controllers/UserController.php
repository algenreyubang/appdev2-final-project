<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(int $id) // Correct the parameter type to int
    {
        $user = User::findOrFail($id); // Use findOrFail to get user or throw 404
        return response()->json($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ]);
    
        // Update the user's details
        $user->update($validatedData);
    
        // Return the updated user with a success status
        return response()->json([
            'user' => $user,
            'message' => 'User updated successfully'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       
    
        $user->delete();
    
        return response()->json([
            'message' => 'User deleted successfully',
           
            'status' => true
        ], 200);
    }
}
