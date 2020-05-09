<?php

namespace App\Http\Controllers\Cd_category;

use DB;

class Helper 
{
	var $uuid = 'Ramsey\Uuid\Uuid';
	var $uuid_parent = 'Ramsey\Uuid\Exception\UnsatisfiedDependencyException';
	var $model_class = 'App\Http\Controllers\Cd_category\Models\Cd_category';

	var $module = 'Cd_category';
	var $table_module = 'cd_category';

	function example_uuid()
	{
		$uuid4 	= $this->uuid::uuid4()->toString();

		return $uuid4;
	}

	public function save($input=array(), $admin_id=0)
	{
		$input[$this->table_module.'_uuid'] = $this->uuid::uuid4()->toString();
    $input['created_by'] = $admin_id;
    $input['date_created'] = date('Y-m-d H:i:s');
    $process_save = $this->model_class::create($input);

    return $process_save;
	}

	public function list_data($input=array(), $auth_type='admin')
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

		$order_by = isset($input['order_by']) ? $input['order_by'] : $this->table_module.'.'.$this->table_module.'_name';
		$type_sort = isset($input['sort']) ? $input['sort'] : 'ASC';

		$select = array(
								$this->table_module.'.*',
								"DATE_FORMAT(DATE_ADD(date_created, INTERVAL 7 HOUR), '%Y-%m-%d %H:%i') as date_created",
								"DATE_FORMAT(DATE_ADD(date_modified, INTERVAL 7 HOUR), '%Y-%m-%d %H:%i') as date_modified",
							);

		if ($auth_type == 'admin') 
		{
			$admin_created = [
													"admin_created.admin_name as created_by",
													"admin_modified.admin_name as modified_by"
											];

			$select = array_merge($select, $admin_created);
		}

		$select = implode(', ', $select);

		$query = $this->model_class::select(DB::raw($select));

		if ($auth_type == 'admin') 
		{
			$query = $query->leftjoin('admin as admin_created', 'admin_created.admin_serial_id', '=', 'created_by')
											->leftjoin('admin as admin_modified', 'admin_modified.admin_serial_id', '=', 'modified_by');
		}

		$query = $query->where($this->table_module.'.deleted', '=', 0);

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

		return $result;
	}

	public function edit_data($uuid='')
	{
		$result = array();

		$query = $this->model_class::where($this->table_module.'_uuid', '=', $uuid)
									->first();

		if (@count($query) > 0) 
		{
			$result = $query->toArray();
		}

		return $result;
	}

	public function update($input=array(), $uuid='', $admin_id=0)
	{
		if (isset($input[$this->table_module.'_rate'])) 
		{
			$input[$this->table_module.'_rate'] = str_replace('.', '', $input[$this->table_module.'_rate']);
		}

		$input['date_modified'] = date('Y-m-d H:i:s');
		$input['modified_by']	= $admin_id;

		$process = $this->model_class::where($this->table_module.'_uuid', '=', $uuid)
										->update($input);

		return true;
	}

	public function delete($uuid='', $admin_id=0)
	{
		$input['date_modified'] = date('Y-m-d H:i:s');
		$input['modified_by']	= $admin_id;
		$input['deleted']	= 1;

		$process = $this->model_class::where($this->table_module.'_uuid', '=', $uuid)
										->update($input);

		return true;
	}

	public function quick_update($input=array(), $uuid='', $admin_id='')
	{
		unset($input['uuid']);

		$input['date_modified'] = date('Y-m-d H:i:s');
		$input['modified_by']	= $admin_id;

		$process = $this->model_class::where($this->table_module.'_uuid', '=', $uuid)
										->update($input);

		return true;
	}

	public function detailData($uuid='', $auth_type='admin')
	{
		$result = array();

		$select = array(
								$this->table_module.'.*',
								"DATE_FORMAT(DATE_ADD(date_created, INTERVAL 7 HOUR), '%Y-%m-%d %H:%i') as date_created",
								"DATE_FORMAT(DATE_ADD(date_modified, INTERVAL 7 HOUR), '%Y-%m-%d %H:%i') as date_modified",
							);

		if ($auth_type == 'admin') 
		{
			$admin_created = [
													"admin_created.admin_name as created_by",
													"admin_modified.admin_name as modified_by"
											];

			$select = array_merge($select, $admin_created);
		}

		$select = implode(', ', $select);

		$query = $this->model_class::select(DB::raw($select));

		if ($auth_type == 'admin') 
		{
			$query = $query->leftjoin('admin as admin_created', 'admin_created.admin_serial_id', '=', 'created_by')
											->leftjoin('admin as admin_modified', 'admin_modified.admin_serial_id', '=', 'modified_by');
		}

		$query = $query->where($this->table_module.'.deleted', '=', 0)
										->first();

		if (@count($query) > 0) 
		{
			$result = $query->toArray();
		}

		return $result;
	}

}