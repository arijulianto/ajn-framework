<?php
/*** DEBUG INFO ***/
// mode website, tampilkan pesan error
define('DEBUG', true);
define('DEBUG_LEVEL', 2);

// Locale Setting
define('LOCALE_CODE', 'id_ID');
define('LOCALE_TIMEZONE', 'Asia/Jakarta');
define('ADMIN_URI', SITE_URI.'admin/');
define('ADMIN_URL', SITE_URL.'admin/');


// check Database Setting
if(DB_NAME!=''){
	$db = new Database();
}

// Module Name
if($_SESSION['admin_uid']){
	if($_GET['module'])
		define('MODULE', is_dir(MODULE_PATH.$_GET['module']) ? $_GET['module'] : '404');
	else
		define('MODULE', 'home');
}else{
	define('MODULE', 'login');
}

// Module Full Path
define('MODULE_DIR', MODULE_PATH . MODULE . '/');
// Module Site URI
define('MODULE_URI', ADMIN_URI . MODULE . '.php');
// Module Site URL
define('MODULE_URL', ADMIN_URL . MODULE . '.php');


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
