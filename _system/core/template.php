<?php
if(MODULE=='logout'){
	include MODULE_DIR . 'index.php';
	exit;
}

if(is_file(MODULE_DIR.'data.php')){
	include MODULE_DIR . 'data.php';
}


if($_GET['ext'] && $_GET['ext']!='html'){
	$ext = trim(strtolower($_GET['ext']),'.');
	$valid_type = array('htm'=>'text/html','json'=>'application/json','xml'=>'text/xml','xls'=>'application/vnd.ms-excel');
	if($valid_type[$ext]){
		header("Cache-Control:public");
		header('content-type:'.$valid_type[$ext]);
		if(is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
					include MODULE_PATH.$_GET['module'].'/' . "$ext.$slug1.php";
		}else{
			$data = array('status'=>'Error', 'message'=>'The path you request is not valid. Please request a valid URL!');
			header('content-type:application/json');
			echo json_encode($data);
		}
	}else{
		$data = array('status'=>'Error', 'message'=>'The path you request is not valid. Please request a valid URL!');
		echo json_encode($data);
	}
}else{
	include TEMPLATE_DIR.'header.php';
	if($conf['autoload_module'] && $slug1)
		include MODULE_DIR . $slug1 . '.php';
	else
		include MODULE_DIR . 'index.php';
	include TEMPLATE_DIR.'footer.php';
}
