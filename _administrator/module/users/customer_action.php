<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');

if($slug3){
	$theID = hex2str($slug3);
	$theID = str_replace('cus','',$theID);
}


if(isset($_POST['done'])){
	if($slug2=='activate'){
		$db->query("UPDATE tbl_user set aktif='1' where iduser='$theID'");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$slug2];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$slug2];
	}elseif($slug2=='deactivate'){
		$db->query("UPDATE tbl_user set aktif='0' where iduser='$theID'");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$slug2];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$slug2];
	}elseif($slug2=='delete'){
		$db->query("DELETE from tbl_user where iduser='$theID'");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$slug2];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$slug2];
	}
}
?><form action="<?php echo MODULE_URI,'/',$slug1,'/',$slug2,'/',$slug3 ?>" method="post">
<div class="action-bar">
<strong><?php echo ucfirst($slug2) ?> Customer</strong>
</div>
<div class="page">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
else
	echo 'Anda yakin ingin ',$aAksi[$slug2],' customer ini?';
?>
</div>
<div class="action-bar">
<?php
if($msg['sukses']){
	echo '<input type="button" value="  OK  " class="btn btn-success" onclick="location.href=\'',MODULE_URI,'/',$slug1,'\'" />';
}else{ ?>
<input type="submit" name="done" value=" Ya " class="btn btn-primary" />
<input type="button" value="Tidak" class="btn btn-inverse" onclick="location.href='<?php echo MODULE_URI,'/',$slug1 ?>'" />
<?php } ?>
</div>
</form>