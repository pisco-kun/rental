<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Controllers\Admin\Models\Admin;
use App\Http\Controllers\Admin\Models\Cd;
use Exception;
use Ramsey\Uuid\Uuid;

class AdminController extends Controller
{
  var $module = 'Admin';
  var $table_module = 'admin';
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

    $model_class = 'App\Http\Controllers\Admin\Models\Admin';

    $data = Admin::where($this->table_module.'_email', '=', $input[$this->table_module.'_email'])
                ->first();

    if (@count($data) > 0) 
    {
      $password = $input[$this->table_module.'_password'];

      if( Hash::check($password, $data[$this->table_module.'_password']) )
      {
        $token = $data->createToken('admin')->accessToken;
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

}
