<?php

namespace App\Http\Controllers\Api;

use App\Spider\SpiderCore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SpiderController extends Controller
{
	public function verifyImg(Request $request)
	{
		$validator = validator($request->all(), [
			'openid' => 'required'
		]);
		if ($validator->fails()) {
			return [
				'status' => 'error',
				'msg' => $validator->errors()->first(),
			];
		}
		$spider = new SpiderCore();
		$spider->loginVPN();
		$img_b64 = $spider->getVerifyImg($request->input('openid'));
		if ($img_b64) {
			return [
				'status' => 'success',
				'msg' => '获取验证码图片成功！',
				'data' => [
					'img' => $img_b64,
					'session'=>\Cache::get(md5($request->input('openid')))
				]
			];
		} else {
			return [
				'status' => 'error',
				'msg' => '获取图片验证码失败！'
			];
		}
	}
	
}
