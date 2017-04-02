<h1 class="title">Profil User</h1>
<?php
if($slug2){
	$theID = hex2str($slug2);
	$theID = str_replace('adm','',$theID);
}


if(isset($_POST['save'])){
	$data_save['user_email'] = $_POST['email'];
	$data_save['nama'] = $_POST['nama'];
	$data_save['gender'] = $_POST['gender'];
	$data_save['idkota'] = $_POST['kota'];
	$data_save['tgl_lahir'] = $_POST['tgl_lahir'];

	if($_POST['passwd']!='' && $_POST['passwd']!=''){
		if($_POST['passwd']>=100000){
			$msg['warning'] = 'Password dengan angka saja amatlah rentan. Harap pilih password yang aman!';
		}elseif(in_array(strtolower($_POST['passwd']), array('admin','administrator','user','password','iloveyou','abc123','qwerty',''))){
			$msg['warning'] = 'Password terlalu mudah ditebak. Harap gunakan yang lain!';
		}elseif(strlen($_POST['passwd'])<6){
			$msg['warning'] = 'Password terlalu pendek. Gunakan setidaknya 6 karakter!';
		}elseif($_POST['passwd']!=$_POST['passwd2']){
			$msg['warning'] = 'Password konfirmasi tidak sama!';
		}else{
			$data_save['user_password'] = md5(base64_encode($_POST['passwd']));
			$change_password = 1;
		}
	}

	$save_user = $db->update('tbl_user', $data_save, array('iduser'=>$_SESSION['admin_uid']));
	if($db->iAffectedRows>0)
		$msg['sukses'] = 'Data berhasil diperbaharui...';
	else
		$msg['warning'] = 'Data gagal diperbaharui. Silahkan coba lagi!';

	if($change_password && $msg['sukses']) $msg['sukses'] = 'Password berhasil diganti';
}


$data = $db->query("SELECT * from view_datauser where iduser='$_SESSION[admin_uid]'")->result();
$data['tgl_lahir'] = $data['tgl_lahir']!='0000-00-00' ? $data['tgl_lahir'] : '';


?><div class="action-bar">
<strong>Detail Informasi Akun Anda</strong>
</div>
<div class="page">
<form action="" method="post">
<?php
if($msg['sukses']){
	echo '<p class="sukses">',$msg['sukses'],'</p>';
}elseif($msg['warning']){
	echo '<p class="warning">',$msg['warning'],'</p>';
}

?>
<fieldset><legend>Informasi User</legend>
<table>
<tr>
	<td class=r width=150><label for="nama">Nama Lengkap :&nbsp;</label></td>
	<td><input type="text" id="nama" name="nama" size="35" value="<?php echo $data['nama'] ? $data['nama'] : $_POST['nama'] ?>" placeholder="Nama Lengkap" required /></td>
</tr>
<tr>
	<td class=r><label for="gender">Gender :&nbsp;</label></td>
	<td><label><input type="radio" id="gender" name="gender" value="L"<?php echo $data['gender']=='L' ? ' checked' : ($_POST['gender']=='L' ? ' checked' : '') ?> required /> Laki-laki</label> <label><input type="radio" name="gender" value="P"<?php echo $data['gender']=='P' ? ' checked' : ($_POST['gender']=='P' ? ' checked' : '') ?> required /> Perempuan</label></td>
</tr>
<tr>
	<td class=r><label for="tglLahir">Tgl Lahir :&nbsp;</label></td>
	<td><input type="date" id="tglLahir" data-type="date" name="tgl_lahir" size="8" value="<?php echo $data['tgl_lahir'] ? $data['tgl_lahir'] : $_POST['tgl_lahir'] ?>" placeholder="dd/mm/yyyy" /></td>
</tr>
<tr>
	<td class=r><label for="kota">Kota :&nbsp;</label></td>
	<td><select name="kota" required><?php
	$provkotas = $db->query("SELECT idkota,kode_kota,kota,provinsi,aktif from tbl_datakota where aktif='1' order by kode_kota")->results();
	foreach($provkotas as $i=>$pk){
		if($pk['aktif']) $provkota[$pk['provinsi']][$pk['kode_kota']] = $pk['kota'];
	}

	foreach($provkota as $prov=>$dk){
		if(count($dk)>0){
			echo '<optgroup label="'.$prov.'">';
			foreach($dk as $i=>$k){
				$sel_kota = $i==$data['idkota'] ? ' selected' : '';
				echo '<option value="',$i,'"',$sel_kota,'>',$k,'</option>';
			}
			echo '</optgroup>';
		}
	}

	?></select></td>
</tr>
</table>
</fieldset><br />

<fieldset><legend>Informasi Akun</legend>
<table>
<tr>
	<td class=r width=150><label for="username">Username :&nbsp;</label></td>
	<td><input type="text" id="username" name="username" size="20" value="<?php echo $data['user_login'] ?>" placeholder="Username" value="Username" readonly /> <small>Tidak bisa diubah!</small></td>
</tr>
<tr>
	<td class=r><label for="email">Email :&nbsp;</label></td>
	<td><input type="email" id="email" name="email" size="35" value="<?php echo $data['user_email'] ? $data['user_email'] : $_POST['email'] ?>" placeholder="Email" value="Email" /></td>
</tr>
<tr>
	<td class=r><label for="passwd">Password Baru:&nbsp;</label></td>
	<td><input type="password" id="passwd" name="passwd" size="20" placeholder="Password Baru" /> <small>Isi jika ingin diganti!</small></td>
</tr>
<tr>
	<td class=r><label for="passwd2">Konfirmasi Password :&nbsp;</label></td>
	<td><input type="password" id="passwd2" name="passwd2" size="20" placeholder="Password Lagi" /></td>
</tr>
</table>
</fieldset><br />

<fieldset class=c>
<input type="button" value="&laquo; Kembali" class="btn btn-inverse" onclick="location.href='<?php echo MODULE_URI ?>'" /> &nbsp; <input type="submit" name="save" value=" Simpan<?php echo ($slug1=='edit' && $theID>0) ? ' Perubahan' : '' ?> " class="btn btn-primary"<?php echo ($msg['sukses'] && $slug1=='new') ? ' disabled' : '' ?> />
</fieldset>
</form>
</div>