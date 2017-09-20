<?php
$fnc = 'array2'.$ext;
if($ext=='xml'){
	echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
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
			$data_field = preg_replace('/ as (\w+),/', ',', $query_field);
		}else{
			$query_field = $data_field = $master_fields;
		}
		// failed field
		$sisa_field = array_diff(explode(',',$data_field), explode(',',$master_fields));
	}
}


	if(is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
		include MODULE_PATH.$_GET['module'].'/' . "$ext.$slug1.php";
		exit;
	}else{
		if(DB_NAME && $tbl_source){
			$strsql = '';
			// /data.format
			if($slug1=='data'){
				// method GET (Get Data Detail)
				if($_SERVER['REQUEST_METHOD']=='GET'){
					if($sisa_field){
						$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
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
						$data = $db->query($strsql);
						if($db->aResults[0])
							$data = $data->results();
						else
							$data = array('status'=>0, 'message'=>'No result to display for this query');
					}
				// method PUT (Update Data)
				}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
					if($_SERVER['REQUEST_METHOD']=='PUT'){
						$data = $_PUT;
					}else{
						$data = $_POST;
						unset($data['_METHOD']);
					}
					if($data){
						$cek_field = array_diff(array_keys($data), explode(',',$master_fields));
						if($cek_field){
							$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
						}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
							$upd = $db->update($tbl_source, $data, ['id'.MODULE=>$slug2])->affectedRows();
							if($upd)
								$data = array('status'=>1, 'message'=>'Data was updated successfull');
							else
								$data = array('status'=>2, 'message'=>'No data was updated. It caused no data was changed!');
						}else{
							$data = array('status'=>0, 'message'=>'Not Acceptend method!');
						}
					}else{
						$data = array('status'=>3, 'message'=>'No request data sent!');
					}
				// method DELETE (Delete Data)
				}elseif($_SERVER['REQUEST_METHOD']=='DELETE' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE')){
					if($_SERVER['REQUEST_METHOD']=='DELETE'){
						$do_del = 1;
					}
					elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='DELETE'){
						$do_del = 1;
					}else{
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
					if($do_del){
						$del = $db->delete($tbl_source, ['id'.MODULE=>$slug2])->affectedRows();
						if($del)
							$data = array('status'=>1, 'message'=>'Data was deleted successfull');
						else
							$data = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
					}else{
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}else{
					$data = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}
			// method POST (Input Data)
			elseif($slug1=='post'){
				if($_SERVER['REQUEST_METHOD']=='POST'){
					if($_POST){
						$cek_field = array_diff(array_keys($data), explode(',',$master_fields));
						if($cek_field){
							$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
						}else{
							$insert = $db->insert($tbl_source, $_POST)->getLastInsertId();
							if($insert)
								$data = array('status'=>1, 'message'=>'Data was saved successfull');
							else
								$data = array('status'=>0, 'message'=>'No data was saved');
						}
					}else{
						$data = array('status'=>0, 'message'=>'No post data sent!');
					}
				}else{
					$data = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}
			// method GET PUT DELETE
			elseif($slug1=='id' && $slug2>0){
				// method GET (Get Data Detail)
				if($_SERVER['REQUEST_METHOD']=='GET'){
					if($sisa_field){
						$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
					}else{
						$cekid = $db->query("SELECT $query_field from $tbl_name WHERE id".MODULE."='$slug2'");
						if($cekid->iAffectedRows)
							$data = $cekid->result();
						else
							$data = array('status'=>0, 'message'=>'No result to display for this query');
					}
				}
				// method PUT (Update Data)
				elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
					if($_SERVER['REQUEST_METHOD']=='PUT'){
						$data = $_PUT;
					}else{
						$data = $_POST;
						unset($data['_METHOD']);
					}
					if($data){
						$cek_field = array_diff(array_keys($data), explode(',',$master_fields));
						if($cek_field){
							$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
						}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
							$upd = $db->update($tbl_source, $data, ['id'.MODULE=>$slug2])->affectedRows();
							if($upd)
								$data = array('status'=>1, 'message'=>'Data was updated successfull');
							else
								$data = array('status'=>2, 'message'=>'No data was updated. It caused no data was changed!');
						}else{
							$data = array('status'=>0, 'message'=>'Not Acceptend method!');
						}
					}else{
						$data = array('status'=>3, 'message'=>'No request data sent!');
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
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
					if($do_del){
						$del = $db->delete($tbl_source, ['id'.MODULE=>$slug2])->affectedRows();
						if($del)
							$data = array('status'=>1, 'message'=>'Data was deleted successfull');
						else
							$data = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
					}else{
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}
				else{
					$data = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}elseif($slug1!='id' && $slug2>0){
				// method GET (Get Data Detail)
				if($_SERVER['REQUEST_METHOD']=='GET'){
					if($sisa_field){
						$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$sisa_field));
					}else{
						$cekid = $db->query("SELECT $query_field from $tbl_name WHERE $slug1='$slug2'");
						if($cekid->iAffectedRows)
							$data = $cekid->result();
						else
							$data = array('status'=>0, 'message'=>'No result to display for this query');
					}
				}
				// method PUT (Update Data)
				elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
					if($_SERVER['REQUEST_METHOD']=='PUT'){
						$data = $_POST;
					}else{
						$data = $_POST;
						unset($data['_METHOD']);
					}
					if($data){
						$cek_field = array_diff(array_keys($data), explode(',',$master_fields));
						if($cek_field){
							$data = array('status'=>0, 'message'=>'Unknown field name. Please check these field name are correct : '.implode(', ',$cek_field));
						}elseif($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
							$upd = $db->update($tbl_source, $data, [$slug1=>$slug2])->affectedRows();
							if($upd)
								$data = array('status'=>1, 'message'=>'Data was updated successfull');
							else
								$data = array('status'=>0, 'message'=>'No data was updated. It caused no data was changed!');
						}else{
							$data = array('status'=>0, 'message'=>'Not Acceptend method!');
						}
					}else{
						$data = array('status'=>0, 'message'=>'No request data sent!');
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
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
					if($do_del){
						$del = $db->delete($tbl_source, [$slug1=>$slug2])->affectedRows();
						if($del)
							$data = array('status'=>1, 'message'=>'Data was deleted successfull');
						else
							$data = array('status'=>0, 'message'=>'No data was deleted. Please try again!');
					}else{
						$data = array('status'=>0, 'message'=>'Not Acceptend method!');
					}
				}
				else{
					$data = array('status'=>0, 'message'=>'Not Acceptend method!');
				}
			}
			if($data){
				if($_GET['format']){
					$formats = explode(',', $_GET['format']);
					if($formats){
						foreach($formats as $pola){
							if(substr($pola,0,5)=='date('){
								$de = explode('--',$pola);
								$dk = str_replace(')','',$de[1]);
								$df = substr($de[0],5,strlen($de[0]));
								foreach($data as $i=>$dt){
									$data[$i][$dk] = tanggal($df, $data[$i][$dk]);
								}
							}
							if(substr($pola,0,7)=='number('){
								$de = explode('*',str_replace(array('number(',')'),'',$pola));
								$dk = $de[0];
								$dg = $de[1]>=0 ? $de[1] : 'auto';
								foreach($data as $i=>$dt){
									$data[$i][$dk] = format_angka($data[$i][$dk], $dg);
								}
							}
						}
					}
				}
			}else{
				$data = array('status'=>0, 'message'=>'The path you request is not valid. Please request a valid URLs!');
			}
		}elseif(DB_NAME && !$tbl_source){
			$data = array('status'=>0, 'message'=>'Component not exist. Please set specific component for current URL!');
		}else{
			$data = array('status'=>0, 'message'=>'Framework not configured for database connection. Please setup Database Configuration!');

		}
	}

header('content-type:'.$valid_service[$ext]);
if($ext=='xml'){
	$data = ['output'=>$data];
}
echo $fnc($data);