<?php
if($_POST){
	$proses = 1;
	$cek = $db->query("SELECT user_password from tma_admin WHERE idadmin='$_SESSION[admin_uid]'")->result();
	if(md5(md5(base64_encode($_POST['password_now'])))!=$cek['user_password']){
		$msg['warning'] = 'Password yang Anda masukkan salah!';
		$proses = 0;
	}else{
		if($_POST['password_now']==$_POST['password_new']){
			$msg['warning'] = 'Harap pilih password baru untuk mengganti!';
			$proses = 0;
		}elseif($_POST['password_new']!=$_POST['password_new2']){
			$msg['warning'] = 'Password konfirmasi tidak sama!';
			$proses = 0;
		}
	}

	if($proses){
		$data_save = array();
		$data_save['user_password'] = md5(md5(base64_encode($_POST['password_new'])));
		$save = $db->update('tma_admin', $data_save, array('idadmin'=>$_SESSION['admin_uid']))->affectedRows();
		if($save)
			$msg['sukses'] = 'Password berhasil diubah';
		else
			$msg['warning'] = 'Password gagal diganti. Silahkan periksa ulang!';
	}

	if($msg['sukses'])
		echo '<p class="alert alert-success">',$msg['sukses'],'</p>';
	elseif($msg['warning'])
		echo '<p class="alert alert-warning">',$msg['warning'],'</p>';
}
?>
<table class="table-form">
<tr>
	<td style="min-width:180px"><label>Password Sekarang</label></td>
	<td><input type="password" name="password_now" size="40" placeholder="Password Saat Ini" required /></td>
</tr>
<tr>
	<td><label>Password Baru</label></td>
	<td><input type="password" name="password_new" size="40" placeholder="Password Baru" required /></td>
</tr>
<tr>
	<td><label>Password Baru Lagi</label></td>
	<td><input type="password" name="password_new2" size="40" placeholder="Konfirmasi Password Baru" required /></td>
</tr>
</table><br />