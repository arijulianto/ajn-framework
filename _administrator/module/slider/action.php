<?php
$theID = decode($slug2);
$theID = str_replace('slide','',$theID);

$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');

if(isset($_POST['done'])){
	if($slug1=='slug1ivate'){
		$db->query("UPDATE tbl_slider set aktif='1' where idslider='$theID'");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Slide berhasil di'.$aAksi2[$slug1];
		else
			$msg['warning'] = 'Slide gagal di'.$aAksi2[$slug1];
	}elseif($slug1=='deslug1ivate'){
		$db->query("UPDATE tbl_slider set aktif='0' where idslider='$theID'");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Slide berhasil di'.$aAksi2[$slug1];
		else
			$msg['warning'] = 'Slide gagal di'.$aAksi2[$slug1];
	}elseif($slug1=='delete'){
		$im = $db->query("SELECT slider from tbl_slider where idslider='$theID'")->results();
		$db->query("DELETE from tbl_slider where idslider='$theID'");
		if($db->iAffectedRows>0){
			$msg['sukses'] = 'Slide berhasil di'.$aAksi2[$slug1];
			foreach($im as $img){
				if(is_file(MEDIA_DIR.'slider/'.$img['slider'])) unlink(MEDIA_DIR.'slider/'.$img['slider']);
			}
		}else{
			$msg['warning'] = 'Slide gagal di'.$aAksi2[$slug1];
		}
	}
}
?><form action="<?php echo MODULE_URI,'/',$slug1; echo $slug2 ? '/'.$slug2 : ''; ?>" method="post">
<div class="action-bar">
<strong><?php echo ucfirst($slug1) ?> Slider</strong>
</div>
<div class="page">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
else
	echo 'Anda yakin ingin ',$aAksi[$slug1],' slider ini?';
?>
</div>
<div class="action-bar">
<?php
if($msg['sukses']){
	echo '<input type="button" value="  OK  " class="btn btn-success" onclick="location.href=\'',MODULE_URI,'\'" />';
}else{ ?>
<input type="submit" name="done" value=" Ya " class="btn btn-primary" />
<input type="button" value="Tidak" class="btn btn-inverse" onclick="location.href='<?php echo MODULE_URI ?>'" />
<?php } ?>
</div>
</form>