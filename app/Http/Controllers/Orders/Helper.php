<?php

namespace App\Http\Controllers\Orders;

use DB;
use DateTime;

class Helper 
{
	var $uuid = 'Ramsey\Uuid\Uuid';
	var $uuid_parent = 'Ramsey\Uuid\Exception\UnsatisfiedDependencyException';
	var $model_class = 'App\Http\Controllers\Orders\Models\Orders';
	var $model_class_orders_detail = 'App\Http\Controllers\Orders\Models\Orders_detail';
	var $model_class_users = 'App\Http\Controllers\Users\Models\Users';
	var $model_class_cd = 'App\Http\Controllers\Cd\Models\Cd';

	var $module = 'Orders';
	var $table_module = 'orders';

	function example_uuid()
	{
		$uuid4 	= $this->uuid::uuid4()->toString();

		return $uuid4;
	}

	public function list_data($input=array())
	{
		$data = array();

		# PAGINATION MANUALLY
		$page = 1;
		if (isset($input['page'])) 
		{
			$page 			= (!empty($input['page'])) ? $input['page'] : 1;
		}
		$perPage 			= isset($input['limit']) ? $input['limit'] : 10;
		$skip 				= ($page - 1) * $perPage;

		$order_by = isset($input['order_by']) ? $input['order_by'] : $this->table_module.'.date_created';
		$type_sort = isset($input['sort']) ? $input['sort'] : 'DESC';

		$select = array(
								$this->table_module.'.*',
								"DATE_FORMAT(DATE_ADD(date_created, INTERVAL 7 HOUR), '%Y-%m-%d %H:%i') as date_created",
							);

		$select = implode(', ', $select);

		$query = $this->model_class::select(DB::raw($select));

		$data_count 	= $query->count();

		$query = $query->orderBy($order_by, $type_sort)
									->take($perPage)
									->skip($skip)
									->get();

		$data 				= json_decode(json_encode($query), True);

		$last_page = ceil($data_count / $perPage);

		$result = [
			'total_data' 						=> $data_count,
			"limit"							=> (int) $perPage,
			"prev_page"					=> ($page == 1) ? 1 : $page - 1, # next page
			"current_page"			=> (int) $page, # next page
			"next_page"					=> ($last_page <= $page) ? '' : $page + 1, # next page
			"last_page"					=> $last_page, # next page
			"total_page"				=> $last_page, # next page
			'data' 							=> $data,
		];

		foreach ($result['data'] as $key => $value) 
		{
			$result['data'][$key]['orders_detail'] = $this->getDetailOrdersById($value['orders_serial_id']);
		}

		return $result;
	}

	public function proccess_orders($input=array())
	{
		$input_detail = array();
		$input_orders = array();
		$input_cd = array();
		$users_uuid = isset($input['users_uuid']) ? $input['users_uuid'] : '';
		$data_cd = isset($input['cd_serial_id']) ? $input['cd_serial_id'] : array();
		$date_now = date('Y-m-d H:i:s');
		unset($input['users_uuid']);

		$check_users = $this->model_class_users::where('deleted', '=', 0)
											->where('users_uuid', '=', $users_uuid)
											->first();

		if (@count($check_users) > 0) 
		{
			$check_users = $check_users->toArray();
			$input_orders[$this->table_module.'_uuid'] = $this->uuid::uuid4()->toString();
			$input_orders[$this->table_module.'_status'] = 0;
			$input_orders['date_created'] = date('Y-m-d H:i:s');
			$input_orders['users_serial_id'] = $check_users['users_serial_id'];

			$save_orders = $this->model_class::create($input_orders);

			$orders_id = '';
			if ($save_orders) 
			{
				$orders_id = $save_orders->orders_serial_id;
			}
		}
		else
		{
			return false;
		}

		$price_cd = $this->model_class_cd::whereIn('cd_serial_id', $data_cd)
										->get()
										->toArray();

		$i = 0;
	
		foreach ($data_cd as $key => $value) 
		{
			$input_detail[$i]['cd_serial_id'] = $value;
			if (isset($input['cd_quantity'][$key]) AND isset($input['orders_day'][$key])) 
			{
				$arr_cd = in_array($value, array_column($price_cd, 'cd_serial_id'));

				if ($arr_cd) 
				{
					$input_detail[$i]['orders_detail_quantity'] = $input['cd_quantity'][$key];
					$input_detail[$i]['orders_detail_days'] = $input['orders_day'][$key];
					$input_detail[$i]['orders_detail_start_date'] = $date_now;
					$input_detail[$i]['orders_detail_end_date'] = date('Y-m-d H:i:s', strtotime('+'.$input['orders_day'][$key].' days'));

					$key_cd = array_search($value, array_column($price_cd, 'cd_serial_id'));
					$input_detail[$i]['orders_detail_rate'] = $price_cd[$key_cd]['cd_rate'];
					$input_detail[$i]['orders_serial_id'] = $orders_id;
					$input_detail[$i]['orders_detail_uuid'] = $this->uuid::uuid4()->toString();
					$input_cd[$i]['cd_serial_id'] = $value; 
					$input_cd[$i]['cd_quantity'] = $price_cd[$key_cd]['cd_quantity'] - $input['cd_quantity'][$key]; 

					# delete data when stock not found
					if ($price_cd[$key_cd]['cd_quantity'] < $input['cd_quantity'][$key]) 
					{
						unset($input_detail[$i]);
					}
				}
				else
				{
					unset($input_detail[$i]);					
				}

			}
			else
			{
				unset($input_detail[$i]);
			}
			$i++;
		}

		if (@count($input_detail) > 0) 
		{
			if (!empty($orders_id)) 
			{
				$save_orders_detail = $this->model_class_orders_detail::insert($input_detail);

				# update stok
				foreach ($input_cd as $key => $value) 
				{
					$this->model_class_cd::where('cd_serial_id', '=', $value['cd_serial_id'])
							->update(['cd_quantity' => $value['cd_quantity']]);
				}
			}
		}
		else
		{
			# delete order when stock not found and detail not found
			$this->model_class::where('orders_serial_id', '=', $orders_id)->delete();

			return false;
		}

		return $save_orders;

	}

	public function getDetailOrdersById($serial_id='')
	{
		$query = $this->model_class_orders_detail::select(DB::raw('orders_detail.*,cd.cd_title'))
								->where('orders_serial_id', '=', $serial_id)
								->leftJoin('cd', 'cd.cd_serial_id', '=', 'orders_detail.cd_serial_id')
								->get()
								->toArray();

		return $query;
	}

	public function getDetailOrdersByUuid($uuid='')
	{
		$query = $this->model_class_orders_detail::select(DB::raw('orders_detail.*,cd.cd_title'))
								->where('orders_detail_uuid', '=', $uuid)
								->leftJoin('cd', 'cd.cd_serial_id', '=', 'orders_detail.cd_serial_id')
								->first();

		if (@count($query) > 0) 
		{
			$query = $query->toArray();
		}

		return $query;
	}

	public function detailData($uuid='')
	{
		$data = array();
		$query = $this->model_class::select(DB::raw('
											orders.*,
											users.users_name,
											users.users_email,
											users.users_phone
									'))
									->where('orders_uuid', '=', $uuid)
									->leftJoin('users', 'users.users_serial_id', '=', 'orders.users_serial_id')
									->first();

		if (@count($query) > 0) 
		{
			$data = $query->toArray();

			$data['orders_detail'] = $this->getDetailOrdersById($query['orders_serial_id']);
			
		}

		return $data;
	}

	public function orders_return($input=array())
	{
		$uuid = isset($input['orders_detail_uuid']) ? $input['orders_detail_uuid'] : '';

		# trugger date end dummy
		$date_now = '2020-05-12 17:00';

		// $date_now = date('Y-m-d H:i:s');

		$check_detail_orders = $this->model_class_orders_detail::where('orders_detail_uuid', '=', $uuid)->first();

		if (@count($check_detail_orders) > 0) 
		{
			$check_detail_orders = $check_detail_orders->toArray();
			$date_end = $check_detail_orders['orders_detail_end_date'];

			if ($date_now > $date_end) 
			{
				$datetime1 = new DateTime($date_end);

				$datetime2 = new DateTime($date_now);

				$difference = $datetime1->diff($datetime2);
				
				$input_detail['orders_late_days'] = $difference->d;
				$input_detail['orders_late_date'] = $date_now;
				$input_detail['orders_late_charge'] = $difference->d * $check_detail_orders['orders_detail_rate'];
				$input_detail['orders_detail_return'] = 1;

				$update_detail = $this->model_class_orders_detail::where('orders_detail_uuid', '=', $uuid)->update($input_detail);

			}
			
			#update total
			$check_orders = $this->model_class::where('orders_serial_id', '=', $check_detail_orders['orders_serial_id'])->first();

			if (@count($check_orders) > 0) 
			{
				$check_orders = $check_orders->toArray();
				
				$orders_total = 0;
				$late_charge = 0;
				if (!empty($check_orders['orders_total'])) 
				{
					$orders_total = $check_orders['orders_total'];
				}

				if (isset($input_detail['orders_late_charge'])) 
				{
					$late_charge = $input_detail['orders_late_charge'];
				}

				$input_orders['orders_total'] = $late_charge + $orders_total + ($check_detail_orders['orders_detail_rate'] * $check_detail_orders['orders_detail_days']);
				// $input_orders
				$this->model_class::where('orders_serial_id', '=', $check_detail_orders['orders_serial_id'])->update($input_orders);

				$data_cd = $this->model_class_cd::where('cd_serial_id', '=', $check_detail_orders['cd_serial_id'])->first();

				$input_cd['cd_quantity'] = $data_cd['cd_quantity'] + $check_detail_orders['orders_detail_quantity'];
				# update stok
				$this->model_class_cd::where('cd_serial_id', '=', $check_detail_orders['cd_serial_id'])->update($input_cd);
			}

			return true;
		}


	}

}