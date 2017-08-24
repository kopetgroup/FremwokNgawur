<?php
/**
 * Ini adalah framework kedua. diambil dari framework yang pertama,
 * lebih ke penataan ulang koding.
 *
 * @package    RizoaFrameWalk
 * @author     Riza Masykur <hanirizo@gmail.com>
 *
 * Functions:
 *  sedotgambar
 *  notfound
 *  app
 *  min
 *  view
 *  uar
 *  gawefolder
 *  setrizo
 *  getrizo
 *  ceklogin
 *  cookie
 *  post
 *  get
 *  slug
 *  redirect
 */
class Rizoa {

  /*
    Download images to server
  */
  public static function sedotgambar($url,$path,$minwidth=600){

    $bsnm = basename($path);
    $patx = explode('/',str_replace('/'.$bsnm,'',$path));

    $i = 0; $rt = '';
    foreach($patx as $p){

      $rt[] = $path[$i].'/';
      $art  = implode($rt);

      /*
        1. bikinin foldernya
      */
      Rizoa::gawefolder($art);

    $i++;}

    //masking to i0
    $url = str_replace('https://','https://i'.mt_rand(0,2).'.wp.com/',$url);
    $url = str_replace('http://','http://i'.mt_rand(0,2).'.wp.com/',$url);

    if(file_exists($path)){

      $status = 'failed';
      $msg    = 'image exist';

    }else{

      /*
        2. sedot gambarnya
      */
      $f   = shell_exec('curl -v --referer "'.$url.'" --connect-timeout 30 --max-time 30 -t 30 --user-agent "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.30 (KHTML, like Gecko) Chrome/12.0.742.112 Safari/534.30" '.$url.'');

      /*
        3. kopi ke path
      */
      file_put_contents($path, $f);

      /*
        4. validasi
      */
      $det  = getimagesize($path);
      $size = filesize($path);

      if($det[0]>$minwidth){

        $status = 'success';
        $msg    = 'image sedotted';

      }else{

        $status = 'failed';
        $msg    = 'image width tidak mencukupi (min width: '.$minwidth.'px)';

        unlink($path);

      }

      $bit    = $det['bits'];
      $width  = $det[0];
      $height = $det[1];
      $mime   = $det['mime'];

    }

    $dts = [
      'status'  => $status,
      'msg'     => $msg,
      'bit'     => $bit,
      'width'   => $width,
      'height'  => $height,
      'mime'    => $mime,
      'url'     => $url,
      'path'    => $path,
      'ext'     => basename($mime),
      'size'    => $size
    ];

    return (object) $dts;

  }

  /*
    Masking to 404
  */
  public static function notfound(){
    /** var */
    $theme   = Rizoa::getrizo('theme');
    $siteurl = Rizoa::getrizo('siteurl');
    $assets  = $siteurl.'/themes/'.$theme.'/assets';

    /** data */
    $data    = [
      'siteurl' => $siteurl,
      'theme'   => $theme,
      'assets'  => $assets
    ];
    $data['var'] = $data;
    Rizoa::view($theme.'/404',$data);
  }

  /*
    Get App Config
  */
  public static function app($name){
    if(file_exists('container/'.$name.'/app.json')){
      $a     = json_decode(file_get_contents('container/'.$name.'/app.json'));
      return $a;
    }
  }

  /*
    Minify by config
  */
  public static function min($bodi){
    $mini = Rizoa::getrizo('minify');
    if($mini=='yes'){
      return str_replace(' ',' ',preg_replace('/\s+/',' ', $bodi));
    }else{
      return $bodi;
    }
  }

  /*
    View Theme
  */
  public static function view($r,$v=array()) {
    if(isset($v)){
      extract($v);
    }
    if(file_exists('themes/'.$r.'.php')){
      include 'themes/'.$r.'.php';
      $output = ob_get_contents();
      //echo Rizoa::min($output);
    }else{
      if(file_exists('themes/'.$r.'/index.php')){
        if(isset($data)){
          extract($data);
        }
        include 'themes/'.$r.'/index.php';
        $output = ob_get_contents();
        //echo Rizoa::min($output);
      }else{
        if(isset($v)){
          extract($v);
        }
        include 'themes/'.Rizoa::getrizo('theme').'/index.php';
        $output = ob_get_contents();
        //echo Rizoa::min($output);
      }
    }
  }

  /*
    Unique Multiple Array
  */
  public static function uar($r,$k){
    $temp_array = array();
    foreach($r as &$v) {
      if(!isset($temp_array[$v[$k]])){
        $temp_array[$v[$k]] =& $v;
      }
    }
    $r = array_values($temp_array);
    return $r;
  }

  /*
    Gawe Folder
  */
  public static function gawefolder($path){
    if(file_exists($path)){}else{
      mkdir($path,0755,true);
    }
  }

  /*
    SetRizo
  */
  public static function setrizo($name,$value){
    Rizoa::gawefolder('cfg');
    $sr = "<?php ".'$meta='."'".$value."';"."?>";
    file_put_contents('cfg/'.$name.'.php',$sr);
  }

  /*
    GetRizo
  */
  public static function getrizo($name){
    if(file_exists('cfg/'.$name.'.php')){
      include 'cfg/'.$name.'.php';
      return $meta;
    }else{
      return '';
    }
  }


  /*
    Cek Login / Cookie = 1
  */
  public static function ceklogin(){
    if(isset($_COOKIE['login'])){
      if($_COOKIE['login']==1){
        return true;
      }else{
        return false;
      }
    }
  }

  /*
    Get Cookie
  */
  public static function cookie($name=''){
    if($name==''){}else{
      if(isset($_COOKIE[$name])){
        return $_COOKIE[$name];
      }else{
        return false;
      }
    }
  }

  /*
    Get _POST
  */
  public static function post($name=''){
    if($name==''){}else{
      if(isset($_POST[$name])){
        return $_POST[$name];
      }else{
        return false;
      }
    }
  }

  /*
    Get _GET
  */
  public static function get($name=''){
    if($name==''){}else{
      if(isset($_GET[$name])){
        return $_GET[$name];
      }else{
        return false;
      }
    }
  }

  /*
    Slug Generator
  */
  public static function slug($text) {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
      return 'n-a';
    }
    return $text;
  }

  public static function redirect($r=''){
    if($r==''){
      $r = Rizoa::getrizo('siteurl');
    }
    header("Location: ".$r);
    die();
  }
}

?>
