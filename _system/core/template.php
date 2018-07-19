<?php
if(MODULE=='logout'){
	session_destroy();
	//if(is_file(MODULE_DIR.'index.php')) include MODULE_DIR . 'index.php';
	if($conf['site_login']){
		header('location:'.SITE_URI.'login');
	}else{
		if($_GET['module']==ADMIN_DIR){
			header('location:'.SITE_URI.ADMIN_DIR.'/login.php?logout=true');
		}else{
			header('location:'.SITE_URI);
		}
	}
	exit;
}
if(isset($_GET['module']) && $_GET['module']!='login'){
	if($_GET['ext'] && ($ext=='json' || $ext=='xml' || $ext=='xls')){
	    // web service skip require login
	}else{
	    if($_GET['module']==ADMIN_DIR){
			if(!$_SESSION['admin_uid']){
				if($slug1=='logout'){
					session_destroy();		
		    	}elseif($slug1!='login'){
					header('location:'.SITE_URI.ADMIN_DIR.'/login.php?next='.urlencode($_SERVER['REQUEST_URI']));
			    	exit;		
		    	}elseif($slug1=='login' && $_GET['logout']){
					header('location:'.SITE_URI.ADMIN_DIR.'/login.php');
					exit;
				}
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
	}elseif(MODULE=='login' && $_SESSION['user_uid']){
		header('location:'.SITE_URI);
		exit;
	}
}

if(is_file(MODULE_DIR.'data.php')){
	include MODULE_DIR . 'data.php';
}

if($_GET['module']==ADMIN_DIR){
	include SYS_CORE . 'administrator.php';
//}elseif($_GET['ext'] && ($ext=='htm' || $ext=='json' || $ext=='xml' || $ext=='xls')){
}elseif($_GET['module']=='login' && $conf['site_login']){
	if($conf['login_cek']){
		if(isset($conf['login_cek']['username']['field'])){ // login with more attribute
			$conf['login_field'] = $conf['login_cek']['username']['field'];
			$conf['login_user'] = $conf['login_cek']['username'];
			unset($conf['login_user']['field']);
			if(!$conf['login_user']['type']) $conf['login_user']['type'] = 'text';
			if(!$conf['login_user']['placeholder']) $conf['login_user']['placeholder'] = 'Username';
		}else{
			$conf['login_field'] = $conf['login_cek']['username'];
			$conf['login_user'] = array('type'=>'text', 'placeholder'=>'Username');
		}
	}
	if($_POST){
		include SYS_CORE.'userlogin.php';
	}
	include TEMPLATE_PATH . 'login.php';
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
