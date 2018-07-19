<?php
$where = array();
if($module['where']){
	$where[] = $module['where'];
}
if($module['filter'] || $module['input']){
echo '<form action="" class="action-bar">',"\n";
if($module['filter']){
	foreach($module['filter'] as $fld=>$arr){
		foreach($arr as $type=>$opt){
			$opt = explode(':=>', $opt);
			$nm = $opt[0];
			$opt = explode('{', $opt[1]);
			$attr = rtrim($opt[1],'}');
			$opt = $opt[0];
			$attr = $attr ? ' '.$attr : '';
			$dt = explode('||', $opt);
			if($type=='combo'){
				if($_GET[$nm]!=''){
					$where[] = "$fld='$_GET[$nm]'";
				}
				echo "<select name=\"$nm\"$attr>";
				foreach($dt as $v){
					$ops = explode('=>', $v);
					if($ops[1])
						echo "<option value=\"$ops[0]\"",($_GET[$nm]==$ops[0]?' selected':''),">$ops[1]</option>";
					else
						echo "<option value=\"\">$ops[0]</option>";
				}
				echo "</select> ";
			}elseif($type=='search'){
				if($_GET[$nm]!=''){
					if(strpos($fld,'/')>=0){
						$qf = explode('/', $fld);
						$tmp = [];
						foreach($qf as $f){
							if($dt[0]=='%')
								$tmp[] = "$f like '%$_GET[$nm]%'";
							elseif(strtolower($dt[0])=='%s')
								$tmp[] = "$f like '%$_GET[$nm]'";
							elseif(strtolower($dt[0])=='s%')
								$tmp[] = "$f like '$_GET[$nm]%'";
						}
						$tmp = '('.implode(' OR ',$tmp).')';
						$where[] = $tmp;
					}else{
						if($dt[0]=='%')
							$where[] = "$fld like '%$_GET[$nm]%'";
						elseif(strtolower($dt[0])=='%s')
							$where[] = "$fld like '%$_GET[$nm]'";
						elseif(strtolower($dt[0])=='s%')
							$where[] = "$fld like '$_GET[$nm]%'";
					}
				}
				echo "<input type=\"search\" name=\"$nm\" value=\"",$_GET[$nm],"\"$attr placeholder=\"$dt[1]\" /> ";
			}elseif($type=='lookup'){
				if($_GET[$nm]!=''){
					$where[] = "$fld='{$_GET[$nm]}'";
				}
				echo "<select name=\"$nm\"$attr>";
				$df = explode('=>', $dt[1]);
				$res = $db->query("SELECT ".implode(',',$df)." from $dt[0]".($dt[2] ? " WHERE $dt[2]":''))->results();
				if($res){
				foreach($res as $row){
					if($row[$df[0]]==$_GET[$nm])
						echo "<option value=\"{$row[$df[0]]}\"",($_GET[$nm]==$row[$df[0]]?' selected':''),">{$row[$df[1]]}</option>";
					else
						echo "<option value=\"{$row[$df[0]]}\">{$row[$df[1]]}</option>";
				}
				}
				echo "</select> ";
			}
		}
	}
	echo '<input type="submit" class="btn btn-primary" value="Lihat Data" />',"\n";
}
if(in_array('new', $mode) && $module['input']){
	echo "<a href=\"",MODULE_URI,"/new\" class=\"btn btn-inverse",($module['filter'] ? ' float-right':''),"\">",$module['input'],"</a>";
}
echo '</form>',"\n";
}
?>
<div class="workspace">
<form action="" method="post">
<div class="table-responsive">
<table class="table table-bordered table-hover table-grid">
<thead>
<tr>
<?php
foreach($module['list_header'] as $i=>$tx){
	$eh = explode('//', $tx);
	if(isset($eh[1])){
		if($eh[1]==0)
			$hw = ' style="display:none"';
		else
			$hw = ' width='.$eh[1];
	}else{
		$hw = '';
	}
	if($module['list_multiaction'] && $i==0)
		echo "\t<th$hw>$eh[0]</th>\n\t<th width=10><input type=\"checkbox\" class=\"checkAll\" /></th>\n";
	else
		echo "\t<th$hw>$eh[0]</th>\n";
}

$where = $where ? ' WHERE '.implode(' AND ', $where) : '';
$order_sort = $module['list_order'] ? 'order by '.$module['list_order'] : '';
$str_query = "SELECT ".implode(',',$fields)." from $module[table]$where $order_sort limit $start,$limit";
$jml = $db->query("SELECT count($module[id]) as jml from $module[table] $where")->result();
//debug($str_query);
$data = $db->query($str_query);

if($db->aResults)
	$data = $data->results();
else
	$data = [];

echo '</tr>
</thead>
<tbody>
';
if($data){
foreach($data as $row){
	echo "<tr>\n";
	foreach($module['list_data'] as $i=>$col){
		$align = $module['list_align'][$col] ? ' class='.substr($module['list_align'][$col],0,1) : '';
		if($module['vars'][$col])
			$row[$col] = $module['vars'][$col][$row[$col]];
		else
			$row[$col] = $row[$col];
		if($module['list_format'][$col]){
			$ef = explode('(', str_replace(')','',$module['list_format'][$col]));
			if($ef[0]=='image'){
				if(is_file($uploads[$field]['dir'].$row[$col])){
					$ext = strrchr($row[$col], '.');
					$name = substr_replace($row[$col],'',-strlen($ext), strlen($ext));
					if(is_file($uploads[$field]['dir'].$name.'_small'.$ext))
						$img_url = $uploads[$field]['url'].$name.'_small'.$ext;
					else
						$img_url = $uploads[$field]['url'].$row[$col];
				}else{
					$img_url = MEDIA_URI.'images/__nopic_thumb.jpg';
				}
				$val = '<img src="'.$img_url.'" '.($forms[$col]['prop']['width']?' width='.$forms[$col]['prop']['width']:' width=80').' />';
			}elseif($ef[0]=='bold'){
				$val = "<strong>$row[$col]</strong>";
			}elseif($ef[0]=='italic'){
				$val = "<em>$row[$col]</em>";
			}elseif($ef[0]=='underline'){
				$val = "<u>$row[$col]</u>";
			}elseif($ef[0]=='small'){
				$val = "<small>$row[$col]</small>";
			}elseif($ef[0]=='date'){
				$val = tanggal($ef[1], $row[$col]);
			}elseif($ef[0]=='lowercase'){
				$val = strtolower($row[$col]);
			}elseif($ef[0]=='uppercase'){
				$val = strtoupper($row[$col]);
			}elseif($ef[0]=='titlecase'){
				$val = ucwords($row[$col]);
			}elseif($ef[0]=='ringkasan'){
				$val = $ef[1] ? ringkasan($row[$col], $ef[1]) : ringkasan($row[$col]);
			}elseif($ef[0]=='number'){
				$val = $ef[1] ? format_angka($row[$col], $ef[1]) : format_angka($row[$col]);
			}elseif($ef[0]=='concat'){
				$col = str_replace(array(' , ',', ',' ,'), ',', $ef[1]);
				$ce = explode(',', $col);
				foreach($ce as $i=>$rc){
					if($row[$rc]){
						$ce['data'][] = $row[$rc];
					}else{
						if(substr(strtolower($rc),0,10)=='ringkasan:'){
							$t = substr_replace($rc, '', 0, 10);
							$t = explode('*',$t);
							if($t[1])
								$trc = ringkasan($row[$t[0]], $t[1]);
							else
								$trc = ringkasan($row[$t[0]]);
							$ce['data'][] = $trc;
						}elseif(substr(strtolower($rc),0,5)=='bold:'){
							$t = substr_replace($rc, '', 0, 5);
							$ce['data'][] = '<strong>'.$row[$t].'</strong>';
						}else{
							$ce['data'][] = $rc;
						}
					}
				}
				$val = implode('', $ce['data']);
			}else{
				$val = $row[$col];
			}
		}else{
			$val = $row[$col];
		}

		if($i==0){
			if($module['list_multiaction'])
				echo "\t<td>$no</td>\n\t<td><input type=\"checkbox\" name=\"pid[]\" class=\"check-$no\" /></td>\n";
			else
				echo "\t<td>$no</td>\n";
		}elseif($col=='action'){
			$idstr = str2num(MODULE.$row[$module['id']]);
			echo "\t<td class=c>";
			if($mode){
				foreach($mode as $act){
					if($act=='edit'){
						$mode['data'][] = "<a href=\"".MODULE_URI."/edit/$idstr\">Edit</a>";
					}elseif($act=='delete'){
						$mode['data'][] = "<a class=\"do-action delete\" data-pid=\"$idstr\" data-type=\"".MODULE."\" data-href=\"".MODULE_URI."/delete/$idstr\">Delete</a>";
					}elseif($act=='activate'){
						if(in_array('aktif', $fields)){
							if($row['aktif'])
								$mode['data'][] = "<a class=\"do-action deactivate\" data-pid=\"$idstr\" data-type=\"".MODULE."\" data-href=\"".MODULE_URI."/deactivate/$idstr\">Nonaktifkan</a>";
							else
								$mode['data'][] = "<a class=\"do-action activate\" data-pid=\"$idstr\" data-type=\"".MODULE."\" data-href=\"".MODULE_URI."/activate/$idstr\">Aktifkan</a>";
						}
					}
				}
				echo implode(' &middot; ', $mode['data']);
				if($mode['data']) unset($mode['data']);
			}			
			echo "</td>\n";
		}else{
			if(substr($module['list_header'][$i],-3,3)=='//0')
				echo "\t<td style=\"display:none\">$val</td>\n";
			else
				echo "\t<td$align>$val</td>\n";
		}
	}
echo "</tr>\n";
$no++;
}
}else{
echo "<tr>
	<td colspan=",count($module['list_data']),"><p class=\"warning\">Tida ada data ",$module['module'],"</p></td>
</tr>\n";
}
?>
</tbody>
</table>
<?php if($module['list_multiaction']){ ?>
<input type="submit" class="btn btn-success btn-small btn-cek" value="Aktifkan" disabled /> <input type="submit" class="btn btn-warning btn-small btn-cek" value="Nonaktifkan" disabled /> <input type="submit" class="btn btn-danger btn-small btn-cek" value="Hapus" disabled />
<?php }

echo paging($jml['jml']);
 ?>
</div>
</form>
</div>