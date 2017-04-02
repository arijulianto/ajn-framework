<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');
$jml = count($_POST['ids']);
$num_data = $jml>1 ? " $jml" : '';

if(isset($_POST['done'])){
	$act = $_POST['act'];
	$arrID = "'".str_replace(',',"','",$_POST['src'])."'";
	$where = strpos($arrID, ','	) ? "iduser IN($arrID)" : "iduser=$arrID";

	if($act=='activate'){
		$db->query("UPDATE tbl_user set aktif='1' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$act];
	}elseif($act=='deactivate'){
		$db->query("UPDATE tbl_user set aktif='0' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$act];
	}elseif($act=='delete'){
		$db->query("DELETE from tbl_user where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Customer berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Customer gagal di'.$aAksi2[$act];
	}
}

?><div class="action-bar">
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
	<th data-sort="string">Nama Lengkap</th>
	<th width=15 data-sort="string">L/P</th>
	<th data-sort="string">Kota</th>
	<th data-sort="string">Tgl Lahir</th>
	<th data-sort="int">Status</th>
	<th width="150">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$status = array('Tidak Aktif','Aktif');
$no = $_GET['page']>1 ? (($limit*$_GET['page'])-$limit)+1 : 1;
$datas = $db->query("SELECT iduser,nama,user_email,gender,kota,tgl_lahir,aktif from view_datauser where posisi='10' order by aktif limit $start,$limit")->results();
$jml_data = $db->query("SELECT count(iduser) as jml from tbl_user where posisi='10'")->result();


foreach($datas as $data){
$st = $status[$data['aktif']];
$id = str2hex('cus'.$data['iduser']);
$checked = ($jml && in_array($data['iduser'], $_POST['ids'])) ? ' checked' : '';
$selected = ($jml && in_array($data['iduser'], $_POST['ids'])) ? ' class="selected"' : '';
echo "<tr$selected>
	<td>$no.</td>
	<td><input type=\"checkbox\" name=\"ids[]\" class=\"check-$no input-check\" value=\"$data[iduser]\"$checked />$data[nama] <small>($data[user_email])</small></td>
	<td class=c>$data[gender]</td>
	<td>$data[kota]</td>
	<td data-sort-value=\"$data[tgl_lahir]\">",$data['tgl_lahir']!='0000-00-00' ? tanggal('j-M-Y',$data['tgl_lahir']) : '&nbsp;',"</td>
	<td class=c data-sort-value=\"$data[aktif]\">$st</td>
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