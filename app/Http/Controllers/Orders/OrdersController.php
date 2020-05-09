<?php

namespace App\Http\Controllers\Orders;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Exception;
use Ramsey\Uuid\Uuid;

use App\Http\Controllers\Orders\Helper as sys_api;

class OrdersController extends Controller
{
  var $module = 'Orders';
  var $table_module = 'orders';
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

      $data = $sys_api->list_data($input);

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

  public function stepOrders(Request $request)
  {
    $sys_api = new sys_api();

    $input = $request->all();

    try {
      $proccess_orders = $sys_api->proccess_orders($input);

      if ($proccess_orders) 
      {
        $result = [
          'status' => 200,
          'message' => 'Orders Successfully'
        ];
      }
      else
      {
        $result = [
          'status' => 400,
          'message' => 'Orders Not Successfully, because stock not found'
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

  public function detail(Request $request, $uuid='')
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();

    try {
      if (empty($uuid)) 
      {
        $result = [
          'status' => 400,
          'message' => 'Parameter Invalid'
        ];

        return response()->json($result, $result['status']);
      }

      $data = $sys_api->detailData($uuid);
      $result = [
        'status' => 200,
        'message' => 'Success',
        'data'    => $data
      ]; 

    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }

  public function ordersReturn(Request $request)
  {
    # Define Helper
    $sys_api      = new sys_api();

    $result = array();
    $input = $request->all();

    try {
      $uuid = isset($input['orders_detail_uuid']) ? $input['orders_detail_uuid'] : '';

      $check_return = $sys_api->getDetailOrdersByUuid($uuid);
      if (@count($check_return) > 0) 
      {
        if ($check_return['orders_detail_return'] == 1) 
        {
          $result = [
            'status' => 400,
            'message' => 'Order has been return!',
          ];

          return response()->json($result, $result['status']);
        }
      }

      $proccess = $sys_api->orders_return($input);

      $data = array();
      if ($proccess) 
      {
        $data = $sys_api->getDetailOrdersByUuid($uuid);
      }

      $result = [
        'status' => 200,
        'message' => 'Success',
        'data'    => $data
      ];
    } catch (Exception $e) {
      $result = [
        'status' => 500,
        'message' => 'Something went wrong!',
      ];
    }

    return response()->json($result, $result['status']);
  }
}
