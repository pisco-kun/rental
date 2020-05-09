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

    // try {     

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

    // } catch (Exception $e) {
    //   $result = [
    //       'status'  => 500,
    //       'message'    => 'Something went wrong',
    //     ];
    // }

    return response()->json($result, $result['status']);
  }

  public function logout(Request $request)
  {  
    $result = auth('admin')->user()->token()->revoke();                  
    if($result)
    {
      $result = [
        'status' => 200,
        'message'    => 'logout successfully'
      ];
    }
    else
    {
      $result = [
        'satatus' => 500,
        'message'    => 'Something went wrong'
      ];        
    }

    return response()->json($result, $result['status']);
  }

  public function get_all(Request $request)
  {
    $result = array();
    $input = $request->all();
    
    try {
      $admin = auth('admin')->user();
      $admin = json_decode(json_encode($admin), true);

      $users = auth('users')->user();
      $users = json_decode(json_encode($users), true);

      if (empty($admin) AND empty($users)) 
      {
        return response('Unauthorized.', 401);
      }

      $data = Admin::get()->toArray();

      $result = [
        'status' => 200,
        'message' => 'Success',
        'data' => $data 
      ];
    } catch (Exception $e) {
      $result = [
        'satatus' => 500,
        'message'    => 'Something went wrong'
      ];        
    }

    return response()->json($result, $result['status']);
  }

}
