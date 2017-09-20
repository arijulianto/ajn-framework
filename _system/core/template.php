<?php
if(MODULE=='logout'){
	session_destroy();
	if(is_file(MODULE_DIR.'index.php')) include MODULE_DIR . 'index.php';
	header('location:'.SITE_URI);
	exit;
}

if(MODULE!='login'){
	if($_GET['ext'] && ($ext=='json' || $ext=='xml' || $ext=='xls')){
	    // web service skip require login
	}else{
	    if(MODULE!=ADMIN_DIR){
			if($conf['site_login'] && !$_SESSION['user_uid']){
				header('location:'.SITE_URI.'login?next='.urlencode($_SERVER['REQUEST_URI']));
		    	exit;			
			}
		}
	}
}

if(is_file(MODULE_DIR.'data.php')){
	include MODULE_DIR . 'data.php';
}

if($_GET['module']==ADMIN_DIR){
	include SYS_CORE . 'administrator.php';
}elseif($_GET['ext'] && ($ext=='json' || $ext=='xml')){
	header("cache-control:public");
	header('content-type:'.$valid_type[$ext]);
	header('access-control-allow-origin:*');
	include SYS_CORE . 'webservice.php';
}else{
	include SYS_CORE . 'website.php';
}