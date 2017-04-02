<?php
// Logout
if(MODULE=='logout'){
	include MODULE_DIR . 'index.php';
	exit;
}


// Login cek
if(!isset($_SESSION['admin_uid'])){
	$next_url = urlencode($_SERVER['REQUEST_URI']);
	if(MODULE!='login' && MODULE!='home'){
		header('location:'.ADMIN_URI.'login.php?next='.$next_url);
		exit;
	}
}




if(MODULE=='login'){
	if(isset($_POST['login'])){
		include MODULE_DIR . 'login-process.php';
	}
}


if(is_file(MODULE_DIR.'data.php'))
	include MODULE_DIR.'data.php';



include ADMIN_PATH.'header.php';
include MODULE_DIR.'index.php';
include ADMIN_PATH.'footer.php';
