<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');
$jml = count($_POST['ids']);
$num_data = $jml>1 ? " $jml" : '';


if($slug3){
	$theID = decode($slug3);
	$theID = str_replace('kurir','',$theID);
}


if(isset($_POST['done'])){
	$act = $_POST['act'];
	$arrID = "'".str_replace(',',"','",$_POST['src'])."'";
	$where = strpos($arrID, ','	) ? "idkurir IN($arrID)" : "idkurir=$arrID";

	if($act=='activate'){
		$db->query("UPDATE tbl_datakurir set aktif='1' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Kurir berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Kurir gagal di'.$aAksi2[$act];
	}elseif($act=='deactivate'){
		$db->query("UPDATE tbl_datakurir set aktif='0' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Kurir berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Kurir gagal di'.$aAksi2[$act];
	}elseif($act=='delete'){
		$db->query("DELETE from tbl_datakurir where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Kurir berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Kurir gagal di'.$aAksi2[$act];
	}
}

if(isset($_POST['save'])){
	$save['kurir'] = $_POST['kurir'];
	$save['website'] = $_POST['website'];
	$save['keterangan'] = $_POST['keterangan'];
	$save['class'] = $_POST['class'];
	$save['aktif'] = $_POST['status'];

	if($_POST['source']>0){
		$db->update('tbl_datakurir', $save, array('idkurir'=>$_POST['source']));
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Kurir berhasil diperbaharui';
		else
			$msg['warning'] = 'Kurir gagal diperbaharui';
	}else{
		$do_save = $db->insert('tbl_datakurir', $save)->getLastInsertId();
		if($do_save>0)
			$msg['sukses'] = 'Kurir baru berhasil ditambahkan';
		else
			$msg['warning'] = 'Kurir baru gagal ditambahkan';
	}
}

if($slug2=='edit'){
	$data = $db->query("SELECT * from tbl_datakurir where idkurir='$theID'")->result();
}

?><h1 class="title">Data Kurir</h1>
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
<form action="<?php echo MODULE_URI,'/',$slug1 ?>" method="post">
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
	<th>Kurir</th>
	<th>Keterangan</th>
	<th>Status</th>
	<th width="110">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$status = array('Tidak Aktif','Aktif');
$no = $_GET['page']>1 ? (($limit*$_GET['page'])-$limit)+1 : 1;
$datas = $db->query("SELECT idkurir,kurir,keterangan,aktif from tbl_datakurir order by aktif limit $start,$limit")->results();
$jml_data = $db->query("SELECT count(idkurir) as jml from tbl_datakurir")->result();


foreach($datas as $xdata){
$st = $status[$xdata['aktif']];
$id = encode('kurir'.$xdata['idkurir']);
$checked = ($jml && in_array($xdata['idkurir'], $_POST['ids'])) ? ' checked' : '';
$selected = ($jml && in_array($xdata['idkurir'], $_POST['ids'])) ? ' class="selected"' : '';
echo "<tr$selected>
	<td>$no.</td>
	<td><input type=\"checkbox\" name=\"ids[]\" class=\"check-$no input-check\" value=\"$xdata[idkurir]\"$checked />$xdata[kurir]</td>
	<td>$xdata[keterangan]</td>
	<td class=c>$st</td>
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
<form action="<?php echo MODULE_URI,'/',$slug1 ?>" method="post">
<input type="hidden" name="source" value="<?php echo $data['idkurir']+0 ?>" />
<table>
<tr>
	<td class=r width=100>&nbsp;</td>
	<td><strong>[<?php echo $data['idkurir']>0 ? 'Ubah Data' : 'Tambah Baru' ?>]</strong></td>
</tr>
<tr>
	<td class=r><label for="nama">Nama Kurir :&nbsp;</label></td>
	<td><input type="text" id="nama" name="kurir" size="25" value="<?php echo $data['kurir'] ?>" placeholder="Nama Kurir" required /></td>
</tr>
<tr>
	<td class=r><label for="web">URL Tracking :&nbsp;</label></td>
	<td><input type="text" id="web" name="website" size="25" value="<?php echo $data['website'] ?>" placeholder="http://" /></td>
</tr>
<tr>
	<td class=r><label for="keterangan">Keterangan :&nbsp;</label></td>
	<td><input type="text" id="keterangan" name="keterangan" size="25" value="<?php echo $data['keterangan'] ?>" placeholder="Keterangan" /></td>
</tr>
<tr>
	<td class=r><label for="logo">Logo :&nbsp;</label></td>
	<td>
<table>
<tr>
<td colspan=2><label><input type="radio" name="class" value="nologo"<?php echo $data['class']=='' ? ' checked' : '' ?> />No Icon</label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="tiki"<?php echo $data['class']=='tiki' ? ' checked' : '' ?> /><i class="icon icon-logo tiki"></i></label></td>
<td><label><input type="radio" name="class" value="jne"<?php echo $data['class']=='jne' ? ' checked' : '' ?> /><i class="icon icon-logo jne"></i></label></td>
</tr>
<tr>
<td><label><input type="radio" name="class" value="dhl"<?php echo $data['class']=='dhl' ? ' checked' : '' ?> /><i class="icon icon-logo dhl"></i></label></td>
<td><label><input type="radio" name="class" value="gojek"<?php echo $data['class']=='gojek' ? ' checked' : '' ?> /><i class="icon icon-logo gojek"></i></label></td>
</tr>
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