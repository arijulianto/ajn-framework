<?php
/*** DEBUG INFO ***/
// mode website, tampilkan pesan error
define('DEBUG', true);
define('DEBUG_LEVEL', 2);

// Locale Setting
define('LOCALE_CODE', 'id_ID');
define('LOCALE_TIMEZONE', 'Asia/Jakarta');


// check Database Setting
if(DB_NAME!=''){
	$db = new Database();
}


// Module Name
if($config['site_login'] && !($_SESSION['admin_uid'] || $_SESSION['user_uid'])){
	define('MODULE', 'login');
}else{
	if($_GET['module'])
		define('MODULE', is_dir(MODULE_PATH.$_GET['module']) ? $_GET['module'] : '404');
	else
		define('MODULE', $conf['default_module']);
}

// Module Full Path
define('MODULE_DIR', MODULE_PATH . MODULE . '/');
// Module Site URI
define('MODULE_URI', SITE_URI . MODULE);
// Module Site URL
define('MODULE_URL', SITE_URL . MODULE);



// Separate Slug as Singel Variabel
if($_GET['slug']){
	$SLUG = explode('/', $_GET['slug']);
	for($i=1;$i<=count($SLUG);$i++){
		$tmp_slug['slug'.$i] = $SLUG[$i-1];
	}
	$item = end($tmp_slug);
	extract($tmp_slug);
}

// parse plugin
if($conf['plugin']){
	foreach($conf['plugin'] as $pf=>$pn){
		if(is_file(PLUGIN_PATH.$pf.'.php')){
			include PLUGIN_PATH.$pf.'.php';
			$pl[$pn] = new $pn();
		}
	}
	if($pl) extract($pl);
}
// Read Setting
if($conf['db_setting']){
	$setting = $db->query("SELECT setting_name,setting_value from tbl_setting")->results();
	if($setting){
		foreach($setting as $row) {
			$setting['data'][$row['setting_name']]=$row['setting_value'];
		}
		if($setting['lokasi_map']){
			$sm = explode('|',$setting['lokasi_map']);
			$setting['lokasi_map'] = $sm[0];
			$setting['lokasi_zoom'] = $sm[1];
		}
		$setting = $setting['data'];
	}
}

// Pager
$limit   = $conf['default_paging'];
$halaman = $_GET['page'];
if(empty($halaman)){
    $start = 0;
    $halaman = 1;
    $no = 1;
}else{
	$start = ($halaman-1) * $limit;
	$no = $start + 1;
}


define('SITE_NAME', $setting['site_name'] ? $setting['site_name'] : $conf['site_name']);

// Template
define('TEMPLATE', '');//is_dir(TEMPLATE_PATH . $conf['template']) ? $conf['template'] : DEFAULT_TEMPLATE);
define('TEMPLATE_DIR', TEMPLATE_PATH);
