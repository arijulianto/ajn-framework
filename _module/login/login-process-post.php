<?php
// filter var
$username = trim($_POST['user']);
$userfiltered = strtolower(str_replace(array('-','_','.',' '),'',$username));
$emailfiltered = strtolower($username);
$password = md5($_POST['pass']);
$now = date('Y-m-d H:i:s');
$myip = $_SERVER['REMOTE_ADDR'];
$next_url = urldecode($_POST['next']);

	
	
/** bikin KONDISI SQL untuk pilih input USERNAME/EMAIL/No HP **/
// login dengan EMAIL
if(strpos($username,'@'))
	$where_user = "user_email='$emailfiltered'";
// login dengan USERNAME (default)
else
	$where_user = " userfiltered='$userfiltered'";

$where_password = "AND user_password='$password'";
$next = urldecode($_POST['next']);


$str_sql = "SELECT idlogin,idgroupuser,nama,username,aktif FROM {$prefix}tbl_login where $where_user $where_password";
//echo $str_sql; exit;
$data = $db->query($str_sql)->result();
// login sudah benar
if($data['idlogin']>0){
	// user yang login dipastikan akunnya aktif
	if($data['aktif']==1){
		/* PHP Session */
		$_SESSION['ais_uid'] = $data['idlogin'];
		$_SESSION['ais_guid'] = $data['idgroupuser'];
		$_SESSION['ais_user'] = $data['username'];
		$_SESSION['ais_nama'] = $data['nama'];

		if($_POST['rememberme']){
			setcookie('__ais_userlog', str2hex('u'.str2num('uid:'.$data['idlogin'])).'-'.str2hex('a'.str2num('ip:'.$_SERVER['REMOTE_ADDR'])).'-'.md5($_SERVER['HTTP_USER_AGENT']), time()+31536000, '/'.(SITE_DIR ? SITE_DIR.'/' : ''));
		}

		$db->query("UPDATE {$prefix}tbl_login set lastlogin_tgl=nowlogin_tgl,nowlogin_tgl='$now',lastlogin_ip=nowlogin_ip,nowlogin_ip='$myip' where idlogin='$data[idlogin]'");
		
		header('location:'.$next_url);
		exit;
	}
	// user yang login akun tidak akrif
	else{
		$msg['warning'] = 'Akun Tidak Aktif</strong>.... Silahkan hubungi Administartor!';
	}
}
// login gagal (salah username/email/password
else{
	$msg['warning'] = '<strong>Login gagal</strong>... Username atau password salah!';
}