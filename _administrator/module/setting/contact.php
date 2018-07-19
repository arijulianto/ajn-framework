<div class="row">
<div class="col-md-4">
<form action="<?php echo MODULE_URI ?>/contact" method="post" class="form-sosmed">
<fieldset>
<legend><span class="title-action">Tambah</span> Sosmed</legend>
<?php
if($_POST){
	$data_save = array();
	$data_save['sosmed'] = $_POST['nama'];
	$data_save['url'] = $_POST['url'];
	$data_save['logo'] = $_POST['fa'];
	$data_save['aktif'] = $_POST['status']=='1' ? '1' : '0';

	if($_POST['pid'] && $_POST['action']){
		$pid = num2str($_POST['pid']);
		$pid = str_replace('ids','',$pid);
		if($_POST['action']=='activate'){
			$do = $db->update('tma_sosmed', array('aktif'=>'1'), array('idsosmed'=>$pid))->affectedRows();
			if($do)
				$msg['sukses'] = 'Sosial Media berhasil diaktifkan';
			else
				$msg['warning'] = 'Sosial Media gagal diaktifkan. Silahkan coba lagi!';
		}elseif($_POST['action']=='deactivate'){
			$do = $db->update('tma_sosmed', array('aktif'=>'0'), array('idsosmed'=>$pid))->affectedRows();
			if($do)
				$msg['sukses'] = 'Sosial Media berhasil dinonaktifkan';
			else
				$msg['warning'] = 'Sosial Media gagal dinonaktifkan. Silahkan coba lagi!';
		}elseif($_POST['action']=='delete'){
			$do = $db->delete('tma_sosmed', array('idsosmed'=>$pid))->affectedRows();
			if($do)
				$msg['sukses'] = 'Sosial Media berhasil dihapus';
			else
				$msg['warning'] = 'Sosial Media gagal dihapus. Silahkan coba lagi!';
			
		}
	}elseif($_POST['pid']){
		$pid = num2str($_POST['pid']);
		$pid = str_replace('ids','',$pid);
		$cek = $db->query("SELECT idsosmed from tma_sosmed WHERE sosmed='$_POST[nama]' AND idsosmed!='$pid'")->result();
		if($cek['idsosmed']){
			$msg['failed'] = '<strong>'.$_POST['nama'].'</strong> sudah ada. Silahkan pilih yang lain!';
		}else{
			$save = $db->update('tma_sosmed', $data_save, array('idsosmed'=>$pid))->affectedRows();
			if($save)
				$msg['sukses'] = 'Sosial Media berhasil diperbaharui';
			else
				$msg['warning'] = 'Sosial Media gagal diperbaharui atau tidak ada data yang diubah. Silahkan coba lagi!';
		}
	}else{
		$cek = $db->query("SELECT idsosmed from tma_sosmed WHERE sosmed='$_POST[nama]'")->result();
		if($cek['idsosmed']){
			$msg['failed'] = '<strong>'.$_POST['nama'].'</strong> sudah ada. Silahkan pilih yang lain!';
		}else{
			//$data_save['iduser'] = $_SESSION['admin_uid'];
			//$data_save['tgl'] = date('Y-m-d H:i:s');
			$save = $db->insert('tma_sosmed', $data_save)->getLastInsertId();
			if($save)
				$msg['sukses'] = 'Sosial Media baru berhasil ditambahkan';
			else
				$msg['warning'] = 'Sosial Media baru gagal ditambahkan. Silahkan coba lagi!';
		}
	}

	if($_POST['action']){
		if($msg['sukses'])
			echo '<p class="alert actions alert-success">',$msg['sukses'],'</p>';
		elseif($msg['warning'])
			echo '<p class="alert actions alert-warning">',$msg['warning'],'</p>';
		elseif($msg['failed'])
			echo '<p class="alert actions alert-danger">',$msg['failed'],'</p>';
	}else{
		if($msg['sukses'])
			echo '<p class="alert alert-success">',$msg['sukses'],'</p>';
		elseif($msg['warning'])
			echo '<p class="alert alert-warning">',$msg['warning'],'</p>';
		elseif($msg['failed'])
			echo '<p class="alert alert-danger">',$msg['failed'],'</p>';
	}
}
?>
<input type="hidden" name="pid" value="<?php echo $_POST['pid'] ?>" />
<p><label><strong>Nama Sosmed</strong></label></p>
<p><input type="text" name="nama" class="input-block" value="<?php echo $_POST['nama'] ?>" placeholder="Nama Sosmed" required /></p>

<p style="padding-top:8px"><label><strong>URL Profil</strong></label></p>
<p><input type="url" name="url" class="input-block" value="<?php echo $_POST['url'] ?>" placeholder="URL Profil atau Website" required /></p>

<p style="padding-top:8px"><label><strong>Ikon Sosmed</strong></label></p>
<p>
	<i class="fa fa-icon<?php echo $_POST['fa'] ? ' fa-'.$_POST['fa'] : '' ?>"></i> <input type="text" name="fa" size="10" class="fa-typing" value="<?php echo $_POST['fa'] ?>" placeholder="fa-[icon]" required />
	<small><i class="fa fa-question-circle"></i> FontAwesome4.7.0 <a href="https://fontawesome.com/v4.7.0/icons/" target="_blank"><i class="fa fa-external-link"></i></a></small>
</p>

<p style="padding-top:6px"><label><strong>Status</strong></label></p>
<p>
	<label><input type="radio" name="status" value="1"<?php echo $_POST['status']=='1' ? ' checked' : '' ?> required /> Aktif</label> &nbsp; 
	<label><input type="radio" name="status" value="0"<?php echo $_POST['status']=='0' ? ' checked' : '' ?> required /> Tidak Aktif</label>
</p>
<hr />
<p class=c>
	<input type="submit" class="btn btn-primary btn-save" value="<?php echo $_POST['pid'] ? 'Simpan Perubahan' : 'Tambahkan' ?>" />
	<a class="btn btn-inverse btn-new"<?php echo !$_POST['pid'] ? ' style="display:none"' : '' ?>>Baru</a>
</p>
</fieldset>
</form><br />
</div>
<div class="col-md-8">
<div class="table-responsive">
<table class="table table-grid table-bordered table-hover">
<thead>
<tr>
	<th width=15>No</th>
	<th colspan=2>Sosial Media</th>
	<th>Status</th>
	<th style="min-width:70px">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$status = array('Tidak Aktif','Aktif');
$sosmed = $db->query("SELECT idsosmed,sosmed,url,logo,aktif from tma_sosmed order by aktif,sosmed")->results();
$jml = $db->query("SELECT count(idsosmed) as jml from tma_sosmed")->result();
if($sosmed){
foreach($sosmed as $row){
$action = $row['aktif'] ? array('deactivate', 'Nonaktifkan') : array('activate','Aktifkan');
$idstr = str2num('ids'.$row['idsosmed']);
$datadb = array('pid'=>str2num('ids'.$row['idsosmed']), 'nama'=>$row['sosmed'], 'url'=>$row['url'], 'fa'=>$row['logo'], 'status'=>$row['aktif']);
$datadb = base64_encode(array2json($datadb));
echo "<tr>
	<td>$no<input type=\"hidden\" name=\"cek[$kat[idkategori]]\" /></td>
	<td class=c width=60><i class=\"fa fa-$row[logo] fa-2x\"></i></td>
	<td>$row[sosmed]<br /><small>URL: $row[url]</small></td>
	<td class=c>",$status[$row['aktif']],"</td>
	<td class=c>
		<a data-db=\"$datadb\" data-form=\".form-sosmed\" class=\"itm-action edit\">Edit</a> &middot; 
		<a class=\"do-action $action[0]\" data-pid=\"$idstr\" data-module=\"",MODULE,"/contact\" data-type=\"Sosial Media\">$action[1]</a> &middot; 
		<a class=\"do-action delete\" data-pid=\"$idstr\" data-module=\"",MODULE,"/contact\" data-type=\"Sosial Media\">Hapus</a>
	</td>
</tr>\n";
$no++;
}
}
?>
</tbody>
</table>
</div>
<?php
if($jml['jml']>$limit){
	paging($jml['jml']);
}
?>
</div>
</div>