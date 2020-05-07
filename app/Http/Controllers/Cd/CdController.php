<?php

namespace App\Http\Controllers\Cd;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Exception;
use Ramsey\Uuid\Uuid;

use App\Http\Controllers\Cd\Helper as sys_api;

class CdController extends Controller
{
  var $module = 'Cd';
  var $table_module = 'cd';
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
      //
  }

  public function index(Request $request)
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();
    $auth_type = 'users';

    try {
      $admin = auth('admin')->user();
      $admin = json_decode(json_encode($admin), true);

      if (!empty($admin)) 
      {
        $auth_type = 'admin';
      }

      $data = $sys_api->list_data($input, $auth_type);

      $result = [
        'status' => 200,
        'message' => 'Success',
        'result' => $data
      ];
    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }

  public function saveData(Request $request)
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();

    $admin = auth('admin')->user();
    $admin = json_decode(json_encode($admin), true);

    try {
      $process_save = $sys_api->save($input, $admin['admin_serial_id']);
      if ($process_save) 
      {
        $result = [
          'status' => 200,
          'message' => 'Save Data Success',
        ];
      }
      else
      {
        $result = [
          'status' => 500,
          'message' => 'Save Data Failed',
        ];
      }

      return response()->json($result, $result['status']);
    } catch (Exception $e) {
      $result = [
          'status' => 500,
          'message' => 'Parameter Invalid',
        ];
      return response()->json($result, $result['status']);     
    }
  }

  public function editData(request $request, $uuid='')
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();

    try {
      $data = $sys_api->edit_data($uuid);

      if ($data['deleted'] == '1') 
      {
        $result = [
          'status' => 400,
          'message' => 'data has been deleted'
        ];
      }
      else
      {
        $result = [
          'status' => 200,
          'message' => 'Success',
          'result' => $data
        ];        
      }

    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }

  public function updateData(Request $request, $uuid='')
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();
    $admin = auth('admin')->user();
    $admin = json_decode(json_encode($admin), true);

    try {
      if (empty($uuid)) 
      {
        $result = [
          'status' => 400,
          'message' => 'Parameter Invalid'
        ];

        return response()->json($result, $result['status']);
      }

      $check_data = $sys_api->edit_data($uuid);

      if ($check_data['deleted'] == '1') 
      {
        $result = [
          'status' => 400,
          'message' => 'data has been deleted'
        ];
      }
      else
      {
        $process_update = $sys_api->update($input, $uuid, $admin['admin_serial_id']);
        $result = [
          'status' => 200,
          'message' => 'Update successfully',
        ];        
      }

    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }

  public function deleteData(Request $request, $uuid='')
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();
    $admin = auth('admin')->user();
    $admin = json_decode(json_encode($admin), true);

    try {
      if (empty($uuid)) 
      {
        $result = [
          'status' => 400,
          'message' => 'Parameter Invalid'
        ];

        return response()->json($result, $result['status']);
      }

      $check_data = $sys_api->edit_data($uuid);

      if ($check_data['deleted'] == '1') 
      {
        $result = [
          'status' => 400,
          'message' => 'data has been deleted'
        ];
      }
      else
      {
        $process_update = $sys_api->delete($uuid, $admin['admin_serial_id']);
        $result = [
          'status' => 200,
          'message' => 'Delete successfully',
        ];        
      }

    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }
}
