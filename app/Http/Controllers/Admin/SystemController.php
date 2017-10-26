<?php

namespace App\Http\Controllers\Admin;

use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SystemController extends Controller
{
  public function envSetting(Request $request)
  {
    //本学期开始日期
    $term_start_date = Setting::getSetting('TermStartDate');
    $term_start_date = date('Y-m-d', $term_start_date);
    return view('admin.system.env-setting', compact('term_start_date'));
  }
  
  /**
   * 设置本学期的起始日期
   * 第一周的周一日期
   */
  public function termStart(Request $request)
  {
    $data = $request->all();
    $validator = validator($data, [
      'start_date' => ['required', 'date']
    ]);
    if ($validator->fails()) {
      return [
        'status' => 'error',
        'msg' => $validator->errors()->first()
      ];
    }
    $day = (new Carbon($data['start_date']))->dayOfWeek;
    if ($day != Carbon::MONDAY) {
      return [
        'status' => 'error',
        'msg' => '请选择第一周的周一'
      ];
    }
    Setting::setSetting('TermStartDate', strtotime($data['start_date']));
    return [
      'status' => 'success',
      'msg' => '设置成功',
    ];
  }
}
