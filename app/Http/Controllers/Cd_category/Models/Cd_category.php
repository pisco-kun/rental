<?php

namespace App\Http\Controllers\Cd_category\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;

class Cd_category extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable;

    protected $table 		= "cd_category";
		protected $primaryKey 	= "cd_category_serial_id";
		protected $guarded = array('cd_category_serial_id');

		public $timestamps = false;
}
