<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    //
    public function register(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|string|max:50',
            'middleName' => 'required|string|max:50',
            'lastName' => 'required|string|max:50',
            'dob' => 'required|date',
            'email' => 'required|string|email|max:50|unique:users',
            'phone' => 'required|string|min:10|max:10|unique:users',
            'password' => 'required|string|min:8|max:50|confirmed', 
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        $path = '';

        if($request->file('photo')) 
        {
            $file = $request->file('photo');
            $fileName = $file->getClientOriginalName();
            $path = $request->file('photo')->storeAs('public/images/users',$fileName);
        }
        // print_r('hey i am here to test');
        $convertedDate = Carbon::createFromFormat('D M d Y', $request->dob)->format('Y-m-d');


        // Create a new user instance
        $users = User::create([
            'first_name' => $request->firstName,
            'middle_name' => $request->middleName,
            'last_name' => $request->lastName,
            'dob' => $convertedDate,
            'user_image' => $path,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), 
        ]);
        

        if($users) {
            return response()->json([
                'msg' => 'User Inserted Successfully',
                'user' => $users
            ]);
        }
        else{
            return response()->json([
                'msg' => 'Something Went Wrong',
                'user' => $users
            ]);
        }
        
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',  
        ]);

        if($validator->fails()) {
            return response()->json($validator->errors());
        }

        if(! $token = auth()->attempt($validator->validated())) {
            return response()->json(['success' => false, 'msg' => 'User & Password is incorrect']);
        }

        return $this->responseWithToken($token);
    }

    protected function responseWithToken($token)
    {
        $id = Auth()->user()->id;
        $user = Auth()->user();

        $data = [
            'remember_token' => $token,
        ];

        DB::table('users')->where('id', $id)->update($data);

        return response()->json([
            'success' => true,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL()*60,
            'user_id' => $id,
            'type' => $user->user_type,
        ]);
    }

    public function logout(Request $request) 
    {
        try {
            // $id = Auth()->user()->id;

            // auth()->logout();

            $key = 'Authorization';
    
            $token = $request->header($key);
            $user = User::where('remember_token', $token)->first();
            
            $data = [
                'remember_token' => NULL,
            ];

            DB::table('users')->where('id', $user->id)->update($data);

            return response()->json(['success'=>true, 'msg'=>'User logged out!']);
        }catch(\Exception $e) {
            return response()->json(['success'=>false, 'msg'=>$e->getMessage()]);
        }
    }
}
