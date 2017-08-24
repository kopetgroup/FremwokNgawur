<?php
namespace insert;

class Kategori {

  /*
    Get Subcat
  */
  function getsubcategory($cat){

    $db     = \Rizoa::getrizo('database');
    $view   = 'subcat_by_cat';
    $query  = [
      'descending'    => 'true',
      'reduce'        => 'false',
      'startkey'      => '["subcategory","'.$_POST['category'].'"]',
      'endkey'        => '["subcategory","'.$_POST['category'].'"]',
      'include_docs'  => 'true'
    ];
    return \Couch::get($db,$view,$query);

  }

  /*
    Get Kategori List
  */
  function getcategory(){

    $db     = \Rizoa::getrizo('database');
    $view   = 'by_type';
    $query  = [
      'descending'    => 'true',
      'reduce'        => 'false',
      'startkey'      => '["category",{}]',
      'endkey'        => '["category"]',
      'include_docs'  => 'true'
    ];
    return \Couch::get($db,$view,$query);

  }


}

?>
