<?php
if($slug2){
	$theID = hex2str($slug2);
	$theID = str_replace('adm','',$theID);
}


if(isset($_POST['save'])){
	$data_save['user_login'] = $_POST['username'];
	$data_save['user_email'] = $_POST['email'];
	$data_save['nama'] = $_POST['nama'];
	$data_save['gender'] = $_POST['gender'];
	$data_save['idkota'] = $_POST['kota'];
	$data_save['tgl_lahir'] = $_POST['tgl_lahir'];
	$data_save['posisi'] = $_POST['jabatan'];
	$data_save['aktif'] = $_POST['status'];

	if($_POST['passwd']!='******' && $_POST['passwd']!=''){
		if($_POST['passwd']=='' || $_POST['passwd2']==''){
			$msg['warning'] = 'Password harus diisi!';
		}elseif($_POST['passwd']>=100000){
			$msg['warning'] = 'Password dengan angka saja amatlah rentan. Harap pilih password yang aman!';
		}elseif(in_array(strtolower($_POST['passwd']), array('admin','administrator','user','password','iloveyou','abc123','qwerty',''))){
			$msg['warning'] = 'Password terlalu mudah ditebak. Harap gunakan yang lain!';
		}elseif(strlen($_POST['passwd'])<6){
			$msg['warning'] = 'Password terlalu pendek. Gunakan setidaknya 6 karakter!';
		}elseif($_POST['passwd']!=$_POST['passwd2']){
			$msg['warning'] = 'Password konfirmasi tidak sama!';
		}else{
			$data_save['user_password'] = md5(base64_encode($_POST['passwd']));
		}
	}

	if($slug1=='new'){
		$data_save['tgl_register'] = date('Y-m-d H:i:s');
		$cek = $db->query("SELECT iduser,user_login,user_email from tbl_user where lower(user_login)='".trim(strtolower($_POST['username']))."' OR lower(user_email)='".trim(strtolower($_POST['email']))."'")->result();
		if($cek['iduser']>0){
			if($cek['user_login'])
				$msg['warning'] = 'Username sudah dipakai oleh user lain. Silahkan gunakan yang lain!';
			elseif($cek['user_email'])
				$msg['warning'] = 'Email sudah dipakai oleh user lain. Silahkan gunakan yang lain!';
		}else{
			$save_user = $db->insert('tbl_user', $data_save)->getLastInsertId();
			if($save_user>0)
				$msg['sukses'] = 'Pengelola baru berhasil ditambahkan...';
			else
				$msg['warning'] = 'Pengelola baru gagal ditambahkan. Silahkan coba lagi!';
		}
	}else{
		$save_user = $db->update('tbl_user', $data_save, array('iduser'=>$theID));
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Pengelola berhasil diperbaharui...';
		else
			$msg['warning'] = 'Pengelola gagal diperbaharui. Silahkan coba lagi!';
	}
}

if($slug1=='edit'){
	$data = $db->query("SELECT * from view_datauser where iduser='$theID'")->result();
	$data['tgl_lahir'] = $data['tgl_lahir']!='0000-00-00' ? $data['tgl_lahir'] : '';
}

?><div class="action-bar">
<strong><?php echo ucfirst($slug1) ?> Pengelola</strong>
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
<tr>
	<td class=r><label for="aktif">Status :&nbsp;</label></td>
	<td><label><input type="radio" id="aktif" name="status" value="1"<?php echo $data['aktif']=='1' ? ' checked' : ($_POST['status']==1 ? ' checked' : '') ?> required /> Aktif</label> <label><input type="radio" name="status" value="0"<?php echo $data['aktif']=='0' ? ' checked' : ($_POST['status']=='0' ? ' checked' : '') ?> required /> Tidak Aktif</label></td>
</tr>
</table>
</fieldset><br />

<fieldset><legend>Informasi Akun</legend>
<table>
<tr>
	<td class=r width=150><label for="username">Username :&nbsp;</label></td>
	<td><input type="text" id="username" name="username" size="35" value="<?php echo $data['user_login'] ? $data['user_login'] : $_POST['username'] ?>" placeholder="Username" value="Username" /></td>
</tr>
<tr>
	<td class=r><label for="jab">Jabatan :&nbsp;</label></td>
	<td><select name="jabatan" id="jab" required><?php
	$jabs = array(3=>'Kontributor',2=>'Administrator');

	foreach($jabs as $i=>$v){
		$sel_jab = $i==$data['posisi'] ? ' selected' : ($i==$_POST['jabatan'] ? ' selected' : '');
		echo '<option value="',$i,'"',$sel_jab,'>',$v,'</option>';
	}

	?></select></td>
</tr>
<tr>
	<td class=r><label for="email">Email :&nbsp;</label></td>
	<td><input type="email" id="email" name="email" size="35" value="<?php echo $data['user_email'] ? $data['user_email'] : $_POST['email'] ?>" placeholder="Email" value="Email" /></td>
</tr>
<tr>
	<td class=r><label for="passwd">Password :&nbsp;</label></td>
	<td><input type="password" id="passwd" name="passwd" size="35" placeholder="Password" value="<?php echo $data['user_email'] ? '******' : '' ?>" /></td>
</tr>
<tr>
	<td class=r><label for="passwd2">Konfirmasi Password :&nbsp;</label></td>
	<td><input type="password" id="passwd2" name="passwd2" size="35" placeholder="Password Lagi" /></td>
</tr>
</table>
</fieldset><br />

<fieldset class=c>
<input type="button" value="&laquo; Kembali" class="btn btn-inverse" onclick="location.href='<?php echo MODULE_URI ?>'" /> &nbsp; <input type="submit" name="save" value=" Simpan<?php echo ($slug1=='edit' && $theID>0) ? ' Perubahan' : '' ?> " class="btn btn-primary"<?php echo ($msg['sukses'] && $slug1=='new') ? ' disabled' : '' ?> />
</fieldset>
</form>
</div>