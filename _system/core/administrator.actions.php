<?php
if($_POST){
	$theID = num2str($_POST['pid']);
	$theID = str_replace(MODULE,'',$theID);
	if($_POST['action']=='activate' && $theID>0){
		$do = $db->update($module['table'], array('aktif'=>'1'), array($module['id']=>$theID))->affectedRows();
		if($do)
			echo '<div class="actions alert alert-success">',$module['module'],' berhasil diaktifkan</div>';
		else
			echo '<div class="actions alert alert-warning">',$module['module'],' gagal diaktifkan</div>';
	}elseif($_POST['action']=='deactivate' && $theID>0){
		$do = $db->update($module['table'], array('aktif'=>'0'), array($module['id']=>$theID))->affectedRows();
		if($do)
			echo '<div class="actions alert alert-success">',$module['module'],' berhasil dinonaktifkan</div>';
		else
			echo '<div class="actions alert alert-warning">',$module['module'],' gagal dinonaktifkan</div>';
	}elseif($_POST['action']=='delete' && $theID>0){
		if($uploads){
			$files = $db->query("SELECT ".implode(',',array_keys($uploads))." from $module[table] WHERE $module[id]='$theID'")->result();
		}
		$do = $db->delete($module['table'], array($module['id']=>$theID))->affectedRows();
		if($do){
			if($files){
				$ff = array_keys($uploads);
				foreach($files as $file){
					foreach($uploads as $nm=>$o){
						@unlink($o.$file[$nm]);
					}
				}
			}
			echo '<div class="actions alert alert-success">',$module['module'],' berhasil dihapus</div>';
		}else{
			echo '<div class="actions alert alert-warning">',$module['module'],' gagal dihapus</div>';
		}
	}
}
?>
