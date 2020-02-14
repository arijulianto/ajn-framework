<?php
$level = array(1=>'Kontributor','Editor','Administrator',99=>'Developer');
if($_POST){
	$proses = 1;
	if(strtolower($_POST['email'])!=strtolower($_POST['ori_email'])){
		$cek = $db->query("SELECT idadmin from tbl_admin WHERE user_email='$_POST[email]' AND idadmin!='$_SESSION[user_uid]'")->result();
		if($cek){
			$msg['warning'] = 'Email sudah digunakan user lain. Harap pilih email yang belum digunakan!';
			$proses = 0;
		}
	}

	if($proses){
		$data_save = array();
		$data_save['nama'] = $_POST['nama'];
		$data_save['user_email'] = $_POST['email'];
		$data_save['gender'] = $_POST['gender'];
		$data_save['alamat'] = $_POST['alamat'];
		$save = $db->update('tbl_admin', $data_save, array('idadmin'=>$_SESSION['admin_uid']))->affectedRows();
		if($save)
			$msg['sukses'] = 'Profil berhasil diperbaharui';
		else
			$msg['warning'] = 'Profil gagal diperbaharui atau tidak ada data yang diubah. Silahkan periksa ulang!';
	}

	if($msg['sukses'])
		echo '<p class="alert alert-success">',$msg['sukses'],'</p>';
	elseif($msg['warning'])
		echo '<p class="alert alert-warning">',$msg['warning'],'</p>';
}

$data = $db->query("SELECT * from tbl_admin WHERE idadmin='$_SESSION[admin_uid]'")->result();
?>
<table class="table-form" width="100%">
<tr>
	<td width="180"><label>Nama Lengkap</label></td>
	<td><input type="text" name="nama" class="input-block" value="<?php echo $data['nama'] ?>" placeholder="Nama Lengkap" required autofocus /></td>
</tr>
<tr>
	<td><label>Email</label></td>
	<td><input type="text" name="email" class="input-block" value="<?php echo $data['user_email'] ?>" placeholder="Email" required autofocus /><input type="hidden" name="ori_email" value="<?php echo $data['user_email'] ?>" /></td>
</tr>
<tr>
	<td><label>Jenis Kelamin</label></td>
	<td>
		<label><input type="radio" name="gender" value="L"<?php echo $data['gender']=='L' ? ' checked' : '' ?> required /> Laki-laki</label>
		<label><input type="radio" name="gender" value="P"<?php echo $data['gender']=='P' ? ' checked' : '' ?> required /> Perempuan</label>
	</td>
</tr>
<tr>
	<td><label>Alamat</label></td>
	<td><textarea name="alamat" cols="42" rows="3" class="input-block" placeholder="Alamat Lengkap"><?php echo $data['alamat'] ?></textarea></td>
</tr>
<tr>
	<td><label>Level</label></td>
	<td><label class="input disabled input-block"><?php echo $level[$data['levelnum']] ?></label></td>
</tr>
<?php if($adm['login_recent']){ ?>
<tr>
	<td><label>Terakhir Login</label></td>
	<td><label class="input disabled input-block"><?php echo durasi($data['nowlogin_tgl']),' (',tanggal('full', $data['nowlogin_tgl']) ?>)</label></td>
</tr>
<?php } ?>
</table><br />