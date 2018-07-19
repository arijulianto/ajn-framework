
<h1 class="title"><?php echo $site['title'] ?></h1>
<div class="content">
<form action="" method="post" enctype="multipart/form-data">
<?php
if($_POST){
	$data_save = array();
	$data_save['site_name'] = $_POST['site_name'];
    $data_save['site_slogan'] = $_POST['site_slogan'];
    if($_FILES['new_logo']['size']>0){
    	include PLUGIN_DIR.'class.upload.php';
		$upload = new upload($_FILES['new_logo'], MEDIA_DIR.'images');
		$ext = strrchr($_FILES['new_logo']['name'], '.');
		$name = substr_replace($_FILES['new_logo']['name'],'',-strlen($ext), strlen($ext));
		$up = $upload->upload(null, 130);
		if($up){
			$data_save['logo'] = $up;
		}
    }
    $data_save['company_name'] = $_POST['company_name'];
    $data_save['company_address'] = $_POST['company_address'];
    $data_save['company_city'] = $_POST['company_city'];
    $data_save['paging'] = $_POST['paging'];
    $data_save['paging_admin'] = $_POST['paging_admin'];

    $upd = 0;
    foreach($data_save as $name=>$value){
    	$do = $db->update('tbl_setting', array('setting_value'=>$value), array('setting_name'=>$name))->affectedRows();
    	if($do) $upd++;
    }
    if($upd>0){
    	echo '<p class="alert alert-success">Pengaturan berhasil diperbaharui</p>';
    	$setting = $db->query("SELECT setting_name,setting_value from $conf[db_setting]")->results();
		if($setting){
			foreach($setting as $row) {
				$setting['data'][$row['setting_name']]=$row['setting_value'];
			}
			$setting = $setting['data'];
			if($setting['lokasi_map']){
				$sm = explode('|',$setting['lokasi_map']);
				$cm = explode(',',$sm[0]);
				$setting['lokasi_map'] = $sm[0];
				$setting['lokasi_lat'] = $cm[0];
				$setting['lokasi_lng'] = $cm[1];
				$setting['lokasi_zoom'] = $sm[1];
			}
			if($setting['template']){
				$conf['template'] = $setting['template'];
			}
		}
    }else{
    	echo '<p class="alert alert-warning">Pengaturan gagal diperbaharui atau tidak ada data yang diubah.</p>';
    }
}
?>
<table class="table-form" width="100%">
<tr>
	<td><label>Nama Website:</label></td>
	<td><input type="text" name="site_name" class="input-block" value="<?php echo $setting['site_name'] ?>" placeholder="Nama Lengkap" required autofocus /></td>
</tr>
<tr>
	<td><label>Slogan:</label></td>
	<td><input type="text" name="site_slogan" class="input-block" value="<?php echo $setting['site_slogan'] ?>" placeholder="Slogan Website" required /></td>
</tr>
<tr>
	<td><label>Logo:</label></td>
	<td>
		<label title="Klik untuk mengganti Logo" style="vertical-align:middle"><img src="<?php echo MEDIA_URI,'images/',$setting['logo'] ?>" class="imgPreview" width="48" style="margin-right:4px;vertical-align:middle" align="middle" /><input type="file" name="new_logo" accept="image/*" data-maxsize="3145728" data-dimension="100x100" data-preview=".imgPreview" /></label><br /><small><i class="fa fa-question-circle"></i> Pilih file JPG atau PNG dengan ukuran kotak, minimal 100x100 pixel</small>
	</td>
</tr>
<tr>
	<td><label>Perusahaan:</label></td>
	<td><input type="text" name="company_name" class="input-block" value="<?php echo $setting['company_name'] ?>" placeholder="Perusahaan" required /></td>
</tr>
<tr>
	<td><label>Alamat:</label></td>
	<td><textarea name="company_address" cols="42" rows="3" class="input-block" placeholder="Alamat Lengkap"><?php echo $setting['company_address'] ?></textarea></td>
</tr>
<tr>
	<td><label>Kota:</label></td>
	<td><input type="text" name="company_city" id="map-city" class="input-block" value="<?php echo $setting['company_city'] ?>" placeholder="Kota" required /></td>
</tr>
<tr>
	<td><label>Paging:</label></td>
	<td>
		<p><label>Website &nbsp; &nbsp; &nbsp; &nbsp; : <input type="number" name="paging" size="6" value="<?php echo $setting['paging_web'] ?>" placeholder="Jml" required /> item per page</label></p>
		<p><label>Administrator : <input type="number" name="paging_admin" size="6" value="<?php echo $setting['paging_admin'] ?>" placeholder="Jml" required /> item per page</label></p>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><p><br /><input type="submit" class="btn btn-primary btn-large" value="Simpan Perubahan" /></p></td>
</tr>
</table>
</form>
</div>