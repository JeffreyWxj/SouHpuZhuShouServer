<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
	public static function getStudentObj($openid, $stuid, $password)
	{
		$stu = Student::where('stuid', $stuid)->first();
		if (!$stu) {
			$stu = new Student();
			$stu->stuid = $stuid;
			$newStu = true;
		}
		$stu->openid = $openid;
		$stu->password = $password;
		$stu->save();
		if (isset($newStu) && $newStu) {
			$schedule = new StuInfo();
			$schedule->s_id = $stu->id;
			$schedule->save();
		}
		return $stu;
	}
	
	public function getStuInfo()
	{
		return $this->hasOne(StuInfo::class,'s_id','id');
	}
}
