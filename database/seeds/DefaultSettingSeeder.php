<?php

use Illuminate\Database\Seeder;

class DefaultSettingSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $settings = [
      // 本学期默认开始日期
      ['key' => 'TermStartDate', 'value' => '0']
    ];
    foreach ($settings as $setting) {
      \App\Models\Setting::setSetting($setting['key'], $setting['value']);
    }
  }
}
