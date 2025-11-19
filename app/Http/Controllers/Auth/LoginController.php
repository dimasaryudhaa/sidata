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

protected function authenticated(Request $request, $user)
{
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard')->with('success', 'Berhasil login sebagai Admin!');
    }

    if ($user->role === 'ptk') {
        return redirect()->route('ptk.dashboard')->with('success', 'Berhasil login sebagai PTK!');
    }

    if ($user->role === 'siswa') {
        return redirect()->route('siswa.dashboard')->with('success', 'Berhasil login sebagai Siswa!');
    }

    return redirect('/')->with('success', 'Berhasil login!');
}


    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function loggedOut(Request $request)
    {
        return redirect('/login');
    }
}
