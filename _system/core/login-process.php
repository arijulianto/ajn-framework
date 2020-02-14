<?php
$enc = array_reverse(explode('+',str_replace(' ','',$conf['login_encrypt'])));
$user_email = trim(strtolower($_POST[$conf['login_name']['name']]));
$password = $_POST['password'];
if($enc){
	foreach($enc as $e){
		if($e=='md5') $password = md5($password);
		elseif($e=='base64') $password = base64_encode($password);
	}
}

$login = $db->query("SELECT ".implode(', ',array_values($conf['login_session'])).",aktif from $conf[login_source] WHERE $cek_db[0]='$user_email' AND $cek_db[1]='$password'")->result();
if($login){
	if($login['aktif']){
		foreach($conf['login_session'] as $name=>$field){
			$_SESSION['user_'.$name] = $login[$field];
		}
		if($conf['login_recent']){
			$now = date('Y-m-d H:i:s');
			$ip = $_SERVER['REMOTE_ADDR'];
			$db->query("UPDATE $conf[login_source] set lastlogin_tgl=nowlogin_tgl,lastlogin_ip=nowlogin_ip, nowlogin_tgl='$now',nowlogin_ip='$ip' WHERE iduser='$login[iduser]'");
		}
		header('location:'.($_REQUEST['next'] ? urldecode($_REQUEST['next']) : SITE_URI));
		exit;
	}else{
		$msg['failed'] = 'Akun tidak aktif.<br />Silahkan hubungi Administrator untuk mengaktifkan akun Anda!';
	}
}else{
	$msg['warning'] = $conf['login_name']['placeholder'].' atau Password salah.<br />Silahkan periksa ulang!';
}