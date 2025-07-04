<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class SignUpController extends Controller
{
    function showSignUpForm() 
    {
        return view('signUp');
    }

    function registerUser(Request $request) 
    {
        // Validate user input
        $request->validate([
            'firstName' => 'required|string|max:50',
            'middleName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:50|unique:users',
            'phone' => 'required|string|min:10|max:10|unique:users',
            'password' => 'required|string|min:8|max:50|confirmed', 
        ]);

        $path = '';

        if($request->file('photo')) 
        {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $request->file('photo')->storeAs('public/images/users',$fileName);
        }

        // Create a new user instance
        $users = User::create([
            'first_name' => $request->firstName,
            'middle_name' => $request->middleName,
            'last_name' => $request->lastName,
            'dob' => $request->dob,
            'user-image' => $path,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
        ]);

        if($users){
            return redirect('/');
        }
    }
}
