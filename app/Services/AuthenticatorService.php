<?php
// AuthenticatorService.php
namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthenticatorService
{
    public function loginUser(User $user)
    {
        Auth::login($user);
    }
}
