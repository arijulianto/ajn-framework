<?php
if(isset($_POST['save'])){
	$data_save['site_name'] = $_POST['sitename'];
	$data_save['site_status'] = $_POST['status'];
	$data_save['company_name'] = $_POST['company_name'];
	$data_save['display_per_page'] = $_POST['num_item'];

	$db->update('tbl_setting', $data_save, array('idsetting'=>'1'));
	if($db->iAffectedRows>0)
		$msg['sukses'] = 'Setting website diperbaharui...';
	else
		$msg['warning'] = 'Setting website gagal diperbaharui atau tidak ada data yang diubah';
}

$setting = $db->query("SELECT * from tbl_setting")->result();
?><h1 class="title">Setting</h1>
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
<legend>Website</legend>
<table>
<tr>
	<td class=r width=120><label>Server Name :&nbsp;</label></td>
	<td><label class="input"><?php echo $_SERVER['SERVER_NAME'] ?></label></td>
</tr>
<tr>
	<td class=r><label>Server IP :&nbsp;</label></td>
	<td><label class="input"><?php echo $_SERVER['SERVER_ADDR'] ?></label></td>
</tr>
<tr>
	<td class=r><label for="nmw">Nama Website :&nbsp;</label></td>
	<td><input type="text" id="nmw" name="sitename" size="40" value="<?php echo $setting['site_name'] ?>" placeholder="Nama Website" /></td>
</tr>
<tr>
	<td class=r><label for="st">Status Website :&nbsp;</label></td>
	<td><label><input type="radio" id="st" name="status" value="online"<?php echo $setting['site_status']=='online' ? ' checked' : '' ?> /> Online</label> &nbsp; <label><input type="radio" id="st" name="status" value="offline"<?php echo $setting['site_status']=='offline' ? ' checked' : '' ?> /> Offline</label> &nbsp; <label><input type="radio" id="st" name="status" value="underconstruction"<?php echo $setting['site_status']=='underconstruction' ? ' checked' : '' ?> /> Under Construction</label> &nbsp; <label><input type="radio" id="st" name="status" value="comingsoon"<?php echo $setting['site_status']=='comingsoon' ? ' checked' : '' ?> /> Coming Soon</label></td>
</tr>
</table>
</fieldset><br />

<fieldset>
<legend>Setting</legend>
<table>
<tr>
	<td class=r width=120><label for="cnp">Nama Perusahaan :&nbsp;</label></td>
	<td><input type="text" id="cnp" name="company_name" size="40" value="<?php echo $setting['company_name'] ?>" placeholder="Nama Perusahaan" /></td>
</tr>
<tr>
	<td class=r><label for="nprod">Item per Page :&nbsp;</label></td>
	<td><input type="number" id="nprod" name="num_item" size="6" value="<?php echo $setting['display_per_page'] ?>" placeholder="Item per page" /></td>
</tr>
</table>
</fieldset><br />

<fieldset class=c>
<input type="submit" name="save" value=" Simpan Perubahan " class="btn btn-primary" />
</fieldset>
</form>
</div>