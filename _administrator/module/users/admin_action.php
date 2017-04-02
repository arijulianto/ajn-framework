<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');

if(isset($_POST['done'])){
	//$msg['sukses'] = 'Berhasil di'.$aAksi2[$slug1];
	$msg['warning'] = 'Gagal di'.$aAksi2[$slug1];
}
?><form action="<?php echo MODULE_URI,'/',$slug1; echo $slug2 ? '/'.$slug2 : ''; ?>" method="post">
<div class="action-bar">
<strong><?php echo ucfirst($slug1) ?> Pengelola</strong>
</div>
<div class="page">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
else
	echo 'Anda yakin ingin ',$aAksi[$slug1],' pengelola ini?';
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