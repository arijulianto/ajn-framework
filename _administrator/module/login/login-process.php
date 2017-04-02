<?php
$login_usermail = trim(strtolower($_POST['user_login']));
$password = md5(base64_encode($_POST['user_passwd']));
$next_url = $_POST['next'] ? urldecode($_POST['next']) : ADMIN_URI.'index.php';
$arr_posisi = array(1=>'Developer',2=>'Administrator',3=>'Kontributor');

$login = $db->query("SELECT * from tbl_user where (user_login='$login_usermail' OR user_email='$login_usermail') AND user_password='$password' AND posisi<10")->result();
if($login['iduser']>0){
	if($login['aktif']=='0'){
		$msg['warning'] = 'Maaf... Login ditolak.<br />Akun Anda belum aktif atau kena blokir. Silahkan hubungi Administrator untuk mengaktifkan akun Anda!';
	}else{
		$_SESSION['admin_uid'] = $login['iduser'];
		$_SESSION['admin_nama'] = $login['nama'];
		$_SESSION['admin_posisi'] = $login['posisi'];
		$_SESSION['admin_posisi_nama'] = $arr_posisi[$login['posisi']];
		$_SESSION['admin_login_time'] = date('Y-m-d H:i:s');

		header('location:'.$next_url);
		exit;
	}
}else{
	$msg['warning'] = 'Username/email atau password salah. silahkan periksa lagi';
}