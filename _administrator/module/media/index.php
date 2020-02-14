<?php
$types = array('jpg'=>'JPG Image','jpeg'=>'JPG Image','png'=>'PNG Image','gif'=>'GIF Image','bmp'=>'BMP Image','pdf'=>'PDF Document');
$dir = $_GET['dir'] ? $_GET['dir'] : 'images';
$directory = MEDIA_PATH.$dir;
$mURI = MEDIA_URI.$dir;
$mURL = MEDIA_URL.$dir;


// upload file
if($_FILES){
	$upload = array();
	$ni=0;$np = 0;
	foreach($_FILES['upload']['name'] as $i=>$name){
		if($_FILES['upload']['type'][$i]=='application/pdf'){
			$upload['pdf'][$ni]['name'] = $name;
			$upload['pdf'][$ni]['tmp_name'] = $_FILES['upload']['tmp_name'][$i];
			$upload['pdf'][$ni]['type'] = $_FILES['upload']['type'][$i];
			$upload['pdf'][$ni]['size'] = $_FILES['upload']['size'][$i];
			$ni++;
		}else{
			$upload['image'][$np]['name'] = $name;
			$upload['image'][$np]['tmp_name'] = $_FILES['upload']['tmp_name'][$i];
			$upload['image'][$np]['type'] = $_FILES['upload']['type'][$i];
			$upload['image'][$np]['size'] = $_FILES['upload']['size'][$i];
			$np++;
		}
	}
	if($upload['pdf']){
		foreach($upload['pdf'] as $upl){
			move_uploaded_file($upl['tmp_name'], MEDIA_DIR.$_POST['dir'].'/'.$upl['name']);
		}
	}
	if($upload['image']){
		include PLUGIN_PATH.'class.upload.php';
		$xupload = new upload();
		foreach($upload['image'] as $upl){
			$xupload->add($upl,MEDIA_DIR.$_POST['dir']);
			$xupload->upload($upl['name'],1024);
		}
	}
	if($slug1=='upload'){
		echo "<script>self.location.href='",MODULE_URI,"'</script>";
	}
}


// delete file
if($_POST['delname'] && $_POST['delpath']){
	unlink($_POST['delpath'].$_POST['delname']);
}


if($dir=='images')
	$kecuali = array('blank.png','__newimage.jpg','__nopic.jpg','__nopic_small.jpg','__nopic_smallwide.jpg','__nopic_thumb.jpg','box-add.png','..', '.');
else
	$kecuali = array('..', '.');


$folders = array_diff(scandir(MEDIA_PATH), array('..', '.', 'assets','brands','product','slider'));
$files = array_diff(scandir($directory), $kecuali);
$thumb = array('jpg','jpeg','png','gif','bmp');

if($folders){
	foreach($folders as $i=>$d){
		if(is_file(MEDIA_PATH.$d)) unset($folders[$i]);
	}
}
?><style>
ul.file-tree{margin:0;padding:0;list-style:none}
ul.file-tree.thumb li{width:19%;margin:0;padding:0;list-style-type:none;display:inline-block;*display:inline;height:158px;}
ul.file-tree.thumb .file{display:block;border:1px solid #fff;cursor:pointer;padding:2px;margin:2px;overflow:hidden;position:relative;}
ul.file-tree.thumb .file:hover{background:#e4effc;border-color:#9ec1ea;}
ul.file-tree.thumb .file-icon{text-align:center;height:128px;vertical-align:bottom;overflow:hidden;}
ul.file-tree.thumb .file-info{text-align:center;padding-top:2px;}
ul.file-tree.thumb .file-icon img{max-height:100%;}
ul.file-tree.thumb .file-info .fname{text-align:center;display:block;font-weight:bold;}
ul.file-tree.thumb .file-info .fsize{text-align:center;display:block;}
ul.file-tree.thumb .check-file{display:none;}
ul.file-tree.thumb .check-file:checked+.file{background:#c4ddfc;border-color:#7da2ce;}

.form-nav,.form-upload{float:left;margin-right:8px}
.form-search{float:right}
#uploadfiles,.btn-upload{display:none;}

.file-explorer{overflow:hidden}
.file-explorer .address-bar{backgrounds:#fff;overflow:hidden;display:block;width:100%;box-sizing:border-box}
.file-explorer .address-bar input,.file-explorer .address-bar select{display:block;width:100%;box-sizing:border-box;border:1px solid #fff;background:#fff;box-shadow:none;padding:0;margin:0;border-radius:0;line-height:100%;outline:none;}
.file-explorer .address-bar .path{float:left;width:75%}
.file-explorer .address-bar .path .icon-folder{position:absolute;margin-top:20px;margin-left:12px;}
.file-explorer .address-bar .path input,.file-explorer .address-bar .path select{margin:10px 8px 10px 0;border:1px solid #ccc;padding:8px 8px 8px 32px}
.file-explorer .address-bar .path input:focus{border-color:#aaa;}
.file-explorer .address-bar .search{float:left;width:24%;margin-left:1%;}
.file-explorer .address-bar .search input{margin:10px 0 10px 0;border:1px solid #ccc;padding:8px}
.file-explorer .address-bar .search input:focus{border-color:#aaa;}

@media all and  (max-width:768px){ 
	ul.file-tree.thumb li{width:32%}
}
@media all and (max-width:640px){
	ul.file-tree.thumb li{width:49%}
}
</style>

<div class="window shadow">
<div class="titlebar">Media Manager</div>
<div class="action-bar">
<div class="file-explorer">
<form action="<?php echo MODULE_URI ?>/upload" method="post" enctype="multipart/form-data">
<div class="wrap">
	<!-- <a class="btn btn-inverse new-folder"><i class="fa fa-folder"></i> New Folder</a> -->
	<input type="hidden" name="dir" value="<?php echo $dir ?>" />
	<label class="btn btn-primary"><i class="fa fa-upload"></i> Upload<input type="file" name="upload[]" data-mazsize="2097152" accept="image/*,application/pdf" class="ajax-upload" multiple style="display:none" /></label>
	<?php //if(!$files) echo '<a class="btn btn-danger del-folder" data-dir="',$dir,'"><i class="fa fa-trash"></i> Delete Folder</a>' ?>

	<div class="btn-group file-actions" style="display:none">
		<a class="btn btn-file-actions detail btn-warning"><i class="fa fa-eye"></i> Detail</a>
		<a class="btn btn-file-actions rename btn-success"><i class="fa fa-pencil"></i> Rename</a>
		<a class="btn btn-file-actions move btn-info"><i class="fa fa-move"></i> Move</a>
		<a class="btn btn-file-actions delete btn-danger"><i class="fa fa-trash"></i> Delete</a>
		<a class="btn btn-file-actions unselect btn-default"><i class="fa fa-select"></i> Unselect</a>
	</div>
</div>
</form>
<form class="address-bar" action="<?php echo MODULE_URI ?>" method="get">
	<div class="path"><i class="fa fa-folder-open icon-folder"></i><select name="dir" class="autosubmit"><?php
foreach($folders as $f){
	$sel = ($dir==$f) ? ' selected' : '';
	echo "<option value=\"$f\"$sel>/$f</option>";
}
?></select></div>
	<div class="search"><input type="search" name="q" value="<?php echo $_GET['q'] ?>" placeholder="Cari File" /></div>
</form>
</div>

</div>
<div class="workspace">
<?php

if($files){
echo '<ul class="file-tree thumb">',"\n";
foreach($files as $file){
$ext = substr(strrchr($file,'.'),1);
$ext2 = strtolower($ext);
$fname = basename($file,'.'.$ext);
$fname = strlen($fname)>12 ? substr($fname,0,12).'..' : $fname;
if($ext2!='html'){
if(($_GET['q']=='' || empty($_GET['q'])) || ($_GET['q'] && stristr($file,$_GET['q']))){
	$do = 1;
}else{
	$do = 0;
}
if(is_dir($directory.'/'.$file)) $dirs[] = $file;
if($do){
if(in_array($ext2, $thumb))
	$fthumb = MEDIA_URI."$dir/$file";
else
	$fthumb = ADMIN_URI."/images/{$ext2}128.png";
$size = byte_format(filesize($directory.'/'.$file));
//$datafile = "{'dir':'$dir','URI':'$mURI','URL':'$mURL','name':'$file','size':'$size','type':'".$types[$ext2]."','preview':'$fthumb'}";
$icon = 
$datafile = array('dir'=>$dir,'URI'=>$mURI,'URL'=>$mURL,'name'=>$file,'size'=>$size,'type'=>$types[$ext2],'icon'=>$fthumb);
$datafile = json_encode($datafile);
$datafile = str_replace('"', "'", $datafile);
echo "<li><input type=\"checkbox\" class=\"check-radio $dir check-file\" data-group=\".$dir\" id=\"",md5($file),"\" /><label title=\"$file\" class=\"file\" for=\"",md5($file),"\" data-file=\"$datafile\">
<div class=\"file-icon\"><img src=\"$fthumb\" /></div>
<div class=\"file-info\"><span class=\"fname\">$fname.$ext</span><span class=\"fsize\">$size</span></div>
</label></li>\n";
}
}
}
echo '</ul>';
}else{
	if($_GET['q'])
		echo '<p class="warning">No find result found for \'',$_GET['q'],'\'.</p>';
	else
		echo '<p class="warning">No files available!</p>';
}
if($files && !$do){
	if($_GET['q'])
		echo '<p class="warning">No find result found for \'',$_GET['q'],'\'.</p>';
	else
		echo '<p class="warning">No files available!</p>';
}

?>

<form action="<?php echo MODULE_URI ?>/file.json" method="post" class="overlay dialog-file-action" style="display:none">
<div class="popup-dialog shadow">
<div class="title title-pop">Informasi File</div>
<div class="main">
<table width="100%">
<tr>
	<td width=100 align="center"><img class="file-preview" /></td>
	<td valign="bottom"><p>&nbsp; <strong class="sFileName"></strong></p>
	<p>&nbsp; File size: <span class="sFileSize"></span></p>
	<p>&nbsp; File type: <span class="sFileType"></span></p></td>
</tr>
<tr class="file-details">
	<td colspan=2>
		<div>Full URL (click to copy):</div>
		<input type="text" class="btn-block sFileURL" readonly onclick="this.select()" /></td>
</tr>
<tr class="file-rename" style="display:none">
	<td colspan=2>
		<div>Enter new file name:</div>
		<input type="text" data-type="usermail" name="new_name" class="btn-block new-file-name" placeholder="Type new file name" /></td>
</tr>
</table>
</div>
<div class="footer wrap">
	<input type="hidden" name="action" class="dAction" value="" />
	<input type="hidden" name="file_name" class="dTarget" value="" />
	<input type="hidden" name="path" class="dPath" value="" />

	<a class="btn btn-inverse hide-popup-dialog float-right">Close</a>
	<a class="btn btn-inverse cancel-rename float-right" style="display:none">Cancel</a>
	<a class="btn btn-danger btn-delete-file float-right"><i class="fa fa-trash"></i> Delete</a>
	<!-- <a class="btn btn-info btn-rename-file float-right"><i class="fa fa-pencil"></i> Rename</a>
	<a class="btn btn-primary btn-rename-file-ok float-right" style="display:none">Rename</a> -->
</div>
</div>
</form>

</div>
</div>
