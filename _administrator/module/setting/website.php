<style>
.upload-area{overflow:hidden}
.upload-area .item{width:102px;height:102px;float:left;margin:0 5px 5px 0;}
.upload-area .item input[type="radio"],.upload-area .item input[type="file"]{display:none;}
.upload-area .item .action{overflow:hidden;width:100px;height:30px;position:absolute}
.upload-area .item .action .item-main{text-align:center;cursor:pointer;display:inline-block;padding:2px 4px;color:#888;font-size:20px;float:left;padding-left:10px}
.upload-area .item .action .item-remove{text-align:center;cursor:pointer;display:inline-block;padding:2px 4px;color:red;font-size:16px;float:right;padding-right:10px;}
.upload-area .item .image{overflow:hidden;width:102px;height:102px;border:1px solid #ece9d8}
.upload-area .item .image label{width:102px;height:102px;overflow:hidden}
.upload-area .item .image label img{min-width:100px;min-height:100px;background:url(../images/box-add.png);}

.upload-area .item input[type="radio"]:checked+.action>.item-main{color:green;}
.upload-area .item input[type="radio"]:checked+.action+.image{border-color:#073a97;}
.upload-area.template-manager .item{display:block;width:50%;height:auto;float:left;overflow:hidden}
.upload-area.template-manager .image{overflow:hidden;width:auto;height:auto;padding:5px 0 0 5px}
.upload-area.template-manager .image>label{display:block;float:left;}
.upload-area.template-manager .info{float:left;margin-left:6px;width:auto;}
.upload-area.template-manager .info>label{display:block;width:auto;}
</style>
<form action="" method="post" enctype="multipart/form-data">
<?php
if($_POST){
	$data_save = array();
	$data_save['site_name'] = $_POST['site_name'];
    $data_save['site_slogan'] = $_POST['site_slogan'];
    $data_save['footer_title'] = $_POST['footer_title'];
    $data_save['footer_text'] = $_POST['footer_text'];
    if($_FILES['new_logo']['size']>0){
    	include PLUGIN_DIR.'class.upload.php';
		$upload = new upload($_FILES['new_logo'], MEDIA_DIR.'images');
		$ext = strrchr($_FILES['new_logo']['name'], '.');
		$name = substr_replace($_FILES['new_logo']['name'],'',-strlen($ext), strlen($ext));
		$up = $upload->upload(null, 130);
		if($up){
			$data_save['logo'] = $up;
		}
    }
    $data_save['company_name'] = $_POST['company_name'];
    $data_save['company_address'] = $_POST['company_address'];
    $data_save['company_city'] = $_POST['company_city'];
    $data_save['lokasi_map'] = $_POST['map_lokasi'];
    $data_save['paging'] = $_POST['paging'];
    $data_save['paging_admin'] = $_POST['paging_admin'];
    $data_save['template'] = $_POST['template'];

    $upd = 0;
    foreach($data_save as $name=>$value){
    	$do = $db->update('tma_setting', array('setting_value'=>$value), array('setting_name'=>$name))->affectedRows();
    	if($do) $upd++;
    }
    if($upd>0){
    	echo '<p class="alert alert-success">Pengaturan berhasil diperbaharui</p>';
    	$setting = $db->query("SELECT setting_name,setting_value from $conf[db_setting]")->results();
		if($setting){
			foreach($setting as $row) {
				$setting['data'][$row['setting_name']]=$row['setting_value'];
			}
			$setting = $setting['data'];
			if($setting['lokasi_map']){
				$sm = explode('|',$setting['lokasi_map']);
				$cm = explode(',',$sm[0]);
				$setting['lokasi_map'] = $sm[0];
				$setting['lokasi_lat'] = $cm[0];
				$setting['lokasi_lng'] = $cm[1];
				$setting['lokasi_zoom'] = $sm[1];
			}
			if($setting['template']){
				$conf['template'] = $setting['template'];
			}
		}
    }else{
    	echo '<p class="alert alert-warning">Pengaturan gagal diperbaharui atau tidak ada data yang diubah.</p>';
    }
}
?>
<table class="table-form" width="100%">
<tr>
	<td><label>Nama Website:</label></td>
	<td><input type="text" name="site_name" class="input-block" value="<?php echo $setting['site_name'] ?>" placeholder="Nama Lengkap" required autofocus /></td>
</tr>
<tr>
	<td><label>Slogan:</label></td>
	<td><input type="text" name="site_slogan" class="input-block" value="<?php echo $setting['site_slogan'] ?>" placeholder="Slogan Website" required /></td>
</tr>
<tr>
	<td><label>Logo:</label></td>
	<td>
		<label title="Klik untuk mengganti Logo" style="vertical-align:middle"><img src="<?php echo MEDIA_URI,'images/',$setting['logo'] ?>" class="imgPreview" width="48" style="margin-right:4px;vertical-align:middle" align="middle" /><input type="file" name="new_logo" accept="image/*" data-maxsize="3145728" data-dimension="100x100" data-preview=".imgPreview" /></label><br /><small><i class="fa fa-question-circle"></i> Pilih file JPG atau PNG dengan ukuran kotak, minimal 100x100 pixel</small>
	</td>
</tr>
<tr>
	<td><label>Judul Footer:</label></td>
	<td><input type="text" name="footer_title" class="input-block" value="<?php echo $setting['footer_title'] ?>" placeholder="Judul Footer" required /></td>
</tr>
<tr>
	<td><label>Deskripsi Footer:</label></td>
	<td><textarea name="footer_text" cols="42" rows="3" class="input-block" placeholder="Deskripsi Footer"><?php echo $setting['footer_text'] ?></textarea></td>
</tr>
<tr>
	<td><label>Perusahaan:</label></td>
	<td><input type="text" name="company_name" class="input-block" value="<?php echo $setting['company_name'] ?>" placeholder="Perusahaan" required /></td>
</tr>
<tr>
	<td><label>Alamat:</label></td>
	<td><textarea name="company_address" cols="42" rows="3" class="input-block" placeholder="Alamat Lengkap"><?php echo $setting['company_address'] ?></textarea></td>
</tr>
<tr>
	<td><label>Kota:</label></td>
	<td><input type="text" name="company_city" id="map-city" class="input-block" value="<?php echo $setting['company_city'] ?>" placeholder="Kota" required /></td>
</tr>
<tr>
	<td><label>Lokasi :</label></td>
	<td><div style="height:300px; width:100%;"><input id="pac-input" style="position:absolute;z-index:10000;width:300px;margin-left:110px;margin-top:8px" type="text" placeholder="Cari Lokasi" onkeypress="return event.keyCode != 13" />
<div id="map" style="width:100%;height:100%"></div></div><input type="hidden" value="<?php echo $_POST['map_lokasi'] ?>" name="map_lokasi" class="input-block" id="map-info" /></td>
</tr>
<tr>
	<td><label>Paging:</label></td>
	<td>
		<p><label>Website &nbsp; &nbsp; &nbsp; &nbsp; : <input type="number" name="paging" size="6" value="<?php echo $setting['paging'] ?>" placeholder="Jml" required /> item per page</label></p>
		<p><label>Administrator : <input type="number" name="paging_admin" size="6" value="<?php echo $setting['paging_admin'] ?>" placeholder="Jml" required /> item per page</label></p>
	</td>
</tr>
<tr>
	<td><label>Template:</label></td>
	<td>
<div class="upload-area template-manager items">
	<?php
$folders = array_diff(scandir(TEMPLATE_PATH), array('..', '.'));
$templates = array();
if($folders){
	foreach($folders as $i=>$d){
		if(is_dir(TEMPLATE_PATH.$d)) $templates[$d] = file_get_contents(TEMPLATE_PATH.$d.'/info.txt');
	}
}

if($templates){
	foreach($templates as $path=>$info){
		$preview = is_file(TEMPLATE_PATH.$path.'/preview.jpg') ? SITE_URI."_template/$path/preview.jpg" : MEDIA_URI.'images/__noimage_thumb.jpg';
echo "		<div class=\"item\">
			<input type=\"radio\" name=\"template\" value=\"$path\" class=\"check-radio\" id=\"main-im-e$path\"",($path==$setting['template'] ? ' checked' : '')," />
			<div class=\"action\"></div>
			<div class=\"image\">
				<label for=\"main-im-e$path\" class=\"item-main\"><img src=\"$preview\" /></label>
				<div class=\"info\">
					<label for=\"main-im-e$path\" class=\"item-main\">",nl2br($info),"</label>
				</div>
			</div>
		</div>\n";		
	}
}
?>
</div>
<small><i class="fa fa-question-circle"></i> Untuk request template baru silahkan hubungi CIMTEK</small>
	</td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><p><br /><input type="submit" class="btn btn-primary btn-large" value="Simpan Perubahan" /></p></td>
</tr>
</table>
</form>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwVCsfZB4cuinVf731vUZp9ElJ7zdoVMs&libraries=places&hl=id">
</script>
<script>
var a = new google.maps.LatLng(<?php echo $setting['lokasi_lat'],',',$setting['lokasi_lng'] ?>);
var mapOptions = {
	zoom: <?php echo $setting['lokasi_zoom'] ?>,
	center: a
}
var map = new google.maps.Map(document.getElementById("map"), mapOptions);
var marker = new google.maps.Marker({
	position: a,
	map: map,
	draggable:true,
});
var geocoder = new google.maps.Geocoder();
var searchBox = new google.maps.places.SearchBox(document.getElementById('pac-input'));

google.maps.event.addListener(searchBox, 'places_changed', function() {
	searchBox.set('map', null);
	var places = searchBox.getPlaces();
	var i, place;
	for (i = 0; place = places[i]; i++) {
		(function(place) {
			marker.setPosition(place.geometry.location);
			map.panTo(place.geometry.location);
			document.getElementById('map-info').value = marker.getPosition().lat()+','+marker.getPosition().lng()+'|'+map.getZoom();
			geocoder.geocode({ 'location': place.geometry.location}, function(results, status) {
				if(results){
					var k = results[0].address_components.length-4;
					document.getElementById('map-city').value = results[0].address_components[k].long_name.replace('Kota ','').replace('Kabupaten ','')+' '+results[0].address_components[results[0].address_components.length-1].short_name
				}
			});
		}(place));
	}
});


map.addListener('drag', function(e){
    marker.setPosition(map.getCenter());
    document.getElementById('map-info').value = marker.getPosition().lat()+','+marker.getPosition().lng()+'|'+map.getZoom();
	geocoder.geocode({ 'location': marker.getPosition()}, function(results, status) {
		if(results){
			var k = results[0].address_components.length-4;
			document.getElementById('map-city').value = results[0].address_components[k].long_name.replace('Kota ','').replace('Kabupaten ','')+' '+results[0].address_components[results[0].address_components.length-1].short_name
		}
	});
});
map.addListener('zoom_changed', function(e) {
    document.getElementById('map-info').value = marker.getPosition().lat()+','+marker.getPosition().lng()+'|'+map.getZoom();
});
marker.addListener('dragend', function(e){
    map.panTo(marker.getPosition());
    document.getElementById('map-info').value = marker.getPosition().lat()+','+marker.getPosition().lng()+','+map.getZoom();
    geocoder.geocode({ 'location': marker.getPosition()}, function(results, status) {
		if(results){
			var k = results[0].address_components.length-4;
			document.getElementById('map-city').value = results[0].address_components[k].long_name.replace('Kota ','').replace('Kabupaten ','')+' '+results[0].address_components[results[0].address_components.length-1].short_name
		}
	});
});
</script>