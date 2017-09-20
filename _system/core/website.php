<?php
$valid_web = array('htm'=>'text/html','xls'=>'application/vnd.ms-excel');

if($conf['valid_ext']){
	if(is_array($conf['valid_ext'])){
		foreach($conf['valid_ext'] as $i=>$ext){
			if($i>=0)
				//$valid_web[$ext] = 'text/html';
				$nol = 0;
			else
				$valid_web[$i] = $ext;
		}
	}else{
		$conf['valid_ext'] = str_replace(' ','',$conf['valid_ext']);
		$conf['valid_ext'] = explode(',',$conf['valid_ext']);
	}
}


if($_GET['ext'] && !in_array($ext, $conf['valid_ext'])){
	if(is_file(MODULE_PATH.$_GET['module'].'/'."$ext.$slug1.php")){
		if($valid_web[$ext]){
			header("Cache-Control:public");
			header('content-type:'.$valid_web[$ext]);
		}
		include MODULE_PATH.$_GET['module'].'/' . "$ext.$slug1.php";
	}else{
		if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
		include TEMPLATE_PATH.'404.php';
		if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
		exit;
	}
}else{
	if($conf['autoload_module']){
		if(is_file(MODULE_DIR.'/'."$slug1.php")){
			include TEMPLATE_DIR.'header.php';
			include MODULE_DIR . $slug1 . '.php';
			include TEMPLATE_DIR.'footer.php';
		}else{
			include TEMPLATE_DIR.'header.php';
			include MODULE_DIR . 'index.php';
			include TEMPLATE_DIR.'footer.php';
		}
	}else{
		include TEMPLATE_DIR.'header.php';
		include MODULE_DIR . 'index.php';
		include TEMPLATE_DIR.'footer.php';
	}
}