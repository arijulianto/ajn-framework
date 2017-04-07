<?php
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
if (defined('DEBUG')){
	if($conf['debug_level']==1)
		error_reporting(E_ALL);
	elseif($conf['debug_level']==2)
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT);
	else
		error_reporting(0);
}else{
	exit('The application environment is not set correctly.');
}
/*
 *---------------------------------------------------------------
 * TIMEZONE SETTING
 *---------------------------------------------------------------
 * Setting Locale info and time
 * By default locale setting was setup to Indonesia UTC+7 (Asia/Jakarta).
 */
date_default_timezone_set($conf['locale_code']);
setlocale(LC_ALL, $conf['timezone']);


// check Database Setting
if(DB_NAME!=''){
	$db = new Database();
}


// Module Name
if($conf['site_status']=='online'){
	if($config['site_login'] && !($_SESSION['admin_uid'] || $_SESSION['user_uid'])){
		define('MODULE', 'login');
	}else{
		if($_GET['module']){
			if(is_dir(MODULE_PATH.$_GET['module'])){
				define('MODULE', $_GET['module']);
			}else{
				if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
				include TEMPLATE_PATH.'offline.php';
				if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
				exit;
			}
		}else{
			define('MODULE', $conf['default_module']);
		}
	}
}elseif($conf['site_status']=='maintenance']){
	include TEMPLATE_PATH.'maintenance.php';
	exit;
}elseif($conf['site_status']=='offline']){
	include TEMPLATE_PATH.'offline.php';
	exit;
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
			$cm = explode(',',$sm[0]);
			$setting['lokasi_map'] = $sm[0];
			$setting['lokasi_lat'] = $cm[0];
			$setting['lokasi_lng'] = $cm[1];
			$setting['lokasi_zoom'] = $sm[1];
		}
		$setting = $setting['data'];
	}
}

// Pager
$limit   = $setting['paging'] ? $setting['paging'] : $conf['default_paging'];
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
