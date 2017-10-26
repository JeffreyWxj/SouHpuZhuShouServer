<?php
/**
 * 去除空格和nbsp
 * @param $str
 * @return mixed
 */
function trimWhite($str){
  $str = str_replace(' ', '', $str);
  $str = str_replace('&nbsp;', '', $str);
  $str=trim($str);
  return $str;
}