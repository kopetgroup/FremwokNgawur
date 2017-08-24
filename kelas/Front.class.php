<?php

class Front {

  public static function index($slug=''){

    /** var */
    $theme   = Rizoa::getrizo('theme');
    $siteurl = Rizoa::getrizo('siteurl');
    $assets  = $siteurl.'/themes/'.$theme.'/assets';

    /** data */
    $data    = [
      'siteurl' => $siteurl,
      'theme'   => $theme,
      'assets'  => $assets,
      'css'     => '',
      'js'      => ''
    ];
    $data['var'] = $data;

    Rizoa::view($theme.'/'.$slug,$data);


  }


}

?>
