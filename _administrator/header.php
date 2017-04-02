<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8" />
<meta name="robots" content="noindex, nofollow" />
<title><?php echo $site['title'] ? $site['title'].' - ' : '' ?>Administrator Panel</title>
<link rel="stylesheet" type="text/css" href="<?php echo ADMIN_URI ?>scripts/styles.css" />
<link rel="icon" type="image/vnd.microsoft.icon" href="<?php echo SITE_URI ?>favicon.ico" />
<link rel="shortcut icon" type="image/x-icon" href="<?php echo SITE_URI ?>favicon.ico" />
<!--[if IE]>
<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
<![endif]-->
<script src="<?php echo SITE_URI ?>js/jquery-1.8.3.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/plugin-validInput.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/plugin-sortTable.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/tinymce.min.js"></script>
<script>
$(function(){
$.fn.doRemove = function() {
	var $this = $(this);
	$(this).fadeOut(1200,function(){
		$this.remove();
	})
	return this;
};

$('a[href^="/"]:not([target="_blank"]),[data-href^="/"]').live('click',function(e){
	var url = $(this).attr('data-href') ? $(this).attr('data-href') : $(this).attr('href');
	if(url.substring(0,1)=='/'){
		history.pushState({path:url}, null, url);
		getContent(url);
		e.preventDefault();
		e.stopPropagation();
		return false;
	}
});
$('[data-scrollto]').live('click',function(e){
	$('body').animate({scrollTop:$($(this).attr('data-scrollto')).offset().top},1200);
	e.preventDefault();
});
$('form[action^="/"]:not([enctype])').live('submit',function(e){
	var url = $(this).attr('action')||self.location.href;
	var type = $(this).attr('method')||'get';
	var data = $(this).serialize();
	var btnClick = $(this).find('input[type=submit]:focus');
	var btnTrig = $(this).find('input[type=submit]:first');
	if($(this).find('input[type=submit]').length){
		if(btnClick.length>0)
			data += '&'+btnClick.attr('name')+'='+btnClick.val();
		else
			data += '&'+btnTrig.attr('name')+'='+btnTrig.val();
	}
	if(type.toLowerCase()=='post'){
		history.pushState({path:url}, null, url);
		postContent(url,data);
	}
	else{
		history.pushState({path:url+'?'+data}, null, url+'?'+data);
		getContent(url+'?'+data);
	}
	e.preventDefault();
	e.stopPropagation();
	return false;
});
<?php if($_SESSION['admin_uid']>0){ ?>
$('.input-check').live('click',function(){
	var cek = $(this).attr('checked');
	var nCek = $('.input-check:checked').length;
	if(cek)
		$(this).parents('tr').addClass('selected');
	else
		$(this).parents('tr').removeClass('selected');
	if(nCek>0){
		$('.btn-action-bm').attr('disabled',false);
	}else{
		$('.btn-action-bm').attr('disabled',true);
	}
});
$('.search-table').live('keyup',function(){
	var filter = $(this).val(), count = 0;
	$('.table-data tbody tr[name="nores"]').remove();
	if(filter.trim()!=''){
		$('.table-data tbody tr').each(function(){
			if($(this).text().toUpperCase().indexOf(filter.toUpperCase()) < 0) {
				$(this).fadeOut();
			}else{
				$(this).show();
				count++;
			}
		});
	}
	if(count==0 && filter.trim()!=''){
		$('.table-data tbody').append('<tr name="nores"><td colspan="'+($('.table-data thead tr:first th').length+1)+'"><p class="warning">Tidak ada hasil pencarian kata \''+filter+'\'!</p></td></tr>');
	}
	if(count==0 && filter.trim()==''){
		$('.table-data tbody tr').show();
	}
});
$('.i-file').live('change',function(){
	var minW = parseInt($(this).attr('data-minw')) || 0;
	var minH = parseInt($(this).attr('data-minh')) || 0;
	var $this = $(this);
	var rowCurr = $(this).parents('.row').index();
	var rowAll = $(this).parents('.rows').find('.row:last').index();

	if(this.files && this.files[0] && this.files[0].type.match('image*')){
		var reader = new FileReader();
		var newimage = new Image();
		reader.onload = function (e) {
			newimage.src = e.target.result;
			if(minW>0 || minH>0){
				if(newimage.width<minW || newimage.height<minH){
					alert('Dimensi gambar terlalu kecil ('+newimage.width+'x'+newimage.height+'). Harap pilih gambar minimal '+minW+'x'+minH+' pixel');
					this.value='';
					$this.val('');
					$this.parents('.row').find('.imgPreview').attr('src', $this.parents('.row').find('.imgPreview').attr('data-src'));

				}else{
					$this.parents('.row').find('.imgPreview').prop('src', e.target.result).show();
					if($this.hasClass('auto-add-row')){
						$this.parents('.rows').find('.row:last').clone().appendTo($this.parents('.rows')).find('img').removeAttr('src').removeAttr('title').removeAttr('width');
					}
		if($this.parents('.rows').find('.row:last').prev().find('.i-file').val()=='' && $this.parents('.rows').find('.row:last').prev().find('.imgPreview').attr('src')==''){
						$this.parents('.rows').find('.row:last').remove();
					}
				}
			}
		}
		reader.readAsDataURL(this.files[0]);
	}
	if(!String(this.files[0].name).match(/(?:gif|jpg|png|bmp)$/i)) {
		alert('Harap pilih file gambar saja dengan format JPG, PNG, atau GIF');
		this.value='';
		$this.parents('.row').find('.imgPreview').attr('src', $this.parents('.row').find('.imgPreview').attr('data-src'));
		if($this.parents('.rows').find('.row:last').prev().find('.i-file').val()=='' && $this.parents('.rows').find('.row:last').prev().find('.imgPreview').attr('src')==''){
			$this.parents('.rows').find('.row:last').remove();
		}
	}
});
$('.f-valid').live('change',function(){
	var valid_type = $(this).attr('accept');
	var valid_ext = $(this).attr('data-valid-ext');
	var ftype = valid_type.split('/');
	if(this.files && this.files[0] && this.files[0].type.match(valid_type)){

	}else{
		alert('Invalid file type. Only '+ftype[1].toUpperCase()+' file is allowed!')
		this.value='';
		$this.val('');
	}
})
$('.sukses,.warning').live('click',function(){
	$(this).doRemove();
});
$('.checked-to').live('change',function(){
	if($(this).parents('.row').find('.imgPreview').attr('src')){
		$(this).parents('.rows').find('.checked-val').val('0');
		$(this).parents('.rows').find('.checked-to').attr('checked', false);
		$(this).parents('.row').find('.checked-val').val('1');
		$(this).parents('.row').find('.checked-to').attr('checked', true);
	}else{
		alert('Gambar belum dipilih!');
		$(this).attr('checked', false);
	}
});
$('input[data-slug]').live('keyup',function(){
	var v = $(this).val().toLowerCase();
	v = v.replace(/[^a-z0-9\s]/gi, '').replace(/[_\s]/g, '-');
	$(this).parents('form').find('.slug'+$(this).attr('data-slug')).text(v.substring(0,50));
	$(this).parents('form').find($(this).attr('data-slug')).val(v.substring(0,50));
});
$('.checkAll').live('change',function(){
	var isCek = $(this).attr('checked');
	if(isCek){
		$('input[type="checkbox"][class^="check-"]').attr('checked',true).parents('tr').addClass('selected');
		$('.btn-action-bm').attr('disabled',false);
	}else{
		$('input[type="checkbox"][class^="check-"]').attr('checked',false).parents('tr').removeClass('selected');
		$('.btn-action-bm').attr('disabled',true);
	}
});
$('.filter-view').live('change',function(){
	postContent('<?php echo ADMIN_URI ?>setting.php/kota',$(this).attr('name')+'='+$(this).val());
});
$('.auto-submit').live('change',function(){
	$(this).parents('form').submit();
});


$('.table-data').sortTable();
<?php } ?>

<?php if(strpos($_SERVER['REQUEST_URI'], '/page.php/') || strpos($_SERVER['REQUEST_URI'], '/product.php/')){ ?>
tinyMCE.init({
	selector: ".editor",
	plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
	menubar:false,
	toolbar1: 
		"formatselect fontselect fontsizeselect forecolor | copy cut paste | code fullscreen charmap"
	,
	toolbar2: 
		"bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image media link"
	,
	file_browser_callback : function(field_name, url, type, win){
		var filebrowser = "<?php echo ADMIN_URI ?>/filebrowser";
		filebrowser += (filebrowser.indexOf("?") < 0) ? "?type=" + type : "&type=" + type;
		tinymce.activeEditor.windowManager.open({
			title : "Browse...",
			width : 670,
			height : 400,
			url : filebrowser
		}, {
		window : win,
		input : field_name
		});
		return false;
	}
}); 
<?php } ?>


function getContent(url){
	$('.loading').remove();
	$('body').append('<div class="loading"></div>');
	$.ajax({
		url:url,
		cache:false,
		success:function(html){
			var title = $(html).filter('title').html();
			var body = $(html).filter('section').html();
			document.title = title;
			$('section').html(body);
			$('.loading').remove();
			$('body').animate({scrollTop:0},1200);
			if(url.indexOf('/page.php/')>-1 || url.indexOf('/product.php/')>-1){
				tinymce.remove();
				tinyMCE.init({
					selector: ".editor",
					plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
					menubar:false,
					toolbar1: 
						"formatselect fontselect fontsizeselect forecolor | copy cut paste | code fullscreen charmap"
					,
					toolbar2: 
						"bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image media link"
					,
					file_browser_callback : function(field_name, url, type, win){
						var filebrowser = "<?php echo ADMIN_URI ?>/filebrowser";
						filebrowser += (filebrowser.indexOf("?") < 0) ? "?type=" + type : "&type=" + type;
						tinymce.activeEditor.windowManager.open({
							title : "Browse...",
							width : 670,
							height : 400,
							url : filebrowser
						}, {
						window : win,
						input : field_name
						});
						return false;
					}
				});
			}
		}
	});
}

function postContent(url,data){
	$('.loading').remove();
	$('body').append('<div class="loading"></div>');

	$.ajax({
		url:url,
		type:'post',
		data:data,
		cache:false,
		success:function(html){
			var title = $(html).filter('title').html();
			var body = $(html).filter('section').html();
			document.title = title;
			$('section').html(body);
			$('.loading').remove();
			$('body').animate({scrollTop:0},1200);
			if(body.indexOf(' class="editor"')>-1){
				tinyMCE.init({
					selector: ".editor",
					plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
					menubar:false,
					toolbar1: 
						"formatselect fontselect fontsizeselect forecolor | copy cut paste | code fullscreen charmap"
					,
					toolbar2: 
						"bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image media link"
					,
					file_browser_callback : function(field_name, url, type, win){
						var filebrowser = "<?php echo ADMIN_URI ?>/filebrowser";
						filebrowser += (filebrowser.indexOf("?") < 0) ? "?type=" + type : "&type=" + type;
						tinymce.activeEditor.windowManager.open({
							title : "Browse...",
							width : 670,
							height : 400,
							url : filebrowser
						}, {
						window : win,
						input : field_name
						});
						return false;
					}
				});
			}
		}
	});
}
window.onpopstate = function(event) {
	getContent(window.location.pathname+window.location.search);
	event.preventDefault();
};

});
var st=new Date('<?php echo date('F d, Y H:i:s') ?>');
var dn=['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
var mn = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

function padlength(n) {
    var output = (n.toString().length == 1) ? '0' + n : n;
    output = parseInt(output) == 0 ? '00' : output;
    return output;
}

function jam() {
    st.setSeconds(st.getSeconds() + 1);
    var dmy = st.getDate() + ' ' + mn[st.getMonth()] + ' ' + st.getFullYear();
    var w = padlength(st.getHours()) + ':' + padlength(st.getMinutes()) + ':' + padlength(st.getSeconds());
    if($('#hour').length){
    	document.getElementById('hour').innerHTML = w;
	    document.getElementById('date').innerHTML = dmy;
	    document.getElementById('day').innerHTML = dn[st.getDay()];
	    setTimeout('jam()', 1000);
	}
}
</script>
</head>
<body onload="jam()">
<header>
<div class="inner">
<h1>Administrator Panel</h1>
</div>
</header>
<nav>
<ul>
	<li style="float:left;color:#fff;padding:10px 12px;font-size:16px"><span id="day"><?php echo hari() ?></span>, <span id="date"><?php echo tanggal('j F Y') ?></span> &nbsp; <strong><span id="hour"><?php echo tanggal('H:i:s') ?></span></strong></li>
<?php if($_SESSION['admin_uid']>0){ ?>
	<li><a href="<?php echo ADMIN_URI ?>users.php/profile">Hi, <?php echo $_SESSION['admin_nama'] ?></a></li>
	<li><a href="<?php echo SITE_URL ?>" target="_blank">Visit Website</a></li>
	<li><a href="<?php echo ADMIN_URL ?>logout.php">Logout</a></li>
</ul>
<?php }else{ ?>
	<li><a href="<?php echo SITE_URI ?>">&laquo; Kembali ke Website</a></li>
<?php } ?>
</nav>
<section>
<?php if($_SESSION['admin_uid']>0){ ?>
<aside>
<?php
echo md5(base64_encode('Bismillah'));
$folders = array_diff(scandir(MODULE_PATH), array('..', '.','404','login','logout','page','home'));
debug($folders);
foreach($folders as $dir){
	if(is_file(MODULE_PATH.$dir.'/_info.php')) include MODULE_PATH.$dir.'/_info.php';
}
?>
<!--<h3>Home</h3>
<ul>
	<li><a href="<?php echo ADMIN_URI ?>index.php"<?php echo MODULE=='home' ? ' class="curr"' : '' ?>>Dashboard</a></li>
	<li><a href="<?php echo ADMIN_URI ?>page.php/home"<?php echo (MODULE=='page' && $slug1=='home') ? ' class="curr"' : '' ?>>Welcome Text</a></li>
	<li><a href="<?php echo ADMIN_URI ?>slider.php"<?php echo MODULE=='slider' ? ' class="curr"' : '' ?>>Slider</a></li>
	<li><a href="<?php echo ADMIN_URI ?>inbox.php"<?php echo MODULE=='inbox' ? ' class="curr"' : '' ?>>Kontak Masuk</a></li>
</ul>

<h3>Produk</h3>
<ul>
	<li><a href="<?php echo ADMIN_URI ?>product.php/brand"<?php echo (MODULE=='product' && $slug1=='brand') ? ' class="curr"' : '' ?>>Data Brand</a></li>
	<li><a href="<?php echo ADMIN_URI ?>product.php/kategori"<?php echo (MODULE=='product' && $slug1=='kategori') ? ' class="curr"' : '' ?>>Data Kategori</a></li>
	<li><a href="<?php echo ADMIN_URI ?>product.php"<?php echo (MODULE=='product' && (empty($slug1) || $slug1=='new' || $slug1=='edit' || $slug1=='activate' || $slug1=='deactivate' || $slug1=='delete')) ? ' class="curr"' : '' ?>>Data Barang</a></li>
</ul>

<h3>Halaman Statis</h3>
<ul>
	<li><a href="<?php echo ADMIN_URI ?>page.php"<?php echo (MODULE=='page' && $slug1!='home') ? ' class="curr"' : '' ?>>Halaman Statis</a></li>
</ul>

<h3>Data User</h3>
<ul>
	<li><a href="<?php echo ADMIN_URI ?>users.php/customer"<?php echo (MODULE=='users' && $slug1=='customer') ? ' class="curr"' : '' ?>>Data Costumer</a></li>
	<li><a href="<?php echo ADMIN_URI ?>users.php"<?php echo (MODULE=='users' && (empty($slug1) || $slug1=='new' || $slug1=='edit' || $slug1=='activate' || $slug1=='deactivate' || $slug1=='delete')) ? ' class="curr"' : '' ?>>Data Pengelola</a></li>
</ul>

<h3>Setting</h3>
<ul>
	<li><a href="<?php echo ADMIN_URI ?>setting.php/bank"<?php echo (MODULE=='setting' && $slug1=='bank') ? ' class="curr"' : '' ?>>Data Bank</a></li>
	<li><a href="<?php echo ADMIN_URI ?>setting.php/kurir"<?php echo (MODULE=='setting' && $slug1=='kurir') ? ' class="curr"' : '' ?>>Data Kurir</a></li>
	<li><a href="<?php echo ADMIN_URI ?>setting.php/kota"<?php echo (MODULE=='setting' && $slug1=='kota') ? ' class="curr"' : '' ?>>Data Kota</a></li>
	<li><a href="<?php echo ADMIN_URI ?>imgbann.php"<?php echo MODULE=='imgbann' ? ' class="curr"' : '' ?>>Data Banner</a></li>
	<li><a href="<?php echo ADMIN_URI ?>setting.php/contact"<?php echo (MODULE=='setting' && $slug1=='contact') ? ' class="curr"' : '' ?>>Contact Info</a></li>
	<li><a href="<?php echo ADMIN_URI ?>setting.php"<?php echo (MODULE=='setting' && empty($slug1)) ? ' class="curr"' : '' ?>>Seting Website</a></li>
	<li><a href="<?php echo ADMIN_URI ?>users.php/profile"<?php echo (MODULE=='users' && $slug1=='profile') ? ' class="curr"' : '' ?>>Ganti Password</a></li>
</ul>-->
</aside>
<?php }


// Pager
$limit   = 50;
$halaman = $_GET['page'];
if(empty($halaman)){
    $start=0;
    $halaman=1;
}else{
	$start = ($halaman-1) * $limit;
}

