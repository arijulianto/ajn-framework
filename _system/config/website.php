<?php
/*
 * --------------------------------------------------------------------------------
 * WEBSITE SETING
 * --------------------------------------------------------------------------------
*/
define('DOMAIN', $_SERVER['HTTP_HOST']);

define('SITE_DIR', 'ajn-framework');

define('SITE_URL', 'http://'.DOMAIN.(SITE_DIR ? '/'.SITE_DIR : '').'/');
define('SITE_URI', (SITE_DIR ? '/'.SITE_DIR : '').'/');

define('MEDIA_URL', 'http://'.DOMAIN.(SITE_DIR ? '/'.SITE_DIR : '').'/media/');
define('MEDIA_URI', (SITE_DIR ? '/'.SITE_DIR : '').'/media/');
define('MEDIA_DIR', BASEPATH.'_media/');

/* Environment & Local Server Setting */
$conf['debug_level'] = 2; // option: 0, 1, 2
$conf['locale_code'] = 'id_ID';
$conf['timezone'] = 'Asia/Jakarta';



/* Default Setting */
$conf['site_name'] = 'AJN Framework';
$conf['site_status'] = 'online'; // option: online, maintenance, offline
$conf['site_login'] = false;

$conf['404_mode'] = 'standalone'; // option: standalone, full
$conf['default_module'] = 'home';
$conf['autoload_module'] = false;
$conf['default_paging'] = 10;
$conf['db_setting'] = false;

