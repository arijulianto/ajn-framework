<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');
$jml = count($_POST['ids']);
$num_data = $jml>1 ? " $jml" : '';

if(isset($_POST['done'])){
	$act = $_POST['act'];
	$arrID = "'".str_replace(',',"','",$_POST['src'])."'";
	$where_slider = strpos($arrID, ','	) ? "idslider IN($arrID)" : "idslider=$arrID";

	if($act=='activate'){
		$db->query("UPDATE tbl_slider set aktif='1' where $where_slider");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Gagal di'.$aAksi2[$act];
	}elseif($act=='deactivate'){
		$db->query("UPDATE tbl_slider set aktif='0' where $where_slider");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Gagal di'.$aAksi2[$act];
	}elseif($act=='delete'){
		$im = $db->query("SELECT slider from tbl_slider where $where_slider")->results();
		$db->query("DELETE from tbl_slider where $where_slider");
		if($db->iAffectedRows>0){
			$msg['sukses'] = 'Berhasil di'.$aAksi2[$act];
			foreach($im as $img){
				if(is_file(MEDIA_DIR.'slider/'.$img['slider'])) unlink(MEDIA_DIR.'slider/'.$img['slider']);
			}
		}else{
			$msg['warning'] = 'Gagal di'.$aAksi2[$act];
		}
	}
}

?><form class="action-bar" action="<?php echo MODULE_URI ?>">
<a class="btn" href="<?php echo MODULE_URI ?>/new">[+] Input Baru</a>
<input type="text" name="q" class="float-right search-table" value="" autocomplete="off" placeholder="Cari..." required />
</form>
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
<input type=\"hidden\" name=\"src\" value=\"",implode(',',$_POST['ids']),"\" />
<p><input type=\"submit\" name=\"done\" value=\" Yes \" class=\"btn btn-primary\" /> <input type=\"button\" value=\" No \" class=\"btn btn-inverse\" onclick=\"location.href='",MODULE_URI,"'\" /></p>
</fieldset><br />
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
	<th width=200>Gambar</th>
	<th data-sort="string-ins">Keterangan</th>
	<th width=80 data-sort="int">Urutan</th>
	<th width=80>Status</th>
	<th width="150">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$status = array('Tidak Aktif','Aktif');
$datas = $db->query("SELECT idslider,slider,judul,keterangan,urutan,aktif from tbl_slider order by aktif,urutan limit $start,$limit")->results();
$jml_data = $db->query("SELECT count(idslider) as jml from tbl_slider")->result();

foreach($datas as $no=>$data){
$no++;
$st = $status[$data['aktif']];
$id = encode('slide'.$data['idslider']);
$checked = ($jml && in_array($data['idslider'], $_POST['ids'])) ? ' checked' : '';
$selected = ($jml && in_array($data['idslider'], $_POST['ids'])) ? ' class="selected"' : '';
$data['judul'] = $data['keterangan'] ? "<strong>$data[judul]</strong>" : $data['judul'];
echo "<tr$selected>
	<td>$no.<br /><input type=\"checkbox\" name=\"ids[]\" class=\"check-$no input-check\" value=\"$data[idslider]\"$checked /></td>
	<td><img src=\"",SITE_URI,"media/slider/$data[slider]\" width=200 /></td>
	<td>$data[judul]<br />$data[keterangan]</td>
	<td class=c>$data[urutan]</td>
	<td class=c>$st</td>
	<td class=c><a href=\"",MODULE_URI,"/edit/$id\">Edit</a> &middot; <a href=\"",MODULE_URI,"/delete/$id\" class=\"actions\">Hapus</a></td>
</tr>\n";
}
?>
</tbody>
</table>
<?php paging($jml_data['jml']); ?>

<input type="submit" name="actions[activate]" value="Aktifkan" class="btn btn-success btn-small btn-action-bm"<?php echo $jml<1 ? ' disabled' : '' ?> />
<input type="submit" name="actions[deactivate]" value="Non-Aktifkan" class="btn btn-warning btn-small btn-action-bm"<?php echo $jml<1 ? ' disabled' : '' ?> />
<input type="submit" name="actions[delete]" value="Hapus" class="btn btn-danger btn-small btn-action-bm"<?php echo $jml<1 ? ' disabled' : '' ?> />
</form>
</div>
