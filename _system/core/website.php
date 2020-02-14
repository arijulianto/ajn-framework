<?php
$valid_web = array('htm'=>'text/html','xls'=>'application/vnd.ms-excel');

if($conf['autoload_module']){
	if(isset($slug1) && is_file(MODULE_DIR.'/'."$slug1.php")){
		include TEMPLATE_DIR.'header.php';
		include MODULE_DIR . $slug1 . '.php';
		include TEMPLATE_DIR.'footer.php';
	}else{
		if(is_dir(MODULE_PATH.MODULE) && is_file(MODULE_PATH.MODULE.'/index.php')){
			$fc = file_get_contents(MODULE_PATH.MODULE.'/index.php');
			$a = explode('class '.ucfirst(MODULE), $fc);
			if(isset($a[1])){
				include SYS_CORE . 'framework.oop.php';
				include MODULE_DIR . 'index.php';
				$className = ucfirst(MODULE);
				$AJN = new $className;
				$AJN->Run();
			}else{
				include TEMPLATE_DIR.'header.php';
				include MODULE_DIR . 'index.php';
				include TEMPLATE_DIR.'footer.php';
			}
		}else{
			if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
			include TEMPLATE_PATH.'404.php';
			if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
			exit;
		}
	}
}else{
	if(is_dir(MODULE_PATH.$_GET['module'])){
		include TEMPLATE_DIR.'header.php';
		include MODULE_DIR . 'index.php';
		include TEMPLATE_DIR.'footer.php';
	}else{
		if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
		include TEMPLATE_PATH.'404.php';
		if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
		exit;
	}
}
