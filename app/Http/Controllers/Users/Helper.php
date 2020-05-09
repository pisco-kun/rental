<?php

namespace App\Http\Controllers\Users;

use DB;

class Helper 
{
	var $uuid = 'Ramsey\Uuid\Uuid';
	var $uuid_parent = 'Ramsey\Uuid\Exception\UnsatisfiedDependencyException';
	var $model_class = 'App\Http\Controllers\Users\Models\Users';

	var $module = 'Users';
	var $table_module = 'users';

	function example_uuid()
	{
		$uuid4 	= $this->uuid::uuid4()->toString();

		return $uuid4;
	}

	public function check_login($input=array())
	{
		$email = isset($input[$this->table_module.'_email']) ? $input[$this->table_module.'_email'] : '';

		$query = $this->model_class::where($this->table_module.'_email', '=', $email)
									->first();

		return $query;
	}

	public function save_data($input=array())
	{
		unset($input[$this->table_module.'_password_confirm']);
		$input[$this->table_module.'_uuid'] = $this->uuid::uuid4()->toString();
		$input['date_created'] = date('Y-m-d H:i:S');
		$query = $this->model_class::create($input);

		return $query;
	}

}