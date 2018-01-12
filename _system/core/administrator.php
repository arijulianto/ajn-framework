<?php
// Direktori dasar yang utama
define('ADMIN_PATH', BASEPATH . '_administrator/');
define('ADMIN_URI', SITE_URI . ADMIN_DIR . '/');
define('ADMIN_URL', SITE_URL . ADMIN_DIR . '/');
$subtitle = ['new'=>'Tambah Data','edit'=>'Edit Data','delete'=>'Hapus Data','activate'=>'Aktifkan Data','deactivate'=>'Nonaktifkan Data'];

// Redefine Slug
$slug1='';
if($_GET['slug']){
	$SLUG = explode('/', $_GET['slug']);
	if($SLUG[1]){
		for($i=1;$i<=count($SLUG);$i++){
			$tmp_slug['slug'.$i] = $SLUG[$i];
		}
		extract($tmp_slug);
	}
}

// Logout proses
if($_GET['slug']=='logout'){
	session_destroy();
	//header('location:'.ADMIN_URI.'index.php');
	exit;
}

// Login proses
if($_POST['user_login'] && $_POST['user_password']){
	include ADMIN_PATH.'config.php';
	// ceklogin
	$enc = array_reverse(explode('+',str_replace(' ','',$adm['login_encrypt'])));
	$user_email = trim(strtolower($_POST['user_login']));
	$user_password = $_POST['user_password'];
	if($enc){
		foreach($enc as $e){
			if($e=='md5') $user_password = md5($user_password);
			elseif($e=='base64') $user_password = base64_encode($user_password);
		}
	}
	
	$login = $db->query("SELECT ".implode(', ',array_values($adm['login_session'])).",aktif from $adm[login_source] WHERE ".$adm['login_cek'][0]."='$user_email' AND ".$adm['login_cek'][1]."='$user_password'")->result();
	if($login){
		if($login['aktif']){
			foreach($adm['login_session'] as $name=>$field){
				$_SESSION['admin_'.$name] = $login[$field];
			}
			if($adm['login_recent']){
				$now = date('Y-m-d H:i:s');
				$ip = $_SERVER['REMOTE_ADDR'];
				$db->query("UPDATE $adm[login_source] set lastlogin_time=nowlogin_time,lastlogin_ip=nowlogin_ip, nowlogin_time='$now',nowlogin_ip='$ip'");
			}
			header('location:'.($_REQUEST['next'] ? $_REQUEST['next'] : SITE_URI));
			exit;
		}else{
			$msg['failed'] = 'Akun tidak aktif.<br />Silahkan hubungi Administrator untuk mengaktifkan akun Anda!';
		}
	}else{
		$msg['warning'] = 'Email atau Password salah.<br />Silahkan periksa ulang!';
	}
}


// Cek Module exist
if(MODULE=='index' || MODULE=='home' || is_file(ADMIN_PATH.'module/'.MODULE.'.php')){
	$has_module = 1;
}

// Load Module Config
if($_SESSION['admin_uid']){
	if(MODULE=='index' || MODULE=='home'){
		include ADMIN_PATH.'header.php';
		include ADMIN_PATH.'module/home/index.php';
		include ADMIN_PATH.'footer.php';
	}elseif(is_file(ADMIN_PATH.'module/'.MODULE.'/index.php')){
		include ADMIN_PATH.'header.php';
		include ADMIN_PATH.'module/'.MODULE.'/index.php';
		// parser
		if($module['mode']){
			$module['mode'] = explode(',', $module['mode']);
		}
		if($module['vars']){
			foreach($module['vars'] as $kk=>$vv){
				$ev = explode('||',$vv);
				foreach($ev as $v){
					$ev2 = explode('=>', $v);
					if($ev2[1])
						$module['vars']['data'][$kk][$ev2[0]] = $ev2[1];
					else
						$module['vars']['data'][$kk][''] = $ev2[1];
				}
			}
			if($module['vars']['data']) $module['vars'] = $module['vars']['data'];
		}
		include ADMIN_PATH . 'footer.php';
	}else{
		$module['module'] = 'Error 404 :: Halaman Tidak Ditemukan';
		include ADMIN_PATH.'header.php';
		echo '<div class="page404"><h1>Error 404 - Halaman Tidak Ditemukan</h1><p>Halaman yang Anda tuju tidak ditemukan. Silahkan periksa ulang URL yang Anda maksud!</p></div>';
	}
}else{
	include ADMIN_PATH.'login.php';
}

