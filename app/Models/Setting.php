<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
  public static function getSetting(string $key)
  {
    $setting = Setting::where('key', $key)->first();
    return $setting ? $setting->value : null;
  }
  
  public static function setSetting(string $key, $value)
  {
    $setting = Setting::where('key', $key)->first();
    if (!$setting) {
      $setting = new Setting();
      $setting->key = $key;
    }
    $setting->value = $value;
    return $setting->save();
  }
  
  public static function getCurrentWeek()
  {
    $term_start_date = Setting::getSetting('TermStartDate');
    $week = ceil((time() - intval($term_start_date)) / (7 * 24 * 3600));
    return ($week > 0 && $week < 21) ? $week : 1;
  }
}
