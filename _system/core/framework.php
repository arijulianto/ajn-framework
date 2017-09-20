<?php
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
if ($conf['debug_level']>=0 && $conf['debug_level']<=2){
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
 */
date_default_timezone_set($conf['locale_code']);
setlocale(LC_ALL, $conf['timezone']);
ini_set('date.timezone', $conf['timezone']);




// check Database Setting
if(DB_NAME!=''){
	$db = new Database();
}



/*
 *---------------------------------------------------------------
 * METHOD VARIABEL
 *---------------------------------------------------------------
*/
if($_SERVER['REQUEST_METHOD']=='PUT' || ($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT')){
	if($_SERVER['REQUEST_METHOD']=='PUT'){
		parse_str(file_get_contents("php://input"),$_PUT);
	}elseif($_SERVER['REQUEST_METHOD']=='POST' && $_POST['_METHOD']=='PUT'){
		$_PUT = $_POST;
		unset($_PUT['_METHOD']);
	}
}


/*
 *---------------------------------------------------------------
 * SLUG
 *---------------------------------------------------------------
*/
if($_GET['slug']){
	$SLUG = explode('/', $_GET['slug']);
	for($i=1;$i<=count($SLUG);$i++){
		$tmp_slug['slug'.$i] = $SLUG[$i-1];
	}
	$item = end($tmp_slug);
	extract($tmp_slug);
}

if($_GET['ext']) $ext = trim($_GET['ext'],'.');



/*
 *---------------------------------------------------------------
 * MODULE NAME
 *---------------------------------------------------------------
*/
if($config['site_login'] && !($_SESSION['admin_uid'] || $_SESSION['user_uid'])){
	define('MODULE', 'login');
}elseif($_GET['module']==ADMIN_DIR){
	if($slug1){
		define('MODULE', ($_SESSION['admin_uid']?$slug1:'login'));
	}else{
		define('MODULE', ($_SESSION['admin_uid']?'home':'login'));
	}
	// Module Admin URI
	define('MODULE_URI', SITE_URI . ADMIN_DIR .'/'. MODULE . '.php');
	// Module Admin URL
	define('MODULE_URL', SITE_URL . ADMIN_DIR .'/'. MODULE . '.php');
}else{
	if($conf['site_status']=='online'){
		if($config['site_login'] && ($_SESSION['admin_uid'] || $_SESSION['user_uid'])){
			if($_GET['module']){
				if(is_dir(MODULE_PATH.$_GET['module'])){
					define('MODULE', $_GET['module']);
				}else{
					if(IS_WEBSERVICE){
						define('MODULE', $_GET['module']);
					}else{
						if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
						include TEMPLATE_PATH.'404.php';
						if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
						exit;
					}
				}
			}else{
				define('MODULE', $conf['default_module']);
			}
		}else{
			if($_GET['module']){
				if(is_dir(MODULE_PATH.$_GET['module'])){
					define('MODULE', $_GET['module']);
				}else{
					if(IS_WEBSERVICE){
						define('MODULE', $_GET['module']);
					}else{
						if($conf['404_mode']=='full') include TEMPLATE_PATH.'header.php';
						include TEMPLATE_PATH.'404.php';
						if($conf['404_mode']=='full') include TEMPLATE_PATH.'footer.php';
						exit;
					}
				}
			}else{
				define('MODULE', $conf['default_module']);
			}
		}
	}elseif($conf['site_status']=='maintenance'){
		include TEMPLATE_PATH.'maintenance.php';
		exit;
	}elseif($conf['site_status']=='offline'){
		include TEMPLATE_PATH.'offline.php';
		exit;
	}
}


/*
 *---------------------------------------------------------------
 * MODULE
 *---------------------------------------------------------------
*/
// Module Full Path
define('MODULE_DIR', MODULE_PATH . MODULE . '/');
// Module Site URI
define('MODULE_URI', SITE_URI . MODULE);
// Module Site URL
define('MODULE_URL', SITE_URL . MODULE);





/*
 *---------------------------------------------------------------
 * PLUGIN
 *---------------------------------------------------------------
*/
if($conf['plugin']){
	foreach($conf['plugin'] as $pf=>$pn){
		if(is_file(PLUGIN_PATH.$pf.'.php')){
			include PLUGIN_PATH.$pf.'.php';
			$pl[$pn] = new $pn();
		}
	}
	if($pl) extract($pl);
}


/*
 *---------------------------------------------------------------
 * SETTING
 *---------------------------------------------------------------
*/
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


/*
 *---------------------------------------------------------------
 * PAGING
 *---------------------------------------------------------------
*/
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

/*
 *---------------------------------------------------------------
 * MODULE NAME
 *---------------------------------------------------------------
*/
define('TEMPLATE', is_dir(TEMPLATE_PATH . $conf['template']) ? $conf['template'].'/' : TEMPLATE_PATH);
define('TEMPLATE_DIR', TEMPLATE);
