<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if(! Auth::check()) {
            return view('login');
        }
        else {
            return redirect()->back();
        }
    }

    public function loginUser(Request $request){
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required', 
        ]);

        if(Auth::attempt($credentials))
        {
            $user_type=Auth()->user()->user_type;

            if($user_type=='customer')
            {
                return redirect()->route('products');
            }
            else if($user_type=='admin')
            {
                return redirect()->route('admin/products');
            }
        }
        return back()->withErrors([
            'email' => 'Invalid Email OR Password'
        ]);
    }

    public function logout()
    {
        if(Auth::check()) {
            Auth::logout();
            return redirect('login')->withErrors([
                'email' => 'Successfully logout'
            ]);
        }
        else {
            return redirect('login');
        }
    }
}
