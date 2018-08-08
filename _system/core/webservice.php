<?php
$fnc = 'array2'.$ext;
$allow = 1;

if($ext=='xml'){
	echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
}

// security check
if($conf['service_security']=='whitelist'){
	$allowed_ip = json_decode(file_get_contents(SYS_DIR.'security/whitelist.json'), true);
	if(in_array(CLIENT_ADDR, $allowed_ip)){

	}else{
		$dataWebService = array('status'=>0, 'code'=>343031, 'message'=>'Unauthorized access');
		$allow = 0;
	}
}elseif($conf['service_security']=='blacklist'){
	$blocked_ip = json_decode(file_get_contents(SYS_DIR.'security/blacklist.json'), true);
	if(in_array(CLIENT_ADDR, $blocked_ip)){
		$dataWebService = array('status'=>0, 'code'=>343033, 'message'=>'Forbidden');
		$allow = 0;
	}else{

	}
}elseif($conf['service_security']=='auth'){
	$data = array('status'=>2, 'message'=>'cek permissions ');
}

if(DB_NAME){
	// test table name
	$test1 = $db->query("SELECT 1 as cek FROM $_GET[module] LIMIT 1");
	$test2 = $db->query("SELECT 1 as cek FROM tbl_$_GET[module] LIMIT 1");
	$test3 = $db->query("SELECT 1 as cek FROM view_$_GET[module] LIMIT 1");


	// set real table & used table name
	if($test1){
		$tbl_name = MODULE;
		$tbl_source = MODULE;
	}
	if($test2){
		$tbl_name = 'tbl_'.MODULE;
		$tbl_source = 'tbl_'.MODULE;
	}
	if($test3){
		$tbl_name = 'view_'.MODULE;
	}


	// collect all field from selected table
	$master_fields = $db->query("SELECT COLUMN_NAME as col FROM INFORMATION_SCHEMA.columns WHERE TABLE_SCHEMA='".DB_NAME."' AND TABLE_NAME ='$tbl_name'")->results();
	if($master_fields){
		foreach($master_fields as $fl) $master_fields['col'][] = $fl['col'];
		$master_fields = implode(',',$master_fields['col']);
	}else{
		$master_fields = '';
	}
	if($master_fields){
		if($_GET['fields']){
			$query_field = str_replace('-',' as ',str_replace([', ',' , '],',',$_GET['fields']));
			$dataWebService_field = preg_replace('/ as (\w+),/', ',', $query_field);
		}else{
			$query_field = $dataWebService_field = $master_fields;
		}
		// failed field
		$sisa_field = array_diff(explode(',',$dataWebService_field), explode(',',$master_fields));
	}
}


$valid_type = array('txt'=>'plain/text', 'json'=>'application/json', 'xml'=>'text/xml', 'xls'=>'application/vnd.ms-excel');
$no_xls = 0;
if($ext=='xls' && $allow==0){
	$no_xls = 1;
}


header("cache-control:public");
header('access-control-allow-origin:*');


if($ext=='xls'){
	if(is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
		if($allow){
			header('content-type:'.$valid_type[$ext]);
			include MODULE_PATH.$_GET['module'].'/' . "$ext.$slug1.php";
			exit;
		}
	}else{
		header('content-type:application/json');
		echo array2json(array('status'=>0, 'message'=>'The path you request is not valid. Please request a valid URLs!'));
	}
	exit;
}else{
	header('content-type:'.$valid_type[$ext]);
}



if($conf['service_security']=='auth' && MODULE=='auth'){
	if($slug1=='user' || $slug1=='session'){
		$dataWebService = array('status'=>1, 'message'=>'cek+proses ngasih auth');
	}elseif($slug1=='check'){
		$dataWebService = array('status'=>1, 'message'=>'cek session');
	}else{
		$dataWebService = array('status'=>0, 'message'=>'The path you request is not valid. Please request a valid URLs!');
	}
}elseif(is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
	if($allow){
		include MODULE_PATH.$_GET['module'].'/' . "$ext.$slug1.php";
		exit;
	}
}elseif($_GET['module'] && $slug1 && !is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
	if($allow){
		$dataWebService = array('status'=>0, 'message'=>'The path you request is not valid. Please request a valid URLs!');
	}
}else{
	if($allow==0){

	}elseif($allow==1 && DB_NAME && $tbl_source){
		$strsql = '';
		// /data.format
		if($slug1=='data'){
			// method GET (Get Data Detail)
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if($sisa_field){
					$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
				}else{
					if($_GET['params']){
						$query_where= "WHERE $_GET[params]";
						$query_where= str_replace(':',"='",$query_where);
						$query_where= str_replace('??',"' AND ",$query_where);
						$query_where= str_replace('//',"' OR ",$query_where);
						$query_where= preg_replace('/like\((\w+)--(.*)\)/',"$1 like '%$2%'",$query_where);
						$query_where= "$query_where'";
						$query_where= substr($query_where,-2,2)=="''" ? substr_replace($query_where,'',-1,1) : $query_where;
						$query_where= str_replace("like ' ","like '",$query_where);
					}
					if($_GET['group']){
						$_GET['group'] = str_replace('ascending','asc',$_GET['group']);
						$_GET['group'] = str_replace('descending','desc',$_GET['group']);
						$query_groupby= "group by $_GET[group]";
					}
					if($_GET['sort']){
						$_GET['sort'] = str_replace('ascending','asc',$_GET['sort']);
						$_GET['sort'] = str_replace('descending','desc',$_GET['sort']);
						$_GET['sort'] = str_replace('-',' ',$_GET['sort']);
						$query_orderby = "order by $_GET[sort]";
					}
					if($_GET['count']){
						$query_limit = "limit $_GET[count]";
					}
					if($slug2>0){
						$query_where = $query_where ? $query_where.' AND id'.MODULE."='$slug2'" : 'WHERE id'.MODULE."='$slug2'";
					}
					$strsql = "SELECT $query_field FROM $tbl_name $query_where $query_groupby $query_orderby $query_limit";
					$strsql = trim($strsql);
					$db->aResults = [];
					$dataWebService = $db->query($strsql);
					if($db->aResults[0])
						$dataWebService = $dataWebService->results();
					else
						$dataWebService = array('status'=>0, 'message'=>'No result to display for this query');
				}
			// method PUT (Update Data)
			}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
				if($_SERVER['REQUEST_METHOD']=='PUT'){
					$dataWebService = $_PUT;
				}else{
					$dataWebService = $_POST;
					unset($dataWebService['_METHOD']);
				}
				if($dataWebService){
					$cek_field = array_diff(array_keys($dataWebService), explode(',',$master_fields));
					if($cek_field){
						$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
					}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
						$upd = $db->update($tbl_source, $dataWebService, ['id'.MODULE=>$slug2])->affectedRows();
						if($upd)
							$dataWebService = array('status'=>1, 'message'=>'Data was updated successfull');
						else
							$dataWebService = array('status'=>2, 'message'=>'No data was updated. It caused no data was changed!');
					}else{
						$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}else{
					$dataWebService = array('status'=>3, 'message'=>'No request data sent!');
				}
			// method DELETE (Delete Data)
			}elseif($_SERVER['REQUEST_METHOD']=='DELETE' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE')){
				if($_SERVER['REQUEST_METHOD']=='DELETE'){
					$do_del = 1;
				}
				elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE'){
					$do_del = 1;
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
				if($do_del){
					$del = $db->delete($tbl_source, ['id'.MODULE=>$slug2])->affectedRows();
					if($del)
						$dataWebService = array('status'=>1, 'message'=>'Data was deleted successfull');
					else
						$dataWebService = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}else{
				$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
			}
		}
		// method POST (Input Data)
		elseif($slug1=='post'){
			if($_SERVER['REQUEST_METHOD']=='POST'){
				if($_POST){
					$cek_field = array_diff(array_keys($dataWebService), explode(',',$master_fields));
					if($cek_field){
						$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
					}else{
						$insert = $db->insert($tbl_source, $_POST)->getLastInsertId();
						if($insert)
							$dataWebService = array('status'=>1, 'message'=>'Data was saved successfull');
						else
							$dataWebService = array('status'=>0, 'message'=>'No data was saved');
					}
				}else{
					$dataWebService = array('status'=>0, 'message'=>'No post data sent!');
				}
			}else{
				$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
			}
		}
		// method GET PUT DELETE
		elseif($slug1=='id' && $slug2>0){
			// method GET (Get Data Detail)
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if($sisa_field){
					$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
				}else{
					$cekid = $db->query("SELECT $query_field from $tbl_name WHERE id".MODULE."='$slug2'");
					if($cekid->iAffectedRows)
						$dataWebService = $cekid->result();
					else
						$dataWebService = array('status'=>0, 'message'=>'No result to display for this query');
				}
			}
			// method PUT (Update Data)
			elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
				if($_SERVER['REQUEST_METHOD']=='PUT'){
					$dataWebService = $_PUT;
				}else{
					$dataWebService = $_POST;
					unset($dataWebService['_METHOD']);
				}
				if($dataWebService){
					$cek_field = array_diff(array_keys($dataWebService), explode(',',$master_fields));
					if($cek_field){
						$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
					}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
						$upd = $db->update($tbl_source, $dataWebService, ['id'.MODULE=>$slug2])->affectedRows();
						if($upd)
							$dataWebService = array('status'=>1, 'message'=>'Data was updated successfull');
						else
							$dataWebService = array('status'=>2, 'message'=>'No data was updated. It caused no data was changed!');
					}else{
						$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}else{
					$dataWebService = array('status'=>3, 'message'=>'No request data sent!');
				}
			}
			// method DELETE (Delete Data)
			elseif($_SERVER['REQUEST_METHOD']=='DELETE' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE')){
				if($_SERVER['REQUEST_METHOD']=='DELETE'){
					$do_del = 1;
				}
				elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE'){
					$do_del = 1;
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
				if($do_del){
					$del = $db->delete($tbl_source, ['id'.MODULE=>$slug2])->affectedRows();
					if($del)
						$dataWebService = array('status'=>1, 'message'=>'Data was deleted successfull');
					else
						$dataWebService = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}
			else{
				$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
			}
		}elseif($slug1!='id' && $slug2>0){
			// method GET (Get Data Detail)
			if($_SERVER['REQUEST_METHOD']=='GET'){
				if($sisa_field){
					$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
				}else{
					$cekid = $db->query("SELECT $query_field from $tbl_name WHERE $slug1='$slug2'");
					if($cekid->iAffectedRows)
						$dataWebService = $cekid->result();
					else
						$dataWebService = array('status'=>0, 'message'=>'No result to display for this query');
				}
			}
			// method PUT (Update Data)
			elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
				if($_SERVER['REQUEST_METHOD']=='PUT'){
					$dataWebService = $_POST;
				}else{
					$dataWebService = $_POST;
					unset($dataWebService['_METHOD']);
				}
				if($dataWebService){
					$cek_field = array_diff(array_keys($dataWebService), explode(',',$master_fields));
					if($cek_field){
						$dataWebService = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
					}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
						$upd = $db->update($tbl_source, $dataWebService, [$slug1=>$slug2])->affectedRows();
						if($upd)
							$dataWebService = array('status'=>1, 'message'=>'Data was updated successfull');
						else
							$dataWebService = array('status'=>0, 'message'=>'No data was updated. It caused no data was changed!');
					}else{
						$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}else{
					$dataWebService = array('status'=>0, 'message'=>'No request data sent!');
				}
			}
			// method DELETE (Delete Data)
			elseif($_SERVER['REQUEST_METHOD']=='DELETE' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE')){
				if($_SERVER['REQUEST_METHOD']=='DELETE'){
					$do_del = 1;
				}
				elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE'){
					$do_del = 1;
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
				if($do_del){
					$del = $db->delete($tbl_source, [$slug1=>$slug2])->affectedRows();
					if($del)
						$dataWebService = array('status'=>1, 'message'=>'Data was deleted successfull');
					else
						$dataWebService = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
				}else{
					$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}
			else{
				$dataWebService = array('status'=>0, 'message'=>'Not Acceptend method!');
			}
		}
		if($dataWebService){
			if($_GET['format']){
				$formats = explode(',', $_GET['format']);
				if($formats){
					foreach($formats as $pola){
						if(substr($pola,0,5)=='date('){
							$de = explode('--',$pola);
							$dk = str_replace(')','',$de[1]);
							$df = substr($de[0],5,strlen($de[0]));
							foreach($dataWebService as $i=>$dt){
								$dataWebService[$i][$dk] = tanggal($df, $dataWebService[$i][$dk]);
							}
						}
						if(substr($pola,0,7)=='number('){
							$de = explode('*',str_replace(array('number(',')'),'',$pola));
							$dk = $de[0];
							$dg = $de[1]>=0 ? $de[1] : 'auto';
							foreach($dataWebService as $i=>$dt){
								$dataWebService[$i][$dk] = format_angka($dataWebService[$i][$dk], $dg);
							}
						}
					}
				}
			}
		}else{
			$dataWebService = array('status'=>0, 'message'=>'The path you request is not valid. Please request a valid URLs!');
		}
	}elseif(DB_NAME && !$tbl_source){
		$dataWebService = array('status'=>0, 'message'=>'Component not exist. Please set specific component for current URL!');
	}else{
		$dataWebService = array('status'=>0, 'message'=>'Framework not configured for database connection. Please setup Database Configuration!');

	}
}


if(!$dataWebService){
	$dataWebService = array('status'=>0, 'code'=>204, 'message'=>'No Data');
}
if($ext=='xml'){
	$dataWebService = array('output'=>$dataWebService);
}
echo $fnc($dataWebService, $conf['service_prettyprint']);