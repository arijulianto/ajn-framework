<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');
$jml = count($_POST['ids']);
$num_data = $jml>1 ? " $jml" : '';


if($slug3){
	$theID = decode($slug3);
	$theID = str_replace('bank','',$theID);
}


if(isset($_POST['done'])){
	$act = $_POST['act'];
	$arrID = "'".str_replace(',',"','",$_POST['src'])."'";
	$where = strpos($arrID, ','	) ? "idbank IN($arrID)" : "idbank=$arrID";

	if($act=='activate'){
		$db->query("UPDATE tbl_databank2 set aktif='1' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Bank berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Bank gagal di'.$aAksi2[$act];
	}elseif($act=='deactivate'){
		$db->query("UPDATE tbl_databank2 set aktif='0' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Bank berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Bank gagal di'.$aAksi2[$act];
	}elseif($act=='delete'){
		$db->query("DELETE from tbl_databank2 where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Bank berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Bank gagal di'.$aAksi2[$act];
	}
}

if(isset($_POST['save'])){
	$save['bank'] = $_POST['bank'];
	$save['nomor_rekening'] = $_POST['nomor_rekening'];
	$save['nama_rekening'] = $_POST['nama_rekening'];
	$save['class'] = $_POST['class'];
	$save['aktif'] = $_POST['status'];

	if($_POST['source']>0){
		$db->update('tbl_databank2', $save, array('idbank'=>$_POST['source']));
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Bank berhasil diperbaharui';
		else
			$msg['warning'] = 'Bank gagal diperbaharui';
	}else{
		$do_save = $db->insert('tbl_databank2', $save)->getLastInsertId();
		if($do_save>0)
			$msg['sukses'] = 'Bank baru berhasil ditambahkan';
		else
			$msg['warning'] = 'Bank baru gagal ditambahkan';
	}
}

if($slug2=='edit'){
	$data = $db->query("SELECT * from tbl_databank where idbank='$theID'")->result();
}

?><h1 class="title">Data Bank</h1>
<div class="action-bar">
<a class="btn" href="<?php echo MODULE_URI,'/',$slug1 ?>/new">[+] Input Baru</a>
<input type="text" name="q" class="float-right search-table" value="<?php echo $_GET['q'] ?>" autocomplete="off" placeholder="Cari..." required />
</div>
<div class="page">
<?php
if($slug2=='activate' || $slug2=='deactivate' || $slug2=='delete' || $_POST['actions']){
if($_POST['actions']['activate']) $slug2 = 'activate';
if($_POST['actions']['deactivate']) $slug2 = 'deactivate';
if($_POST['actions']['delete']) $slug2 = 'delete';

if(!$msg['sukses'])
echo "<form action=\"\" method=\"post\">
<fieldset>
<p>Anda yakin ingin $aAksi[$slug2]$num_data data yang dipilih?</p>
<input type=\"hidden\" name=\"act\" value=\"$slug2\" />
<input type=\"hidden\" name=\"src\" value=\"",$slug3 ? $theID : implode(',',$_POST['ids']),"\" />
<p><input type=\"submit\" name=\"done\" value=\" Yes \" class=\"btn btn-primary\" /> <input type=\"button\" value=\" No \" class=\"btn btn-inverse\" onclick=\"location.href='",MODULE_URI,"/$slug1'\" /></p>
</fieldset>
</form>";
}
?>
<div class="wrap">
<div class="col-3-5">
<form action="" method="post">
<?php
if($msg['sukses']){
	echo '<p class="sukses">',$msg['sukses'],'</p>';
}elseif($msg['warning']){
	echo '<p class="warning">',$msg['warning'],'</p>';
}

?>
<table class="table table-bordered table-striped table-hover table-grid table-data">
<thead>
<tr>
	<th width="30">No.</th>
	<th>Nama Bank</th>
	<th>Rekening</th>
	<th>Status</th>
	<th width="90">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$arr_status = array('Tidak Aktif','Aktif');
$no = $_GET['page']>1 ? (($limit*$_GET['page'])-$limit)+1 : 1;
$datas = $db->query("SELECT idbank,bank,nomor_rekening,nama_rekening,aktif from tbl_databank order by aktif,bank limit $start,$limit")->results();
$jml_data = $db->query("SELECT count(idbank) as jml from tbl_databank")->result();


foreach($datas as $xdata){
$status = $arr_status[$xdata['aktif']];
$id = encode('bank'.$xdata['idbank']);
$checked = ($jml && in_array($xdata['idbank'], $_POST['ids'])) ? ' checked' : '';
$selected = ($jml && in_array($xdata['idbank'], $_POST['ids'])) ? ' class="selected"' : '';
echo "<tr$selected>
	<td>$no.</td>
	<td><input type=\"checkbox\" name=\"ids[]\" class=\"check-$no input-check\" value=\"$xdata[idbank]\"$checked />$xdata[bank]</td>
	<td>$xdata[nama_rekening]<br />$xdata[nomor_rekening]</td>
	<td class=c>$status</td>
	<td class=c><a href=\"",MODULE_URI,"/$slug1/edit/$id\">Edit</a> &middot; <a href=\"",MODULE_URI,"/$slug1/delete/$id\" class=\"actions\">Hapus</a></td>
</tr>\n";
$no++;
}
?>
</tbody>
</table>
<?php paging($jml_data['jml']); ?>

<input type="submit" name="actions[activate]" value="Aktifkan" class="btn btn-success btn-small btn-action-bm"<?php echo !$num_data ? ' disabled' : '' ?> />
<input type="submit" name="actions[deactivate]" value="Non-Aktifkan" class="btn btn-warning btn-small btn-action-bm"<?php echo !$num_data ? ' disabled' : '' ?> />
<input type="submit" name="actions[delete]" value="Hapus" class="btn btn-danger btn-small btn-action-bm"<?php echo !$num_data ? ' disabled' : '' ?> />
</form>
</div>
<div class="col-2-5">
<form action="<?php echo MODULE_URI; echo $slug2=='edit' ? '/'.$slug1 : '/'.$slug1.'/new'; if($slug2=='edit') echo '/',$slug2,'/',$slug3; ?>" method="post">
<input type="hidden" name="source" value="<?php echo $data['idbank']+0 ?>" />
<table>
<tr>
	<td class=r width=150>&nbsp;</td>
	<td><strong>[<?php echo $data['idbank']>0 ? 'Ubah Data' : 'Tambah Baru' ?>]</strong></td>
</tr>
<tr>
	<td class=r><label for="nama">Nama Bank :&nbsp;</label></td>
	<td><input type="text" id="nama" name="bank" size="25" value="<?php echo $data['bank'] ?>" placeholder="Nama Bank" required /></td>
</tr>
<tr>
	<td class=r><label for="nrek">No. Rekening :&nbsp;</label></td>
	<td><input type="text" id="nrek" name="nomor_rekening" size="25" value="<?php echo $data['nomor_rekening'] ?>" placeholder="Nomor Rekening" /></td>
</tr>
<tr>
	<td class=r><label for="npem">Nama Pemilik :&nbsp;</label></td>
	<td><input type="text" id="npem" name="nama_rekening" size="25" value="<?php echo $data['nama_rekening'] ?>" placeholder="Nama Pemilik Rekening" /></td>
</tr>
<tr>
	<td class=r><label for="logo">Logo :&nbsp;</label></td>
	<td>
<table>
<tr>
<td colspan=2><label><input type="radio" name="class" value="nologo"<?php echo $data['class']=='' ? ' checked' : '' ?> />No Icon</label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="bca-bank"<?php echo $data['class']=='bca-bank' ? ' checked' : '' ?> /><i class="icon icon-logo bca-bank"></i></label></td>
<td><label><input type="radio" name="class" value="mandiri"<?php echo $data['class']=='mandiri' ? ' checked' : '' ?> /><i class="icon icon-logo mandiri"></i></label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="bni"<?php echo $data['class']=='bni' ? ' checked' : '' ?> /><i class="icon icon-logo bni"></i></label></td>
<td><label><input type="radio" name="class" value="bri"<?php echo $data['class']=='bri' ? ' checked' : '' ?> /><i class="icon icon-logo bri"></i></label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="cimb"<?php echo $data['class']=='cimb' ? ' checked' : '' ?> /><i class="icon icon-logo cimb"></i></label></td>
<td><label><input type="radio" name="class" value="visa"<?php echo $data['class']=='visa' ? ' checked' : '' ?> /><i class="icon icon-logo visa"></i></label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="mastercard"<?php echo $data['class']=='mastercard' ? ' checked' : '' ?> /><i class="icon icon-logo mastercard"></i></label></td>
<td><label><input type="radio" name="class" value="paypal"<?php echo $data['class']=='paypal' ? ' checked' : '' ?> /><i class="icon icon-logo paypal"></i></label></td>
</tr>
</table>
</td>
</tr>
<tr>
	<td class=r><label for="aktif">Status :&nbsp;</label></td>
	<td><label><input type="radio" id="aktif" name="status" value="1"<?php echo $data['aktif']=='1' ? ' checked' : '' ?> required /> Aktif</label> <label><input type="radio" name="status" value="0"<?php echo $data['aktif']=='0' ? ' checked' : '' ?> required /> Tidak Aktif</label></td>
</tr>
<tr>
	<td class=r>&nbsp;</td>
	<td><input type="submit" name="save" value="<?php echo $slug2=='edit' ? 'Simpan Perubahan' : ' Tambahkan ' ?>" class="btn btn-success" /></td>
</tr>
</table>
</form>
</div>
</div>