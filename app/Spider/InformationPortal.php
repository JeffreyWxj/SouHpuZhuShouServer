<?php

namespace App\Spider;


use GuzzleHttp\Client;
use Sunra\PhpSimple\HtmlDomParser;

class InformationPortal
{
	public function __construct($stuid, $id_number)
	{
		$this->stuid = $stuid;
		if (strlen($id_number) == 15) {
			$this->id_number = substr($id_number, 9, 6);
		} else {
			$this->id_number = substr($id_number, 11, 6);
		}
		$this->client = new Client([
			'verify' => false,
			'cookies' => true
		]);
		$this->login();
	}
	
	public function login()
	{
		$postData = [
			'Login.Token1' => '',
			'Login.Token2' => '',
			'goto' => 'http%3A%2F%2Fmy.hpu.edu.cn%2FloginSuccess.portal',
			'gotoOnFail' => 'http%3A%2F%2Fmy.hpu.edu.cn%2FloginFailure.portal'
		];
		$headers = [
			'Referer' => 'http://my.hpu.edu.cn/login.portal'
		];
		$this->client->post('http://my.hpu.edu.cn/userPasswordValidate.portal', [
			'headers' => $headers,
			'form_params' => $postData
		]);
	}
	
	public function getCurrentWeekNum()
	{
		$response = $this->client->get('http://my.hpu.edu.cn/viewschoolcalendar3.jsp', [
			'headers' => [
				'Referer' => 'http://my.hpu.edu.cn/index.portal'
			]
		]);
		$dom = HtmlDomParser::str_get_html((string)$response->getBody());
		$week = intval($dom->find('.red')[1]->text());
		return $week;
	}
}