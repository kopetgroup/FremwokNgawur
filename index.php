<?php
/*
  Bismillah
  Astaghfirullah
  Project Dimulai 3 April 2017, untuk projek RootpixelVideo
*/

//date_default_timezone_set('UTC');
date_default_timezone_set('Asia/Jakarta');
error_reporting(E_ALL); ini_set('display_errors', 1);

spl_autoload_register(function ($app) {
  $ds  = '/';
  $dir = __DIR__;
  $app = str_replace('\\', $ds, $app);
  $file = "{$dir}{$ds}kelas/{$app}.class.php";
  if(is_readable($file)){
    require_once $file;
  }else{

    $app   = str_replace('\\', '/', $app);
    $apx   = explode('/',$app);
    $apd   = $apx[0];
    unset($apx[0]);
    $apz   = implode('/',$apx);
    require_once 'container/'.$apd.'/'.$apz.'.php';

  }

});

include 'router.php';
