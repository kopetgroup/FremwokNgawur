<?php
namespace insert;

class Insert {

  /*
    Insert Kiwot
  */
  function lebokno(){

    if(isset($_POST)){
      $res = '';

      /*
        Insert Kategori
      */
      if($_POST['type']=='category'){

        if($_POST['category']==''){
          $type  = 'category';
        }else{
          $type  = 'subcategory';
        }

        $value = explode("\n",$_POST['value']);
        foreach($value as $v){

          $v   = trim($v);
          if($v!==''){
            $dt  = [
              '_id'     => $type.'_'.\Rizoa::slug($v),
              'title'   => $v,
              'parent'  => $_POST['category'],
              'type'    => $type,
              'date'    => date('Y-m-d H:i:s')
            ];
            $res[] = \Couch::put(\Rizoa::getrizo('database'),$dt);
          }
        }

      }else{
        /*
          Insert Kiwot
        */
        if(isset($_POST['subcat'])){
          $subcat = $_POST['subcat'];
        }else{
          $subcat = '';
        }
        $value = explode("\n",$_POST['value']);
        foreach($value as $v){

          $v   = trim($v);
          if($v!==''){
            $dt  = [
              '_id'         => 'keyword_'.\Rizoa::slug($v),
              'title'       => $v,
              'category'    => $_POST['category'],
              'subcategory' => $subcat,
              'type'        => 'keyword',
              'date'        => date('Y-m-d H:i:s')
            ];
            $res[] = \Couch::put(\Rizoa::getrizo('database'),$dt);
          }
        }

      }
      return $res;
    }
  }


}

?>
