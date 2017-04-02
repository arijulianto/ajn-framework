<?php
if(isset($_POST['save'])){
	$data_save['social_facebook'] = $_POST['social']['facebook'];
	$data_save['social_twitter'] = $_POST['social']['twitter'];
	$data_save['social_gplus'] = $_POST['social']['gplus'];
	$data_save['contact_whatsapp'] = $_POST['contact']['whatsapp'];
	$data_save['contact_ym'] = $_POST['contact']['ym'];
	$data_save['contact_skype'] = $_POST['contact']['skype'];
	$data_save['alamat'] = $_POST['address'];
	$data_save['kota'] = $_POST['kota'];
	$data_save['telpon'] = $_POST['phone'];
	$data_save['lokasi_map'] = $_POST['maps_coord'].'|'.$_POST['maps_zoom'];

	$db->update('tbl_setting', $data_save, array('idsetting'=>'1'));
	if($db->iAffectedRows>0)
		$msg['sukses'] = 'Info Kontak & Jejaring Sosial berhasil diperbaharui...';
	else
		$msg['warning'] = 'Info Kontak & Jejaring Sosial gagal diperbaharui atau tidak ada data yang diubah';
}

$setting = $db->query("SELECT * from tbl_setting")->result();
$se = explode('|', $setting['lokasi_map']);
$setting['lokasi_map'] = $se[0];
$setting['lokasi_zoom'] = $se[1];
?><h1 class="title">Info Kontak & Jejaring Sosial</h1>
<div class="page">
<form action="" method="post">
<?php
if($msg['sukses']){
	echo '<p class="sukses">',$msg['sukses'],'</p>';
}elseif($msg['warning']){
	echo '<p class="warning">',$msg['warning'],'</p>';
}

?>
<fieldset>
<legend>Jejaring Sosial</legend>
<table>
<tr>
	<td class=r width=100><label for="scfb">Facebook :&nbsp;</label></td>
	<td><input type="text" id="scfb" name="social[facebook]" size="40" value="<?php echo $setting['social_facebook'] ?>" placeholder="https://www.facebook.com/[userName]" /></td>
</tr>
<tr>
	<td class=r><label for="sctw">Twitter :&nbsp;</label></td>
	<td><input type="text" id="sctw" name="social[twitter]" size="40" value="<?php echo $setting['social_twitter'] ?>" placeholder="@userName" /></td>
</tr>
<tr>
	<td class=r><label for="scgp">Google+ :&nbsp;</label></td>
	<td><input type="text" id="scgp" name="social[gplus]" size="40" value="<?php echo $setting['social_gplus'] ?>" placeholder="https://plus.google.com/+[userID]" /></td>
</tr>
</table>
<small>Note: hanya username atau userID saja, bukan full URL</small>
</fieldset><br />

<fieldset>
<legend>Infomasi Kontak</legend>
<table>
<tr>
	<td class=r width=100><label for="cnad">Alamat :&nbsp;</label></td>
	<td><input type="text" id="cnad" name="address" size="40" value="<?php echo $setting['alamat'] ?>" placeholder="Jl. ..." /></td>
</tr>
<tr>
	<td class=r><label for="cnph">Telpon :&nbsp;</label></td>
	<td><input type="text" id="cnph" name="phone" size="40" value="<?php echo $setting['telpon'] ?>" placeholder="Phone" /></td>
</tr>
<tr>
	<td class=r><label for="cnwa">WhatsApp :&nbsp;</label></td>
	<td><input type="text" id="cnwa" name="contact[whatsapp]" size="40" value="<?php echo $setting['contact_whatsapp'] ?>" placeholder="08xxx" /></td>
</tr>
<tr>
	<td class=r><label for="cnym">Yahoo Msgr :&nbsp;</label></td>
	<td><input type="text" id="cnym" name="contact[ym]" size="40" value="<?php echo $setting['contact_ym'] ?>" placeholder="ID Yahoo Messenger" /></td>
</tr>
<tr>
	<td class=r><label for="cnsk">Skype :&nbsp;</label></td>
	<td><input type="text" id="cnsk" name="contact[skype]" size="40" value="<?php echo $setting['contact_skype'] ?>" placeholder="ID Skype" /></td>
</tr>
<tr>
	<td class=r><label for="cnlok">Lokasi Peta :&nbsp;</label></td>
	<td><input type="text" id="cnlok" name="maps_coord" size="28" value="<?php echo $setting['lokasi_map'] ?>" placeholder="-x.xxxxxx,y.yyyyyy" /> <input type="text" data-type="number" name="maps_zoom" size="4" value="<?php echo $setting['lokasi_zoom'] ?>" placeholder="Zoom" /></td>
</tr>
<tr>
	<td class=r><label for="kota">Kota :&nbsp;</label></td>
	<td><input type="text" id="kota" name="kota" size="30" value="<?php echo $setting['kota'] ?>" placeholder="Kota" /></td>
</tr>
</table>
</fieldset><br />
<fieldset class=c>
<input type="submit" name="save" value=" Simpan Perubahan " class="btn btn-primary" />
</fieldset>
</form>
</div>