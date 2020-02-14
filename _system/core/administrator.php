<?php
// Direktori dasar yang utama
define('ADMIN_PATH', BASEPATH . '_administrator/');
define('ADMIN_URI', SITE_URI . ADMIN_DIR . '/');
define('ADMIN_URL', SITE_URL . ADMIN_DIR . '/');


include ADMIN_PATH.'config.php';

$limit   = isset($setting['paging_admin']) ? $setting['paging_admin'] : $conf['default_paging'];
$halaman = isset($_GET['page']) ? $_GET['page'] : 1;
if(empty($halaman)){
    $start = 0;
    $halaman = 1;
    $no = 1;
}else{
	$start = ($halaman-1) * $limit;
	$no = $start + 1;
}

// Redefine Slug
$slug1='';
if(isset($_GET['slug'])){
	$SLUG = explode('/', $_GET['slug']);
	if(isset($SLUG[1])){
		for($i=1;$i<=count($SLUG)-1;$i++){
			$tmp_slug['slug'.$i] = $SLUG[$i];
		}
		extract($tmp_slug);
	}
}


// Logout proses
if(isset($_GET['slug']) && $_GET['slug']=='logout'){
	if(isset($_COOKIE['__usr_atdn'])){
		setcookie('__usr_atdn', "", time()-3600, ADMIN_URI);
	}
	header('location:'.ADMIN_URI.'/login.php');
	exit;
}


// Login proses
if(isset($_POST['user_login']) && isset($_POST['user_password'])){
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
	
	if(is_array($adm['login_source'])){
		if($user_email==trim(strtolower($adm['login_source']['username'])) && $_POST['user_password']==$adm['login_source']['password']){
			foreach($adm['login_session'] as $name=>$value){
				$_SESSION['admin_'.$name] = $value;
			}
			if($adm['login_recent']){
					$now = date('Y-m-d H:i:s');
					$_SESSION['admin_login'] = $now;
				}
			header('location:'.(isset($_REQUEST['next']) ? urldecode($_REQUEST['next']) : ADMIN_URI));
		}else{
			$msg['warning'] = 'Email atau Password salah.<br />Silahkan periksa ulang!';
		}
	}else{
		$login = $db->query("SELECT ".implode(', ',array_values($adm['login_session'])).",aktif from $adm[login_source] WHERE ".$adm['login_cek'][0]."='$user_email' AND ".$adm['login_cek'][1]."='$user_password'")->result();
		if($login){
			if($login['aktif']){
				foreach($adm['login_session'] as $name=>$field){
					$_SESSION['admin_'.$name] = $login[$field];
				}
				if(isset($adm['login_recent']) && $adm['login_recent']){
					$now = date('Y-m-d H:i:s');
					$ip = $_SERVER['REMOTE_ADDR'];
					$db->query("UPDATE $adm[login_source] set lastlogin_tgl=nowlogin_tgl,lastlogin_ip=nowlogin_ip, nowlogin_tgl='$now',nowlogin_ip='$ip' WHERE $adm[login_id]='".$login[$adm['login_id']]."'");
					$_SESSION['admin_login'] = $now;
				}
				header('location:'.(isset($_REQUEST['next']) ? urldecode($_REQUEST['next']) : ADMIN_URI));
				exit;
			}else{
				$msg['failed'] = 'Akun tidak aktif.<br />Silahkan hubungi Administrator untuk mengaktifkan akun Anda!';
			}
		}else{
			$msg['warning'] = 'Email atau Password salah.<br />Silahkan periksa ulang!';
		}
	}
}


// Cek Module exist
if(MODULE=='index' || MODULE=='home' || is_file(ADMIN_PATH.'module/'.MODULE.'/index.php')){
	$has_module = 1;

	if(is_file(ADMIN_PATH.'module/'.MODULE.'/data.php')){
		include ADMIN_PATH.'module/'.MODULE.'/data.php';
	}

	if(isset($_GET['ext']) && ($_GET['ext']=='json' || $_GET['ext']=='xml')){
		$func = 'array2'.$_GET['ext'];
		if($ext=='xml'){
			echo '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
		}
		$valid_type = array('htm'=>'text/html','json'=>'application/json', 'xml'=>'text/xml');

		header("cache-control:public");
		header('access-control-allow-origin:'.$_SERVER['HTTP_HOST']);
		header('content-type:'.$valid_type[$_GET['ext']]);
		if(is_file(ADMIN_PATH.'module/'.MODULE.'/'.$_GET['ext'].'.'.$slug1.'.php')){
			include ADMIN_PATH.'module/'.MODULE.'/'.$_GET['ext'].'.'.$slug1.'.php';
		}else{
			$output = array('status'=>0, 'code'=>'0x00', 'message'=>'Invalid path request. Please request specific URL!');
			if($ext=='xml'){
				$output['status'] = '0';
				$output = array('request'=>$output);
			}
			echo $func($output);
		}
		exit;
	}elseif(isset($_GET['ext']) && $_GET['ext']=='htm'){
		if(is_file(ADMIN_PATH.'module/'.MODULE.'/'.$_GET['ext'].'.'.$slug1.'.php')){
			include ADMIN_PATH.'module/'.MODULE.'/'.$_GET['ext'].'.'.$slug1.'.php';
		}else{
			echo '<h1>Error 404 - Halaman Tidak Ditemukan</h1>';
		}
		exit;
	}
}

// Load Module Config
if(isset($_SESSION['admin_uid'])){
	if(is_file(ADMIN_PATH.'module/'.MODULE.'/_config.php')){
		include ADMIN_PATH.'module/'.MODULE.'/_config.php';
		if($slug1=='new')
			$site['title'] = $module['input'].' | '.$module['module'];
		elseif($slug1=='edit')
			$site['title'] = 'Edit '.$module['module'];
		else
			$site['title'] = $module['module'];

		// parse config
		$mode = str_replace(array(', ',' ,',' , '), ',', $module['mode']);
		$mode = str_replace(' ','',strtolower($mode));
		$mode = explode(',', $mode);
		if(in_array('activate', $mode)) $mode[] = 'deactivate';
		$fields = str_replace(array(', ',' ,',' , '), ',', $module['fields']);
		$fields = str_replace(' ','',strtolower($fields));
		$fields = explode(',', $fields);
		
		if($module['input_required']){
			$input_required = str_replace([' , ',', ',' ,'], ',', $module['input_required']);
			$input_required = explode(',', $input_required);
		}

		if($module['edit_required']){
			$edit_required = str_replace([' , ',', ',' ,'], ',', $module['edit_required']);
			$edit_required = explode(',', $edit_required);
		}

		$forms = array();
		$uploads = array();
		foreach($module['form'] as $field=>$fld){
			$ff = explode('::', $fld);
			$tt = explode('(', $ff[1]);
			$prop = explode('{', $tt[1]);
			if(isset($prop[1])){
				$prop = str_replace('}','', substr_replace($prop[1], '', -1, 1));
				$prop = explode('||', $prop);
				$props = array();
				foreach($prop as $pr){
					$e = explode('=>', $pr);
					$props[$e[0]] = $e[1];
				}
				if($props['path']) $props['path'] = trim($props['path'],'/');// else $props['path'] = 'images';
				if($props['url'])
					$props['url'] = trim($props['url'],'/').'/';
				
			}else{
				$props = array();
				//unset($props);
			}
			$attr = str_replace(')','',$tt[1]);
			$attr = explode('{', $attr)[0];

			if($slug1=='new' && $input_required){
				if(in_array($field, $input_required)) $attr = $attr.' required';
			}elseif($slug1=='edit' && $edit_required){
				if(in_array($field, $edit_required)) $attr = $attr.' required';
			}

			
			$forms[$field] = array('label'=>$ff[0], 'type'=>$tt[0], 'name'=>$field, 'prop'=>$props, 'attr'=>$attr);
			if($tt[0]=='file'){
				$forms[$field]['prop']['path'] = MEDIA_PATH.$props['path'].'/';
				$forms[$field]['prop']['uri'] = MEDIA_URI.$props['path'].'/';
				$uploads[$field] = MEDIA_PATH.$props['path'].'/';
			}
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


		include ADMIN_PATH.'header.php';
		echo "<h1 class=\"title\">$module[module]</h1>\n<div class=\"content\">\n";
		if(isset($db)){
			if(in_array($slug1, $mode)){
				if($slug1=='new' || $slug1=='edit'){
					include SYS_CORE.'administrator.form.php';
				}elseif($slug1=='activate' || $slug1=='deactivate' || $slug1=='delete'){
					include SYS_CORE.'administrator.actions.php';
					include SYS_CORE.'administrator.list.php';
				}else{
					include SYS_CORE.'administrator.list.php';
				}
			}else{
				include SYS_CORE.'administrator.list.php';
			}
		}else{
			echo '<h3>Koneksi ke database tidak ditemukan!</h3><p>Admin Konfig memerlukan koneksi ke database, silahakn setup terlebih dahulu!</p>';
		}
		echo "</div>\n";
		include ADMIN_PATH.'footer.php';
	}else{
		if(MODULE=='media' && ($adm['menu_media'] || !is_dir(ADMIN_PATH.'module/media'))){
			$site['title'] = 'Media Manager';
			include ADMIN_PATH.'header.php';
			include SYS_CORE.'administrator.media.php';
			include ADMIN_PATH.'footer.php';
		}elseif(MODULE=='index' || MODULE=='home'){
			include ADMIN_PATH.'header.php';
			include ADMIN_PATH.'module/home/index.php';
			include ADMIN_PATH.'footer.php';
		}elseif(is_file(ADMIN_PATH.'module/'.MODULE.'/index.php')){
			include ADMIN_PATH.'header.php';
			include ADMIN_PATH.'module/'.MODULE.'/index.php';
			include ADMIN_PATH . 'footer.php';
		}else{
			$site['title'] = 'Error 404 :: Halaman Tidak Ditemukan';
			include ADMIN_PATH.'header.php';
			echo '<h1 class="title">Error 404 - Halaman Tidak Ditemukan</h1><div class="pad"><p>Halaman yang Anda tuju tidak ditemukan. Silahkan periksa ulang URL yang Anda maksud!</p></div>';
			include ADMIN_PATH.'footer.php';
		}
	}
}else{
	include ADMIN_PATH.'login.php';
}

