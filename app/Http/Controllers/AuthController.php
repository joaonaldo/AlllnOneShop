<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use  App\Models\User;

//import auth facades
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
     
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
            'adress' => 'required|string',
            'city' => 'required|string',
            'codepostal' => 'required|string',
            'password' => 'required',
        ]);

        try {

            $user = new User;
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            $user->adress = $request->input('adress');
            $user->city = $request->input('city');
            $user->codepostal = $request->input('codepostal');
            $plainPassword = $request->input('password');
            $user->password = app('hash')->make($plainPassword);

            $user->save();

   
            return response()->json(['user' => $user, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
    
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }


    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function logout(Request $request)
    {
        Auth::logout(); // Encerra a sessão do usuário

        return response()->json(['message' => 'Logout successful']);
    }

    public function showCheckout()
    {
        // Verificar se o usuário está autenticado
        if (Auth::check()) {
            // Obter as informações do usuário autenticado
            $user = Auth::user();

            // Passar as informações do usuário para a view de checkout
            return view('checkout', ['user' => $user]);
        }

        // Se o usuário não estiver autenticado, redirecionar para a página de login
        return redirect('/login');
    }
}