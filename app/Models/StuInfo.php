<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StuInfo extends Model
{
	protected $fillable = [
		'stu_name',
		'id_number',
		'ji_guan',
		'gao_zhong',
		'gao_kao_num',
		'xi_suo',
		'zhuan_ye',
		'ban_ji',
	];
}
