<?php

namespace App\Spider;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class SpiderCore
{
	public function __construct()
	{
		// 创建cookieJar
		$this->cookieJar = new CookieJar();
		// 创建请求客户端
		$this->client = new Client([
			'verify' => false,
			'cookies' => $this->cookieJar
		]);
		// 状态记录
		$this->is_login_vpn = false;    // 是否登录了VPN
		$this->is_login_jiaowu = false; // 是否登录了教务系统
		// Url集合
		$this->urls = [
			//VPN登录页面
			'vpn_login_page' => 'https://vpn.hpu.edu.cn/por/login_psw.csp',
			//VPN登录POST地址
			'vpn_login_post_url' => 'https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305',
			// 验证码图片请求地址
			'verify_img_url' => 'https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/validateCodeAction.do'
		];
		// VPN账号
		$this->vpn_account = [
			'svpn_name' => env('VPN_STUID'),
			'svpn_password' => env('VPN_PASSWORD'),
			'mitm_result' => '',
			'svpn_rand_code' => '',
		];
		// 所需的headers
		// 请求VPN登录页面&&执行VPN登录
		$this->vpn_headers = [
			'Referer' => 'https://vpn.hpu.edu.cn/por/login_psw.csp',
			'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0'
		];
	}
	
	/**
	 * 登录VPN
	 */
	public function loginVPN()
	{
		// 访问VPN登录页面
		$this->client->get($this->urls['vpn_login_page'], [
			'headers' => $this->vpn_headers
		]);
		// 发送账号密码模拟登录
		$login = $this->client->post($this->urls['vpn_login_post_url'], [
			'headers' => $this->vpn_headers,
			'form_params' => $this->vpn_account,
			'allow_redirects' => false
		]);
		if ($login->getStatusCode() == '302') {//VPN登录成功
			$this->is_login_vpn = true;
		} else {
			$this->is_login_vpn = false;
		}
	}
	
	/**
	 * 获取图片验证码
	 * @param $openid
	 * @return bool|string
	 */
	public function getVerifyImg($openid)
	{
		if (!$this->is_login_vpn) {
			return false;
		}
		$response = $this->client->get($this->urls['verify_img_url']);
		if ($response->getStatusCode() == '200') {
			// 获取图片验证码成功
			$cookies = serialize($this->cookieJar);
			// 把爬虫cookie存储到session中
			\Cache::set(md5($openid), $cookies);
			return base64_encode($response->getBody());
		} else {
			return false;
		}
	}
}