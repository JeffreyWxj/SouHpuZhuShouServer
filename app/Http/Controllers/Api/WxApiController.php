<?php

namespace App\Http\Controllers\Api;

use App\Applet\Openid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WxApiController extends Controller
{
	public function jsCodeToOpenId(Request $request)
	{
		$validator = validator($request->all(), [
			'js_code' => ['required']
		]);
		if ($validator->fails()) {
			return [
				'status' => 'error',
				'msg' => $validator->errors()->first()
			];
		}
		$openid = Openid::jsCodeToOpenid($request->input('js_code'));
		return [
			'status' => 'success',
			'msg' => '获取openid成功',
			'data' => $openid
		];
	}
}
