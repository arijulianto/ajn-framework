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



/* 
 * --------------------------------------------------------------------------------
 * ENVIRONMENT & LOCAL SERVER SETTING
 * --------------------------------------------------------------------------------

 # DEBUG LEVEL
 Environments levels of error reporting.
 option value to set:
 1 = All Error Message (Development)
 2 = Standard
 3 = Clean Message (Production)

 # LOCALE CODE
 country code for local formatting
 complete list: https://msdn.microsoft.com/en-us/library/cc233982.aspx

 # TIMEZONE
 Setting Locale info for date and time
 support timezone: http://php.net/manual/en/timezones.php
*/
$conf['debug_level'] = 2; // option: 0, 1, 2
$conf['locale_code'] = 'id_ID';
$conf['timezone'] = 'Asia/Jakarta';



/* 
 * --------------------------------------------------------------------------------
 * DEFAULT SETTING
 * --------------------------------------------------------------------------------
*/
$conf['site_name'] = 'AJN Framework';
$conf['site_status'] = 'online'; // option: online, maintenance, offline
$conf['site_login'] = false;
$conf['template'] = 'default';

$conf['404_mode'] = 'standalone'; // option: standalone, full
$conf['default_module'] = 'home';
$conf['autoload_module'] = false;
$conf['default_paging'] = 10;
$conf['db_setting'] = false;

