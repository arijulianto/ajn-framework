<?php
if($slug2){
	$theID = num2str($slug2);
	$theID = str_replace(MODULE,'',$theID);
}
if($_POST){
	if($slug1=='edit' && in_array('edit', $mode)){
		$data_save = array();
		foreach(array_keys($forms) as $f){
			if($uploads[$f]){
				if($_FILES[$f]['size']>0){
					if(move_uploaded_file($_FILES[$f]['tmp_name'], $uploads[$f]['dir'].$_FILES[$f]['name']))
						$data_save[$f] = $_FILES[$f]['name'];
				}else{
					$data_save[$f] = $_POST['file_ori'];
				}
			}else{
				$data_save[$f] = $_POST[$f];// ? $_POST[$f] : '';
			}
		}
		//unset($data_save[$module['id']]);
		$save = $db->update($module['table'], $data_save, array($module['id']=>$theID))->affectedRows();
		if($save){
			$msg['sukses'] = 'Konten '.$module['module'].' berhasil diperbaharui';
			if($uploads && $_POST['file_ori']){
				$fn = array_keys($uploads)[0];
				$ext = strrchr($_POST['file_ori'], '.');
				$name = substr_replace($_POST['file_ori'],'',-strlen($ext), strlen($ext));
				if($data_save[$fn] && $data_save[$fn]!=$_POST['file_ori']){
					@unlink($uploads[$fn]['dir'].$_POST['file_ori']);
					@unlink($uploads[$fn]['dir'].$name.'_small'.$ext);
				}
			}
		}else{
			$msg['warning'] = 'Konten '.$module['module'].' gagal diperbaharui atau tidak ada data yang diubah. Silahkan periksa ulang inputan Anda!';
		}
	}elseif($slug1=='new' && in_array('new', $mode)){
		$data_save = array();
		foreach(array_keys($forms) as $f){
			if($uploads[$f]){
				if($_FILES[$f]['size']>0){
					if(move_uploaded_file($_FILES[$f]['tmp_name'], $uploads[$f]['dir'].$_FILES[$f]['name']))
						$data_save[$f] = $_FILES[$f]['name'];
				}
			}else{
				$data_save[$f] = $_POST[$f];
			}
		}
		if($module['track']){
			$data_save['iduser'] = $_SESSION['admin_uid'];
			$data_save['tgl'] = date('Y-m-d H:i:s');
		}
		$save = $db->insert($module['table'], $data_save)->getLastInsertId();
		if($save)
			$msg['sukses'] = 'Konten '.$module['module'].' baru berhasil ditambhahkan';
		else
			$msg['warning'] = 'Konten '.$module['module'].' gagal ditambahkan. Silahkan periksa ulang inputan Anda, lalu coba lagi!';
	}
}
if($slug1=='edit' && in_array('edit', $mode)){
	$data = $db->query("SELECT ".implode(',',$fields)." from $module[view] WHERE $module[id]='$theID'")->result();

}elseif($slug1=='new' && in_array('new', $mode)){
	$data = array();
	foreach($fields as $f){
		$data[$f] = $_POST[$forms[$f]['name']] ? $_POST[$forms[$f]['name']] : '';
	}
}

echo '<form action="" method="post"',($module['upload'] ? ' enctype="multipart/form-data"' : ''),'>
<div class="window-top">
<h3>';
if($slug1=='edit')
	echo 'Edit';
elseif($slug1=='new')
	echo $module['input'];
echo ' ',$module['module'],'</h3>
</div>
<div class="workspace">
';

if($msg['sukses'])
	echo '<p class="alert alert-success">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="alert alert-warning">',$msg['warning'],'</p>';

echo '<table class="table-form">',"\n";

foreach($forms as $field=>$finfo){
	if($finfo['label']=='(hidden)'){
		echo "<tr style=\"display:none\"><td>&nbsp;</td><td><input type=\"hidden\" name=\"$finfo[name]\" value=\"$data[$field]\" /></td></tr>\n";
	}else{
		echo "<tr>\n\t<td><label>$finfo[label] :</label></td>\n\t<td>";
		// TEXTAREA
		if($finfo['type']=='textarea'){
			echo "<textarea name=\"$finfo[name]\" $finfo[attr]>$data[$field]</textarea>";
		}
		// RADIO / CHECKBOX
		elseif($finfo['type']=='radio' || $finfo['type']=='checkbox'){
			$cr = array();
			foreach($finfo['prop'] as $k=>$v)
				$cr[] = "<label><input type=\"$finfo[type]\" name=\"$finfo[name]\" value=\"$k\"$finfo[attr]".((!empty($data[$field]) && $k==$data[$field])?' checked' : '')." /> $v</label>";
			echo '<p>',implode(' &nbsp; ', $cr),'</p>';
		}
		// COMBO
		elseif($finfo['type']=='combo'){
			$cr = array();
			foreach($finfo['prop'] as $k=>$v)
				$cr[] = "<option value=\"$k\"$finfo[attr]".($v==$data[$field]?' selected' : '').">$v</option>\n";
			echo "<select name=\"\"$finfo[attr]>\n",implode("\t", $cr),"</select>\n";
		}
		// FILE
		elseif($finfo['type']=='file'){
			$img = $data[$field] ? substr_replace(strrchr($data[$field], '.'),'',0,1) : '';
			echo '<div><label>';
			if($finfo['prop']['type']=='image'){
				if($slug1=='edit'){
					if(is_file($uploads[$field]['dir'].$data[$field])){
						$ext = strrchr($data[$field], '.');
						$name = substr_replace($data[$field],'',-strlen($ext), strlen($ext));
						if(is_file($uploads[$field]['dir'].$name.'_small'.$ext))
							$img_url = $uploads[$field]['url'].$name.'_small'.$ext;
						else
							$img_url = $uploads[$field]['url'].$data[$field];
					}else{
						$img_url = MEDIA_URI.'images/__nopic_thumb.jpg';
					}
					echo "<img src=\"$img_url\" title=\"Klik untuk mengganti gambar!\" class=\"img-preview\" />";
				}
			}
			echo "<input type=\"$finfo[type]\" name=\"$finfo[name]\" $finfo[attr] /></label>
			<input type=\"hidden\" name=\"file_ori\" value=\"$data[$field]\" />";
			echo '</div>';
		}else{
			echo "<input type=\"$finfo[type]\" name=\"$finfo[name]\" value=\"$data[$field]\" $finfo[attr] />";
		}
		echo "</td>\n</tr>\n";
	}
}

echo '</table>
</div>
<div class="window-bottom">
	<table class="table-form">
	<tr>
		<td>&nbsp;</td>
		<td><input type="submit" class="btn btn-primary btn-large" value=" Simpan " /> &nbsp; <a href="',MODULE_URI,'" class="btn btn-inverse btn-large">&laquo; Kembali</a></td>
	</tr>
	</table>
</div>
</form>
';
