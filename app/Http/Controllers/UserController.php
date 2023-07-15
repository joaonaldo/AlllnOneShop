<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use  App\Models\User;

class UserController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get the authenticated User.
     *
     * @return Response
     */
    public function profile()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    /**
     * Get all User.
     *
     * @return Response
     */
    public function allUsers()
    {
         return response()->json(['users' =>  User::all()], 200);
    }

    public function getUsernameById($id)
    {
        $user = User::find($id); // Busca o utilizador pelo ID
    
        if ($user) {
            $username = $user->name; // Obtém o nome do utilizador
            return response()->json(['username' => $username], 200);
        } else {
            return response()->json(['error' => 'utilizador não encontrado'], 404);
        }
    }
    
    

    /**
     * Get one user.
     *
     * @return Response
     */
    public function singleUser($id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json(['user' => $user], 200);

        } catch (\Exception $e) {

            return response()->json(['message' => 'user not found!'], 404);
        }

    }

    public function getAuthenticatedUserName()
    {
        $user = Auth::user(); 
    
        if ($user) {
            $userData = [
                'user_id' => $user->id,
                'user_name' => $user->name
            ];
            return response()->json($userData, 200);
        } else {
            return response()->json(['error' => 'utilizador não autenticado'], 401);
        }
    }

}

