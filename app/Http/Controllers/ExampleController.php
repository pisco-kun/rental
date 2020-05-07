<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\User;

class ExampleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function login(Request $request)
    {
        $input = $request->all();

        $users = User::where('users_email', '=', $input['users_email'])
                    ->first();

        if (@count($users) > 0) 
        {
          $password = $input['users_password'];

          if( Hash::check($password, $users['users_password']) )
          {
            $success['token'] = $users->createToken('nApp')->accessToken;
            return response()->json(['success' => $success], 200);
          }
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'users_name' => 'required',
            'users_email' => 'required|email',
            'users_password' => 'required|email',
            'c_users_password' => 'required|same:password',
        ]);

        if ($validator->fails()) 
        {
            return response()->json([
                'error' => $validator->errors()
            ], 401);
        }

        $input = $request->all();
        unset($input['c_users_password']);
        $input['users_password'] = Hash::make($input['users_password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('nApp')->accessToken;
        $success['users_name'] = $user->users_name;

        return response()->json(['success' => $success], 200);
    }

    public function rental(Request $request)
    {
        echo '<pre>';
        print_r('ok');
        exit;
    }

    //
}
