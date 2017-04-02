<?php
if($slug3){
	$theID = hex2str($slug3);
	$theID = str_replace('cus','',$theID);

	$data = $db->query("SELECT * from view_datauser where iduser='$theID'")->result();

	$data_alamat = $db->query("SELECT * from view_user_alamat where iduser='$theID'")->results();

	$genders = array('L'=>'Laki-laki', 'P'=>'Perempuan');
	$statuses = array('Tidak Aktif', 'Aktif');

	$data['email'] = $data['user_email'];
	$data['gender'] = $data['gender'] ? $genders[$data['gender']] : 'N/A';
	$data['tgl_lahir'] = $data['tgl_lahir']!='0000-00-00' ? date('d/m/Y', strtotime($data['tgl_lahir'])) : 'N/A';
	$data['tgl_register'] = date('d/m/Y H:i', strtotime($data['tgl_register']));
	$lbl_facebook = $data['facebook_id'] ? 'Terhubung' : 'Belum Terhubung';
	$lbl_google = $data['google_id'] ? 'Terhubung' : 'Belum Terhubung';
	$data['status'] = $statuses[$data['aktif']];

	if($_POST['save']){
		$data_save['aktif'] = $_POST['status'];
		if($_POST['new_passwrd']) $data_save['user_password'] = md5(base64_encode($_POST['new_passwrd']));

		$db->update('tbl_user', $data_save, array('iduser'=>$theID));
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Data customer berhasil diperbaharui';
		else
			$msg['warning'] = 'Data custmer gagal diperbaharui. Mungkin tidak ada data yag diubah!';
	}
}
?><div class="action-bar">
<strong><?php echo ucfirst($slug2) ?> Customer</strong>
</div>
<div class="page">
<form action="<?php echo MODULE_URI,'/',$slug1,'/',$slug2,'/',$slug3 ?>" method="post">
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
	<td class=r width=120><label for="nama">Nama Customer :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"text\" id=\"nama\" name=\"nama\" size=\"35\" placeholder=\"Nama Lengkap\" required />" : "<label class=\"input\">$data[nama]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="gender">Gender :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<label><input type=\"radio\" id=\"gender\" name=\"gender\" value=\"L\" /> Laki-laki</label> <label><input type=\"radio\" name=\"gender\"value=\"P\" /> Perempuan</label>" : "<label class=\"input\">$data[gender]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="tglLahir">Tgl Lahir :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"date\" id=\"tglLahir\" data-type=\"date\" name=\"tgl_lahir\" size=\"8\" placeholder=\"dd/mm/yyyy\" />" : "<label class=\"input\">$data[tgl_lahir]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="kota">Kota :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"text\" id=\"kota\" name=\"kota\" size=\"20\" placeholder=\"Kota\" required />" : "<label class=\"input\">$data[kota]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="fb">Facebook :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<label><input type=\"checkbox\" id=\"fb\" name=\"facebook\" /> Terhubung</label>" : "<label class=\"input\">$lbl_facebook</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="gogl">Google :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<label><input type=\"checkbox\" id=\"gogl\" name=\"google\" /> Tidak Terhubung</label>" : "<label class=\"input\">$lbl_google</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="tglreg">Tgl Daftar :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"text\" id=\"tglreg\" name=\"tgl_daftar\" size=\"12\" value=\"dd/mm/yyyy\" placeholder=\"dd/mm/yyyy\" />" : "<label class=\"input\">$data[tgl_register]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="aktif">Status :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<label><input type=\"radio\" id=\"aktif\" name=\"status\" value=\"L\" /> Aktif</label> <label><input type=\"radio\" name=\"status\" value=\"P\" /> Tidak Aktif</label>" : "<label><input type=\"radio\" name=\"status\" value=\"1\"".($data['aktif']=='1' ? ' checked' : '')." /> Aktif</label> <label><input type=\"radio\" name=\"status\" value=\"0\"".($data['aktif']=='0' ? ' checked' : '')." /> Tidak Aktif</label>" ?></td>
</tr>
</table>
</fieldset><br />

<fieldset><legend>Informasi Akun</legend>
<table>
<tr>
	<td class=r width=120><label for="email">Email :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"text\" id=\"email\" name=\"nama\" size=\"35\" placeholder=\"Email\" value=\"Email\" />" : "<label class=\"input\">$data[email]</label>" ?></td>
</tr>
<tr>
	<td class=r><label for="passwd">Password :&nbsp;</label></td>
	<td><?php echo $slug2=='new' ? "<input type=\"password\" id=\"passwd\" name=\"nama\" size=\"35\" placeholder=\"Password\" value=\"Password\" />" : "<input type=\"password\" name=\"new_passwrd\" size=\"30\" placeholder=\"Password baru\" /> <small>Isi jika ingin mengganti password</small>" ?></td>
</tr>
</table>
</fieldset><br />

<fieldset><legend>Data Alamat</legend>
<table class="table">
<?php
if($data_alamat){
foreach($data_alamat as $dt){
$default = $dt['utama'] ? ' (Default)' : '';
echo "<tr>
	<td class=r width=120><label>$dt[label] :&nbsp;</label></td>
	<td><strong>$dt[nama]</strong>$default<br />",nl2br($dt['alamat']),"<br />Kec. $dt[kecamatan]<br />$dt[kota]</td>
</tr>\n";
}
}else{
echo "<tr>
	<td colspan=2><p class=\"warning\">$data[nama] belum mempunyai data alamat!</p></td>
</tr>\n";
}
?>
</table>
</fieldset><br />

<fieldset class=c>
<input type="button" value="&laquo; Kembali" class="btn btn-inverse" data-href="<?php echo MODULE_URI,'/',$slug1 ?>" /> <input type="submit" name="save" value=" Simpan " class="btn btn-primary" />
</fieldset>
</form>
</div>