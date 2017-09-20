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
	header('location:'.ADMIN_URI.'index.php');
	exit;
}

// Login proses
if($_POST['user_login'] && $_POST['user_password']){
	include ADMIN_PATH.'login-process.php';
}


// Cek Module exist
if(MODULE=='index' || MODULE=='home' || is_file(ADMIN_PATH.'module/'.MODULE.'.php')){
	$has_module = 1;
}



// Load Module Config
if($_SESSION['admin_uid']){
	if(MODULE=='index' || MODULE=='home'){
		include ADMIN_PATH.'header.php';
		include ADMIN_PATH.'home.php';
	}elseif(is_file(ADMIN_PATH.'module/'.MODULE.'.php')){
		include ADMIN_PATH.'module/'.MODULE.'.php';
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
		include ADMIN_PATH . 'header.php';
		include ADMIN_PATH.'administrator.php';
	}else{
		$module['module'] = 'Error 404 :: Halaman Tidak Ditemukan';
		include ADMIN_PATH.'header.php';
		echo '<div class="page404"><h1>Error 404 - Halaman Tidak Ditemukan</h1><p>Halaman yang Anda tuju tidak ditemukan. Silahkan periksa ulang URL yang Anda maksud!</p></div>';
	}
}else{
	include ADMIN_PATH.'header.php';
	include ADMIN_PATH.'login.php';
}

include ADMIN_PATH . 'footer.php';
