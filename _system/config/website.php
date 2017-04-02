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

/* Default Setting */
$conf['site_name'] = 'AJN Framework';
$conf['site_open'] = true;
$conf['site_login'] = false;

$conf['default_module'] = 'home';
$conf['autoload_module'] = true;

$conf['default_paging'] = 10;

$conf['db_setting'] = false;

