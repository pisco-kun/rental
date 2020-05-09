<?php

namespace App\Http\Controllers\Users;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Exception;

use App\Http\Controllers\Users\Helper as sys_api;

class UsersController extends Controller
{
  var $module = 'Users';
  var $table_module = 'users';
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
    # Define Helper
    $sys_api      = new sys_api();

    $validator = Validator::make($request->all(), [
            $this->table_module.'_email' => 'required|email',
            $this->table_module.'_password' => 'required',
    ]);

    if ($validator->fails()) 
    {
        return response()->json([
            'error' => $validator->errors()
        ], 500);
    }

    $result = array();
    $input = $request->all();

    $check_users = $sys_api->check_login($input);

    if (@count($check_users) > 0) 
    {
      $password = isset($input[$this->table_module.'_password']) ? $input[$this->table_module.'_password'] : '';

      if( Hash::check($password, $check_users[$this->table_module.'_password']) )
      {
        $token = $check_users->createToken($this->table_module)->accessToken;
        $result = [
          'status' => 200,
          'message'   => 'Success',
          'token'   => $token
        ];
      }
      else
      {
        $result = [
          'status'  => 500,
          'message'    => 'Wrong Password',
        ];
      }
    }
    else
    {
      $result = [
        'status'  => 500,
        'message'    => 'Email Not Found',
      ];
    }

    return response()->json($result, $result['status']);
  }

  public function logout(Request $request)
  {  
    $result = auth('admin')->user()->token()->revoke();                  
    if($result)
    {
      $response = [
        'status' => 200,
        'message'    => 'logout successfully'
      ];
    }
    else
    {
      $response = [
        'satatus' => 500,
        'message'    => 'Something is wrong'
      ];        
    }

    return response()->json($response, $response['status']);
  }

  public function register(Request $request)
  {
    # Define Helper
    $sys_api = new sys_api();
    $validator = Validator::make($request->all(), [
        $this->table_module.'_name' => 'required',
        $this->table_module.'_email' => 'required|email',
        $this->table_module.'_password' => 'required|min:4',
        $this->table_module.'_password_confirm' => 'required|min:4|same:'.$this->table_module.'_password',
    ]);

    if ($validator->fails()) 
    {
      return response()->json([
          'error' => $validator->errors()
      ], 500);
    }

    $input = $request->all();

    $check_users = $sys_api->check_login($input);

    if (@count($check_users) > 0) 
    {
      return response()->json([
          'error' => 'Email already exist'
      ], 500);
    }

    $input['users_password'] = Hash::make($input['users_password']);

    $proccess_save = $sys_api->save_data($input);
    
    if (@count($proccess_save) > 0) 
    {
      $result['status'] = 200;
      $result['message'] = 'Register Successfully';
      $result['token'] = $proccess_save->createToken('users')->accessToken;
      
      return response()->json($result, $result['status']);
    }

    return response()->json([
          'error' => 'Something went wrong'
      ], 500);

  }

}
