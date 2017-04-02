<?php
$aAksi = array('activate'=>'mengaktifkan', 'deactivate'=>'menonaktifkan', 'delete'=>'menghapus');
$aAksi2 = array('activate'=>'aktifkan', 'deactivate'=>'nonaktifkan', 'delete'=>'hapus');
$jml = count($_POST['ids']);
$num_data = $jml>1 ? " $jml" : '';
$str_q = str_replace("'","\'",$_REQUEST['q']);
$str_q = str_replace("\\","\\\\",$str_q);


if($_REQUEST['view']=='3'){
	$tbl_name = 'tbl_datakota';
	$view_name = 'tbl_datakota';
	$the_id = 'kode_provinsi';
	$element = 'provinsi';
}elseif($_REQUEST['view']=='2'){
	$tbl_name = 'tbl_datakota';
	$view_name = 'tbl_datakota';
	$the_id = 'kode_kota';
	$element = 'kota';
	$element_filter = 'Provinsi';
}else{
	$tbl_name = 'tbl_datakecamatan';
	$view_name = 'view_kecamatan';
	$the_id = 'idkecamatan';
	$element = 'kecamatan';
	$element_filter = 'Kabupaten/Kota';
}

if($slug3){
	$theID = decode($slug3);
	$theID = str_replace('kota','',$theID);
}


if(isset($_POST['done'])){
	$act = $_POST['act'];
	$arrID = "'".str_replace(',',"','",$_POST['src'])."'";
	$where = strpos($arrID, ','	) ? "$the_id IN($arrID)" : "$the_id=$arrID";

	if($act=='activate'){
		$db->query("UPDATE $tbl_name set aktif='1' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Data '.$element.' berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Data '.$element.' gagal di'.$aAksi2[$act];
	}elseif($act=='deactivate'){
		$db->query("UPDATE $tbl_name set aktif='0' where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Data '.$element.' berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Data '.$element.' gagal di'.$aAksi2[$act];
	}elseif($act=='delete'){
		$db->query("DELETE from $tbl_name where $where");
		if($db->iAffectedRows>0)
			$msg['sukses'] = 'Data '.$element.' berhasil di'.$aAksi2[$act];
		else
			$msg['warning'] = 'Data '.$element.' gagal di'.$aAksi2[$act];
	}
}



// data combo
$where_kota = $_REQUEST['p']!='' ? " where kode_provinsi='$_REQUEST[p]'" : '';
$where_kota = trim($_REQUEST['q'])!='' ? ($where_kota ? "$where_kota AND kota like '%$_REQUEST[q]%'" : "where kota like '%$_REQUEST[q]%'") : $where_kota;

// kota provinsi
$provkotas = $db->query("SELECT kode_kota,kota,kode_provinsi,provinsi from tbl_datakota order by kode_kota")->results();
foreach($provkotas as $pk){
	$data_prov[$pk['kode_provinsi']] = $pk['provinsi'];
	$data_kota[$pk['provinsi']][$pk['kode_kota']] = $pk['kota'];
	$data_kota2[$pk['kode_provinsi'].'/'.$pk['provinsi']][$pk['kode_kota']] = $pk['kota'];
	$data_kota_raw[$pk['kode_kota']] = $pk['kota'];
}


if($_REQUEST['view']=='1' || empty($_REQUEST['view'])){
	if($_REQUEST['q']) $where_cari[] = "kecamatan like '%$str_q%'";
	if($_REQUEST['p']) $where_cari[] = strlen($_REQUEST['p'])==2 ? "kode_provinsi='$_REQUEST[p]'" : "kode_kota='$_REQUEST[p]'";
	$where_cari = $where_cari ? 'where '.implode(' AND ',$where_cari) : '';
	$str_sql_data = "SELECT idkecamatan,kecamatan,kode_kota,kota,kode_provinsi,provinsi,aktif,aktif_kota from view_kecamatan $where_cari order by kode_kota,kecamatan limit $start,$limit";
	$str_sql_jml = "SELECT count(idkecamatan) as jml from tbl_datakecamatan $where_cari";
}
elseif($_REQUEST['view']=='2'){
	if($_REQUEST['q']) $where_cari[] = "provinsi like '%$str_q%'";
	if(strlen($_REQUEST['p'])==2) $where_cari[] = "kode_provinsi='$_REQUEST[p]'";
	$where_cari = $where_cari ? 'where '.implode(' AND ',$where_cari) : '';
	$str_sql_data = "SELECT kode_kota,kota,kode_provinsi,provinsi,aktif from tbl_datakota $where_cari order by kode_provinsi,kode_kota limit $start,$limit";
	$str_sql_jml = "SELECT count(kode_kota) as jml from tbl_datakota $where_cari";
}
else{
	if($_REQUEST['q']) $where_cari = "where provinsi like '%$str_q%'";
	$str_sql_data = "SELECT kode_provinsi,provinsi from tbl_datakota $where_cari group by kode_provinsi order by kode_provinsi limit $start,$limit";
	$str_sql_jml = "SELECT count(kode_provinsi) as jml from tbl_datakota $where_cari group by kode_provinsi";
}


$data = $db->query($str_sql_data)->results();
$total = $db->query($str_sql_jml)->result();

?><h1 class="title">Data Kota</h1>
<form class="action-bar" action="<?php echo MODULE_URI,'/',$slug1 ?>" method="get">
<a class="btn disabled">[+] Input Baru</a>
<input type="text" name="q" class="float-right search-table" value="<?php echo $_REQUEST['q'] ?>" autocomplete="off" placeholder="Cari..." />
<?php if($_REQUEST['view']!='3'){ ?>
<select name="p" class="auto-submit float-right"><option value="">=[Semua <?php echo ucfirst($element_filter) ?>]=</option><?php
if($_REQUEST['view']=='1' || empty($_REQUEST['view'])){
	foreach($data_kota2 as $prov=>$kota){
		$sel_kota1 = strstr($prov,'/',true)==$_REQUEST['p'] ? ' selected' : '';
		echo "<option value=\"",strstr($prov,'/',true),"\"$sel_kota1>",substr(strrchr($prov,'/'),1),"</option>";
		foreach($kota as $kode=>$nama){
			$sel_kota2 = $kode==$_REQUEST['p'] ? ' selected' : '';
			echo "<option value=\"$kode\"$sel_kota2>&nbsp; &nbsp;$nama</option>";
		}
	}
}elseif($_REQUEST['view']=='2'){
	foreach($data_prov as $kode=>$nama){
		$sel_prov = $kode==$_REQUEST['p'] ? ' selected' : '';
			echo "<option value=\"$kode\"$sel_prov>$nama</option>";
	}
}
?></select>
<?php } ?>
<select name="view" class="auto-submit float-right"><?php
$v = array(1=>'Kecamatan','Kota','Provinsi');
foreach($v as $k=>$s){
	$sel_view = $k==$_REQUEST['view'] ? ' selected' : '';
	echo '<option value="',$k,'"',$sel_view,'>',$s,'</option>';
}
?></select>
</form>
<div class="page">
<?php
if($_POST['actions']){
if($_POST['actions']['activate']) $slug2 = 'activate';
if($_POST['actions']['deactivate']) $slug2 = 'deactivate';
if(!$msg['sukses'])
echo "<form action=\"",MODULE_URI,"/$slug1\" method=\"post\">
<fieldset>
<p>Anda yakin ingin $aAksi[$slug2]$num_data $element yang dipilih?</p>
<input type=\"hidden\" name=\"p\" value=\"$_REQUEST[p]\" />
<input type=\"hidden\" name=\"view\" value=\"$_REQUEST[view]\" />
<input type=\"hidden\" name=\"act\" value=\"$slug2\" />
<input type=\"hidden\" name=\"src\" value=\"",$slug3 ? $theID : implode(',',$_POST['ids']),"\" />
<p><input type=\"submit\" name=\"done\" value=\" Yes \" class=\"btn btn-primary\" /> <input type=\"button\" value=\" No \" class=\"btn btn-inverse\" onclick=\"location.href='",MODULE_URI,"/$slug1'\" /></p>
</fieldset>
</form><br />";
}
?>
<form action="<?php echo MODULE_URI,'/',$slug1 ?>" method="post">
<?php
if($msg['sukses']){

	echo '<p class="sukses">',$msg['sukses'],'</p>';
}elseif($msg['warning']){
	echo '<p class="warning">',$msg['warning'],'</p>';
}

if($slug2=='edit'){ ?>
<fieldset>
<legend>Edit Biaya Pengiriman</legend>
<form action="<?php echo MODULE_URI,'/',$slug1 ?>" method="post">
<input type="hidden" name="source" value="<?php echo $data['idkurir']+0 ?>" />
<table>
<tr>
	<td class=r><label>Lokasi :&nbsp;</label></td>
	<td><label class="input">Kecamatan <?php echo $data['kecamatan'],', ',$data['kota'],' - ',$data['provinsi'] ?></label></td>
</tr>
<tr>
	<td class=r><label for="biaya">Biaya Pengiriman :&nbsp;</label></td>
	<td><input type="number" id="biaya" name="biaya_kirim" size="12" value="<?php echo $data['biaya'] ?>" placeholder="Biaya Pegiriman" required /></td>
</tr>
<tr>
	<td class=r><label for="waktu">Waktu Pengiriman :&nbsp;</label></td>
	<td><input type="text" id="waktu" name="waktu_kirim" size="12" value="<?php echo $data['waktu_kirim'] ?>" placeholder="Waktu Pengiriman" /> hari</td>
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
</fieldset><br />
<?php } ?>
<table class="table table-bordered table-striped table-hover table-grid table-data">
<thead>
<tr>
	<th width="30">No.</th>
	<th width=20><input type="checkbox" class="checkAll" /></th>
	<th>Kecamatan</th>
	<th>Kota</th>
	<th>Provinsi</th>
	<th>Status</th>
	<th width=120>Ket.</th>
</tr>
</thead>
<tbody>
<?php
$status = array('Tidak Aktif','Aktif');
$no = $_GET['page']>1 ? (($limit*$_GET['page'])-$limit)+1 : 1;

if($data){
foreach($data as $xdata){
$st = $status[$xdata['aktif']].(isset($xdata['aktif_kota']) ? ' / '.$status[$xdata['aktif_kota']] : '');
$id = encode('kota'.$xdata['idkotajne']);
$checked_kec = ($jml && in_array($xdata['idkecamatan'], $_POST['ids'])) ? ' checked' : '';
$checked_kota = ($jml && in_array($xdata['kode_kota'], $_POST['ids'])) ? ' checked' : '';
$checked_prov = ($jml && in_array($xdata['kode_provinsi'], $_POST['ids'])) ? ' checked' : '';
$selected = ($jml && in_array($xdata['idkotajne'], $_POST['ids'])) ? ' class="selected"' : '';
$waktu = $xdata['waktu_kirim']>0 ? '['.$xdata['waktu_kirim'].' hari] &middot; ' : '';
if($_REQUEST['view']=='3')
	$input_cek = "<input type=\"checkbox\" name=\"ids[]\" data-sub=\"kec\" class=\"check-$no input-check\" value=\"$xdata[kode_provinsi]\"$checked_prov />";
elseif($_REQUEST['view']=='2')
	$input_cek = "<input type=\"checkbox\" name=\"ids[]\" data-sub=\"kec\" class=\"check-$no input-check\" value=\"$xdata[kode_kota]\"$checked_kota />";
else
	$input_cek = "<input type=\"checkbox\" name=\"ids[]\" data-sub=\"kec\" class=\"check-$no input-check\" value=\"$xdata[idkecamatan]\"$checked_kec />";
echo "<tr$selected>
	<td>$no.</td>
	<td>$input_cek</td>
	<td>$xdata[kecamatan]</td>
	<td>$xdata[kota]</td>
	<td>$xdata[provinsi]</td>
	<td class=c>$st</td>
	<td class=r>$waktu<a href=\"",MODULE_URI,"/$slug1/edit/$id\">Edit</a></td>
</tr>\n";
$no++;
}
}else{
if($_REQUEST['p']) $lokasi_filter = ' di '.$data_kota_raw[$_REQUEST['p']];
echo '<tr>
	<td colspan=7><p class="warning">Tidak ada data ',$element,$lokasi_filter,' ditemukan!</p></td>
</tr>
';
}
?>
</tbody>
</table>
<input type="hidden" name="p" value="<?php echo $_REQUEST['p'] ?>" /><input type="hidden" name="view" value="<?php echo $_REQUEST['view'] ?>" />
<p><input type="submit" name="actions[activate]" value="Aktifkan" class="btn btn-success btn-small btn-action-bm"<?php echo !$num_data ? ' disabled' : '' ?> />
<input type="submit" name="actions[deactivate]" value="Non-Aktifkan" class="btn btn-warning btn-small btn-action-bm"<?php echo !$num_data ? ' disabled' : '' ?> />

<?php paging($total['jml'], 20); ?>

</form>



