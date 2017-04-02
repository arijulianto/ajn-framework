<?php
$total_urutan = $db->query("SELECT urutan from tbl_slider order by urutan desc limit 1")->result();
$total_urutan = $total_urutan['urutan'];

if($slug2){
	$theID = decode($slug2);
	$theID = str_replace('slide','',$theID);
}

if(isset($_POST['save'])){
	$save['slider'] = $_POST['ori_file'];
	$valid_images = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP);

	if($_FILES['file']['size']>0){
		$finfo = getimagesize($_FILES['file']['tmp_name']);
		$ext = strtolower(strrchr($_FILES['file']['name'], '.'));
		$basename = basename($_FILES['file']['name'], $ext);
		$fname = time().$ext;

		if(in_array($finfo[2], $valid_images)){
			$upload = move_uploaded_file($_FILES['file']['tmp_name'], MEDIA_DIR.'slider/'.$fname);
			if($upload){
				$save['slider'] = $fname;
				if($fname!=$_POST['ori_file'] && $_POST['ori_file']!=''){
					unlink(MEDIA_DIR.'slider/'.$_POST['ori_file']);
				}
			}
		}
	}
	$save['judul'] = $_POST['title'];
	$save['keterangan'] = $_POST['keterangan'];
	$save['urutan'] = $_POST['urutan'];
	$save['aktif'] = $_POST['aktif'];

	if($slug1=='edit'){
		$save = $db->update('tbl_slider', $save, array('idslider'=>$theID))->affectedRows();
		if($save>0)
			$msg['sukses'] = 'Slider berhasil diperbaharui';
		else
			$msg['warning'] = 'Slider gagal diperbaharui';
	}
	elseif($slug1=='new'){
		$save = $db->insert('tbl_slider', $save)->getLastInsertId();
		if($save>0)
			$msg['sukses'] = 'Slider baru berhasil ditambahkan';
		else
			$msg['warning'] = 'Slider baru gagal ditambahkan';
	}
}

if($slug1=='edit'){
	$data = $db->query("SELECT * from tbl_slider where idslider='$theID'")->result();
}else{
	$data['urutan'] = $total_urutan+1;
}


?><form action="<?php echo MODULE_URI,'/',$slug1; echo $slug2 ? '/'.$slug2 : ''; ?>" method="post" enctype="multipart/form-data">
<input type="hidden" name="source" value="<?php echo $theID+0 ?>" />
<div class="action-bar">
<strong><?php echo ucfirst($slug1) ?> Slider</strong>
</div>
<div class="page">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
?>
<table>
<tr>
	<td class=r width=120><label for="file">Gambar :&nbsp;</label></td>
	<td class="row">
		<label><img class="imgPreview"<?php if($data['slider']) echo " src=\"",MEDIA_URI,"slider/$data[slider]\" title=\"Klik untuk mengganti\""; ?> /><?php if($data['slider']) echo "\n		<input type=\"hidden\" name=\"ori_file\" value=\"$data[slider]\" />\n"; ?>
		<input type="file" name="file" class="i-file" id="file"<?php if($slug1=='new') echo ' required'; ?> data-minw="530" data-minh="320" accept="image/*" capture="camera" /></label><br />
		<small>Pilih file gambar JPG, PNG atau GIF dengan dimensi 530x320 pixel</small>
	</td>
</tr>
<tr>
	<td class=r><label for="title">Judul :&nbsp;</label></td>
	<td><input type="text" name="title" size="40" id="title" value="<?php echo $data['judul'] ?>" placeholder="Judul" /></td>
</tr>
<tr>
	<td class=r><label for="desc">Keterangan :&nbsp;</label></td>
	<td><textarea name="keterangan" id="desc" cols="40" rows="3" placeholder="Keterangan"><?php echo $data['keterangan'] ?></textarea></td>
</tr>
<tr>
	<td class=r><label for="sort">Urutan :&nbsp;</label></td>
	<td>
<input type="number" name="urutan" id="sort" size="4" min="1" value="<?php echo $data['urutan'] ?>" placeholder="Urutan" required /> dari total <?php echo $total_urutan ?></td>
</tr>
<tr>
	<td class=r><label>Status :&nbsp;</label></td>
	<td><label><input type="radio" name="aktif" value="1"<?php echo ($data['aktif']=='1' || $slug1=='new') ? ' checked' : '' ?> required /> Aktif</label> <label><input type="radio" name="aktif" value="0"<?php echo $data['aktif']=='0' ? ' checked' : '' ?> required /> Tidak Aktif</label></td>
</tr>
</table>
</div>
<div class="action-bar">
<input type="button" value="&laquo; Kembali" class="btn btn-inverse" onclick="location.href='<?php echo MODULE_URI ?>'" />
<input type="submit" name="save" value="Simpan<?php echo $slug1=='edit' ? ' Perubahan' : '' ?>" class="btn btn-primary"<?php echo ($msg['sukses'] && $slug1=='new') ? ' disabled' : '' ?> />
</div>
</form>