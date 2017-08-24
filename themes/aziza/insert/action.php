<?php
$action = $_POST['action'];

if($action=='insert'){
  $insert  = new \insert\Insert();
  echo json_encode($insert->lebokno());
}else{
  $kategori  = new \insert\Kategori();
  if($_POST['type']=='category'){
    echo json_encode($kategori->getcategory());
  }else{
    echo json_encode($kategori->getsubcategory($_POST['category']));
  }
}
?>
