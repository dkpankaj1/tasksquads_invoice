<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Support\Toastr;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function create()
    {
        return view('admin.auth.login');
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        request()->session()->regenerate();
        Toastr::success('Login Success', 'Success');

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Toastr::info('LogOut Success');

        return redirect()->route('login');
    }

    public function destroySession($session_id)
    {
        try {
            $currentSessionId = Session::getId();
            $session = DB::table('sessions')
                ->where('id', $session_id)
                ->where('user_id', Auth::id())
                ->where('id', '!=', $currentSessionId)
                ->first();

            if ($session) {
                DB::table('sessions')->where('id', $session->id)->delete();
                $user = Auth::user();
                $newRememberToken = Str::random(60);
                $user->forceFill(['remember_token' => $newRememberToken])->save();
                Toastr::success('Logout Success');
            } else {
                Toastr::success(__('messages.error.default'));
            }

            return redirect()->back();
        } catch (Exception $e) {
            Toastr::error($e->getMessage());

            return redirect()->back();
        }
    }
}
