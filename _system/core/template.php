<?php
if(MODULE=='logout'){
	session_destroy();
	//if(is_file(MODULE_DIR.'index.php')) include MODULE_DIR . 'index.php';
	if($conf['site_login'])
		header('location:'.SITE_URI.'login');
	else
		header('location:'.SITE_URI);
	exit;
}
if(isset($_GET['module']) && $_GET['module']!='login'){
	if($_GET['ext'] && ($ext=='json' || $ext=='xml' || $ext=='xls')){
	    // web service skip require login
	}else{
	    if($_GET['module']==ADMIN_DIR){
			if(!$_SESSION['admin_uid']){
				header('location:'.SITE_URI.ADMIN_DIR.'login?next='.urlencode($_SERVER['REQUEST_URI']));
		    	exit;			
			}
		}elseif($_GET['module']!=ADMIN_DIR){
			if($conf['site_login'] && !$_SESSION['user_uid']){
				header('location:'.SITE_URI.'login?next='.urlencode($_SERVER['REQUEST_URI']));
		    	exit;			
			}
		}
	}
}else{
	if($conf['site_login'] && !$_SESSION['user_uid']){
		if(MODULE!='login'){
			header('location:'.SITE_URI.'login?next='.urlencode($_SERVER['REQUEST_URI']));
	    	exit;			
	    }
	}
}

if(is_file(MODULE_DIR.'data.php')){
	include MODULE_DIR . 'data.php';
}

if($_GET['module']=='login' && $conf['site_login']){
	$cek_db = array_keys($conf['login_cek']);
	$login_field = array_values($conf['login_session']);
	if($_POST){
		include SYS_CORE.'login-process.php';
	}
	include TEMPLATE_DIR . 'login.php';
}elseif($_GET['module']==ADMIN_DIR){
	include SYS_CORE . 'administrator.php';
//}elseif($_GET['ext'] && ($ext=='htm' || $ext=='json' || $ext=='xml' || $ext=='xls')){
}elseif($_GET['ext']){
	if($ext=='json' || $ext=='xml' || $ext=='xls'){
		include SYS_CORE . 'webservice.php';
	}else{
		$skip_ext = explode(',', $conf['skip_ext']);
		if(in_array($ext, $skip_ext)){
			include SYS_CORE . 'website.php';
		}else{
			if(is_file(MODULE_DIR.$ext.'.'.$slug1.'.php')){
				include MODULE_DIR . $ext.'.'.$slug1.'.php';
			}else{
				if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
				include TEMPLATE_PATH.'404.php';
				if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
				exit;
			}
		}
	}
}else{
	include SYS_CORE . 'website.php';
}