<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ptk;
use App\Models\AkunPtk;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'ptk') {
            $akunPtk = AkunPtk::where('email', $user->email)->first();
            $ptk = null;

            if ($akunPtk) {
                $ptk = Ptk::find($akunPtk->ptk_id);
            }

            return view('profile.index', compact('user', 'ptk'));
        }

        return view('profile.index', compact('user'));
    }
}
