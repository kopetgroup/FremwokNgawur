<?php
namespace insert;

class Install {

  /*
    Install Database
  */
  function database(){

    $db = \Rizoa::getrizo('database');

    /*
      1. Create database
    */
    $r = \Couch::create($db,'container/insert/view');
    return $r;

  }


}

?>
