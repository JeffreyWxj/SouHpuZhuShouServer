<?php

namespace App\Http\Controllers\Api;

use App\Models\Student;
use App\Spider\SpiderCore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
	public function login(Request $request)
	{
		$validator = validator($request->all(), [
			'openid' => 'required',
			'stuid' => 'required',
			'password' => 'required',
			'verify_code' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'status' => 'error',
				'msg' => $validator->errors()->first()
			];
		}
		// 取出缓存的cookieJar
		$cache_cookie = cache(md5($request->input('openid')));
		if (!$cache_cookie) {
			return [
				'status' => 'error',
				'msg' => '骚年，验证码超时啦，刷新验证码重试吧~~ -_-'
			];
		} else {
			$cookieJar = unserialize($cache_cookie);
			$spider = new SpiderCore($cookieJar, true);
			$login_jiao_wu = $spider->loginJiaoWu($request->all());
			if ($login_jiao_wu) {   // 教务系统登录成功
				$stu = Student::getStudentObj($request->input('openid'), $request->input('stuid'), $request->input('password'));    // 获取stu实例
				$stu_info = $stu->getStuInfo;   // 获取stu_info实例
				$info = $spider->getStuInfo();  // 抓取stu_info
				$schedule_parsed= $spider->getSchedule();    // 抓取课表html
				$stu_info->schedule_parsed = json_encode($schedule_parsed);  // 存储课表html
				$stu_info->fill($info); // 存储stu_info
				$stu_info->save();  // 执行存储
				return [
					'status' => 'success',
					'msg' => '登录成功 6_6'
				];
			} else {
				return [
					'status' => 'error',
					'msg' => '输入的信息不正确啊，再检查一下？ @_@'
				];
			}
		}
	}
	
	public function studentInfo(Request $request)
	{
		$data = $request->all();
		$validator = validator($data, [
			'openid' => 'required',
			'stuid' => ['required', Rule::exists('students', 'stuid')],
		]);
		if ($validator->fails()) {
			return [
				'status' => 'error',
				'msg' => $validator->errors()->first()
			];
		}
		$stu = Student::where('stuid', $data['stuid'])->first();
		$info = $stu->getStuInfo;
		$info->schedule_parsed=json_decode($info->schedule_parsed,true);
		return [
			'status' => 'success',
			'msg' => '请求成功',
			'data' => $info
		];
	}
}
