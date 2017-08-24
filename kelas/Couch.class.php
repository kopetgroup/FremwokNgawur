<?php
class Couch {

	/**
	 * CouchDB Wrapper
	 * forking asli diambil dari github.
	 * penambahan fungsi untuk createdb,add,edit,delete,drop
	 * @package    RizoaFrameWalk
	 * @author     Riza Masykur <hanirizo@gmail.com>
	 *
	 * Function:
	 * get
	 * compact
	 * put
	 * edit
	 * create 	=> create db if no exsist, auto insert design
	 * drop
	 * send
	 * scandir
	*/

	/*
		GET documents
	*/
	public static function get($dbname,$view,$arr){

		$md = '';
		foreach($arr as $k=>$v){
			$md .= $k.'='.$v.'&';
		}

		$data = Couch::send('GET','/'.$dbname.'/_design/'.$view.'/_view/'.$view.'?'.$md);
		$mt 	= '';
		if(isset($data->rows[0])){

			if(isset($arr['include_docs'])){
				if($arr['include_docs']=='true'){
					foreach($data->rows as $r){
						$mt[] =	$r->doc;
					}
				}else{
					$mt = $data->rows;
				}
			}else{
				$mt = $data->rows;
			}

		}
		return $mt;

	}

	/*
		Compact database
	*/
	public static function compact($dbname){

		$r = shell_exec('curl -H "Content-Type: application/json" -X POST http://localhost:5984/'.$dbname.'/_compact');
		return json_decode($r);

	}

	/*
		Create Document
	*/
  public static function put($dbname,$data){

		$data = Couch::send('PUT','/'.$dbname.'/'.$data['_id'],$data);
		return $data;

	}

	/*
		Edit document
	*/
  public static function edit($dbname,$data){

		$i = Couch::send('GET','/'.$dbname.'/'.$data['_id']);
		$d = '';

		if($i){
			$d['_rev'] = $i->_rev;
			foreach($data as $c=>$v){
				$d[$c] = $v;
			}
		}
		$data = Couch::send('POST','/'.$dbname.'/_design/edit/_update/edit/'.$data['_id'],$d);
		return $data;

	}

	/*
		Create Database
		- auto insert _design
	*/
  public static function create($dbname,$design=''){

		$r 			= Couch::send('PUT','/'.$dbname);
		$result = '';
		if(isset($r->ok)){

			$result = [
				'status' => 'success',
				'msg'		 => 'database '.$dbname.' created'
			];

			/** Sisan Gawe Edit */
			$item = [
				'_id' 			=> '_design/edit',
				'language'	=> 'javascript',
				'updates'		=> [
					'edit'				=> 'function (doc, req) { var fields = JSON.parse(req.body); for (var i in fields) { doc[i] = fields[i] } var resp = eval(uneval(doc)); delete resp._revisions; return [doc, toJSON(resp)]; }'
				]
			];
			Couch::send('PUT','/'.$dbname.'/_design/edit',$item);


		}else{
			$result = [
				'status' => $r->error,
				'msg'		 => $r->reason
			];
		}

		/*
			auto insert _design
		*/
		if($design==''){

		}else{

			/*
				lets insert
			*/
			$e = Couch::scandir($design);
			foreach($e as $f){
				Couch::send('PUT','/'.$dbname.'/_design/'.$f,json_decode(file_get_contents($design.'/'.$f)));
			}

		}

		return $result;

	}

	/*
		Busak Database
	*/
	public static function drop($dbname){

		$r = Couch::send('DELETE','/'.$dbname);

		if($r->ok==1){
			$result = [
				'status' => 'success',
				'msg'		 => 'database '.$dbname.' deleted'
			];
		}else{
			$result = [
				'status' => $r->error,
				'msg'		 => $r->reason
			];
		}
		return $result;

	}

	/*
		General couchdb wrapper
	*/
  public static function send($method, $url, $post_data = NULL) {

		$post_data = json_encode($post_data);
  	$host = 'localhost';
  	$port = '5984';
    $s = fsockopen($host, $port, $errno, $errstr);
    if(!$s) {
       //echo "$errno: $errstr\n";
       //return false;
    }
    $request = "$method $url HTTP/1.0\r\nHost: $host\r\n";
    if($post_data) {
     $request .= "Content-Length: ".strlen($post_data)."\r\n\r\n";
     $request .= "$post_data\r\n";
    }
    else {
     $request .= "\r\n";
    }
    fwrite($s, $request);
    $response = "";
    while(!feof($s)) {
      $response .= fgets($s);
    }

		list($a,$b) = explode("\r\n\r\n", $response);
		return json_decode($b);
  }

	/*
		Scan Directory
	*/
	public static function scandir($dir){

		$ignored = array('.', '..');
    $files = array();
    foreach (scandir($dir) as $file) {
      if (in_array($file, $ignored)) continue;
      $files[$file] = filemtime($dir . '/' . $file);
    }
    arsort($files);
    $files = array_keys($files);
    foreach($files as $d){
      $ds[] = str_replace('.php','',$d);
    }
    return $ds;

	}

}
