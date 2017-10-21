<?php

namespace App\Applet;


use GuzzleHttp\Client;

class Openid
{
	public static function jsCodeToOpenid($js_code)
	{
		$url = config('wxapi.js_code_to_openid');
		$params = [
			'appid' => env('WX_APPLET_APPID'),
			'secret' => env('WX_APPLET_SECRET'),
			'js_code' => $js_code,
			'grant_type' => 'authorization_code'
		
		];
		$client = new Client(['verify' => false]);
		$response = $client->get($url, ['query' => $params]);
		$response = json_decode($response->getBody(), true);
		return $response;
	}
}