<?php

namespace App\Http\Controllers;

use App\Contracts\DatabaseServiceInterface;
use App\Models\User;
use App\Services\AuthenticatorService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    protected $databaseService;
    protected $authenticator;

    public function __construct(DatabaseServiceInterface $databaseService, AuthenticatorService $authenticator)
    {
        $this->databaseService = $databaseService;
        $this->authenticator = $authenticator;
    }

    public function redirectToProvider(Request $request)
    {

        //->stateless()
        $env = $request->query('env', '0');
        // dd($env);

        // Guardamos el env en la sesión (para poder accederlo en el callback)
        session(['env' => $env]);

        return Socialite::driver('microsoft')->redirect();
    }

    public function handleProviderCallback(Request $request)
    {
        try {
            $env = session('env', '0');
            //dd($env);
            $databaseService = app(DatabaseServiceInterface::class);
            // dd($databaseService);

            $user = Socialite::driver('microsoft')->stateless()->user();

            //$user = Socialite::driver('microsoft')->stateless()->user();

            $existingUser = $databaseService->findUserByEmail($user->getEmail());

            //dd($existingUser);
            // dd($databaseService);
            if (!is_null($existingUser)) {
                $newUser = new User((array) $existingUser);
                // dd($newUser);
            } else {
                $newUser = $this->databaseService->createUser([
                    'name' => $user->getName(),
                    'email' => $user->getEmail(),
                    'microsoft_id' => $user->getId(),
                ]);
                $newUser = new User((array) $existingUser);

            }

            // Autenticamos al usuario
            $this->authenticator->loginUser($newUser);
            // $redir =redirect()->to('http://localhost:8003/viewWelcome');
            //dd($redir);
            return redirect()->to('http://localhost:8003/viewWelcome');

            return response()->json(['redirect' => $redir->getTargetUrl()], 200);

        } catch (Exception $e) {

            return response()->json(['error' => 'No se pudo iniciar sesión. Inténtalo de nuevo.'], 500);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('http://localhost:8003');

    }

    public function storeUsers(Request $request)
    {
        try {
          
            $env = $request->header('env', '0');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',   
            'microsoft_id' => 'required|string|max:255',
        ]);
        
        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'microsoft_id' => $request->input('microsoft_id'),
        ];
        //dd($request);

        $databaseService = app(DatabaseServiceInterface::class);
        //dd($databaseService);
        $databaseService->storeUser($userData); 
        
        return response()->json([
            'data' => 'Usuario registrado correctamente',
            'message' => 'Success'
        ], 200);

        } catch (\Throwable $th) {

            \Log::error('User registration error: ' . $th->getMessage());

            //return response()->json($th->getMessage(), 500);
            return response()->json([
                'error' => 'No se pudo registrar el usuario. Intenta nuevamente.'
            ], 500);
        }
    }

    // public function redirectToProvider()
    // {
    //     //->stateless()
    //     return Socialite::driver('microsoft')->redirect();
    // }

    // public function handleProviderCallback()
    // {
    //     try {

    //         $user = Socialite::driver('microsoft')->stateless()->user();

    //         $existingUser = DB::connection('sqlsrv_prod')
    //             ->table('users')
    //             ->where('email', $user->getEmail())
    //             ->first();

    //         if ($existingUser) {

    //             $newUser = new User((array) $existingUser);

    //         } else {

    //             $newUser = DB::connection('sqlsrv_prod')->table('Usuario')
    //                 ->insert([
    //                     'name' => $user->getName(),
    //                     'email' => $user->getEmail(),
    //                     'microsoft_id' => $user->getid(),
    //                 ]);

    //             Auth::login($newUser);
    //         }

    //         //SE UTILIZA redirect()->to PORQUE ES UNA PAGINA FUE DEL PROYECTO API
    //         return redirect()->to('http://localhost:8003/viewWelcome');

    //     } catch (Exception $e) {
    //         return redirect()->to('http://localhost:8003')->withErrors(['error' => 'Error al autenticar con Microsoft']);
    //     }

    // }
    // public function logout(Request $request)
    // {
    //     Auth::logout();
    //     return redirect('http://localhost:8003');

    // }

    public function getUsers()
    {

    }
}
