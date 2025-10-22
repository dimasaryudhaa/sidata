<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request; // ← tambahkan ini

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function loggedOut(Request $request)
    {
        return redirect('/login');
    }
}
