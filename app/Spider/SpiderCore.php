<?php

namespace App\Spider;


use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Mockery\Exception;
use PHPHtmlParser\Dom;
use Sunra\PhpSimple\HtmlDomParser;

class SpiderCore
{
  public function __construct($cookieJar = null, $is_login_vpn = false)
  {
    if (!$cookieJar) {
      // 创建cookieJar
      $this->cookieJar = new CookieJar();
    } else {
      $this->cookieJar = $cookieJar;
    }
    // 创建请求客户端
    $this->client = new Client([
      'verify' => false,
      'cookies' => $this->cookieJar
    ]);
    // 状态记录
    $this->is_login_vpn = $is_login_vpn;    // 是否登录了VPN
    $this->is_login_jiaowu = false; // 是否登录了教务系统
    // Url集合
    $this->urls = [
      //VPN登录页面
      'vpn_login_page' => 'https://vpn.hpu.edu.cn/por/login_psw.csp',
      //VPN登录POST地址
      'vpn_login_post_url' => 'https://vpn.hpu.edu.cn/por/login_psw.csp?sfrnd=2346912324982305',
      // 验证码图片请求地址
      'verify_img_url' => 'https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/validateCodeAction.do',
      // 教务系统登录POST
      'jiaowu_login_post_url' => 'https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97/loginAction.do',
      // 本学期课表
      'ben_xue_qi_ke_biao' => 'https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/xkAction.do?actionType=6',
      // 学籍信息
      'xue_ji_xin_xi' => 'https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/xjInfoAction.do?oper=xjxx',
      // 本学期成绩
      'ben_xue_qi_cheng_ji' => 'https://vpn.hpu.edu.cn/web/0/http/1/218.196.240.97/bxqcjcxAction.do'
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
    // 请求教务系统的header
    $this->jiaowu_headers = [
      'Referer' => 'https://vpn.hpu.edu.cn/web/1/http/1/218.196.240.97',
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
      cache([md5($openid) => $cookies], 2);
      return base64_encode($response->getBody());
    } else {
      return false;
    }
  }
  
  public function loginJiaoWu($jiaowu_data)
  {
    if (!$this->is_login_vpn) {
      throw new Exception('未登录VPN无法抓取课表');
    }
    $params = [
      'zjh' => $jiaowu_data['stuid'],
      'mm' => $jiaowu_data['password'],
      'v_yzm' => $jiaowu_data['verify_code']
    ];
    $response = $this->client->post($this->urls['jiaowu_login_post_url'], [
      'headers' => $this->jiaowu_headers,
      'form_params' => $params,
    ]);
    $result_str = iconv('gbk', 'utf-8', (string)$response->getBody());
    if (strpos($result_str, '<title>学分制综合教务</title>') !== false) {
      $this->is_login_jiaowu = true;
      return true;
    } else {
      return false;
    }
  }
  
  /**
   * 获取课程表
   */
  public function getSchedule()
  {
    /**
     * 周数字符串处理成数组
     * etc..3-4周=>[3,4]
     * @param $str
     * @return array
     */
    function solveWeek($str)
    {
      $str = str_replace('周', '', $str);
      $str = str_replace('上', '', $str);
      $str = trimWhite($str);
      if ($str == '') {
        return [];
      }
      $block = explode(',', $str);
      $result = [];
      foreach ($block as $item) {
        $section = explode('-', $item);
        if (count($section) == 2) {
          for ($i = intval($section[0]); $i <= intval($section[1]); $i++) {
            $result[] = $i;
          }
        } else if (count($section) == 1) {
          $result[] = intval($section[0]);
        }
      }
      return $result;
    }
    
    /**
     * 节次字符串处理成数字
     * @param $str
     * @return int|mixed
     */
    function solveSection($str)
    {
      $str = trimWhite($str);
      if ($str == '') {
        return 0;
      }
      $map = ['一' => 1, '二' => 2, '三' => 3, '四' => 4, '五' => 5, '六' => 6, '七' => 7, '八' => 8, '九' => 9];
      return array_get($map, $str, 0);
    }
    
    /**
     * 生成 周数=>节次=>课程 数组
     * @param $current_result
     * @param $weeks
     * @param $section
     * @param $main_info
     * @return mixed
     */
    function genSchedule($current_result, $weeks, $day, $section, $main_info)
    {
      foreach ($weeks as $week) {
        $current_result[$week][$day][$section] = $main_info;
      }
      return $current_result;
    }
    
    $response = $this->client->get($this->urls['ben_xue_qi_ke_biao']);
    $body = mb_convert_encoding((string)$response->getBody(), 'utf-8', 'gbk');
    $dom = HtmlDomParser::str_get_html($body);
    $tables = $dom->find('table');
    $table = $tables[7];
    $thead = $table->find('thead')[0];
    // 表头数量
    $count = count($thead->find('th'));
    $idx = -1;    // 课程索引
    $main_info = [];  // 某个课程的主要信息缓存
    $result = []; // 整理结果
    $trs = $table->find('tr');
    for ($i = 1; $i < count($trs); $i++) {
//    foreach ($table->find('tr') as $tr) {
      $tr = $trs[$i];
      $tds = $tr->find('td');
      if (count($tds) == $count) {   // 主课程条目
        $idx++;
        $main_info = [
          '课程名' => trimWhite($tds[2]->text()),
          '教师' => trimWhite($tds[7]->text())
        ];
        $weeks = solveWeek($tds[11]->text());
        $day = intval(trimWhite($tds[12]->text()));
        $section = solveSection($tds[13]->text());
        $main_info['教学楼'] = trimWhite($tds[16]->text() . $tds[17]->text());
      } else {  // 附加课程条目
        $weeks = solveWeek($tds[0]->text());
        $day = intval(trimWhite($tds[1]->text()));
        $section = solveSection($tds[2]->text());
        $main_info['教学楼'] = trimWhite($tds[5]->text() . $tds[6]->text());
      }
      $result = genSchedule($result, $weeks, $day, $section, $main_info);
    }
    return $result;
  }
  
  public function getStuInfo()
  {
    $response = $this->client->get($this->urls['xue_ji_xin_xi']);
    $body = mb_convert_encoding($response->getBody(), 'utf-8', 'gbk');
    $dom = HtmlDomParser::str_get_html($body);
    $tables = $dom->find('table#tblView');
    $table = $tables[0];
    $trs = $table->find('tr');
    $stu_name = trim($trs[0]->find('td.fieldName')[1]->next_sibling()->text());
    $id_number = trim($trs[2]->find('td.fieldName')[1]->next_sibling()->text());
    $ji_guan = trim($trs[6]->find('td.fieldName')[0]->next_sibling()->text());
    $gao_zhong = trim($trs[8]->find('td.fieldName')[0]->next_sibling()->text());
    $gao_kao_num = trim($trs[9]->find('td.fieldName')[1]->next_sibling()->text());
    $xi_suo = trim($trs[12]->find('td.fieldName')[1]->next_sibling()->text());
    $zhuan_ye = trim($trs[13]->find('td.fieldName')[0]->next_sibling()->text());
    $ban_ji = trim($trs[14]->find('td.fieldName')[1]->next_sibling()->text());
    $result = compact('stu_name', 'id_number', 'ji_guan', 'gao_zhong', 'gao_kao_num', 'xi_suo', 'zhuan_ye', 'ban_ji');
    return $result;
  }
  
  public function getScore()
  {
    $response = $this->client->get($this->urls['ben_xue_qi_cheng_ji']);
    $body = mb_convert_encoding($response->getBody(), 'utf-8', 'gbk');
    $dom = HtmlDomParser::str_get_html($body);
    $table = $dom->find('table')[6];
    $trs = $table->find('tr');
    $result = [];
    for ($i = 1; $i < count($trs); $i++) {
      $tds = $trs[$i]->find('td');
      $one = [
        '课程名' => trimWhite($tds[2]->text()),
        '学分' => trimWhite($tds[4]->text()),
        '最高分' => trimWhite($tds[6]->text()),
        '最低分' => trimWhite($tds[7]->text()),
        '平均分' => trimWhite($tds[8]->text()),
        '成绩' => trimWhite($tds[9]->text()),
        '名次' => trimWhite($tds[10]->text()),
      ];
      $result[] = $one;
    }
    return $result;
  }
  
}