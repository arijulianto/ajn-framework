<?php
/*
 * --------------------------------------------------------------------------------
 * WEBSITE SETING
 * --------------------------------------------------------------------------------
*/
define('DOMAIN', $_SERVER['HTTP_HOST']);
define('CLIENT_ADDR', $_SERVER['REMOTE_ADDR']);


define('SITE_DIR', 'ajn-framework');
define('ADMIN_DIR', 'adminpanel');

define('SITE_URL', $_SERVER['REQUEST_SCHEME'].'://'.DOMAIN.(SITE_DIR ? '/'.SITE_DIR : '').'/');
define('SITE_URI', (SITE_DIR ? '/'.SITE_DIR : '').'/');

define('MEDIA_URL', $_SERVER['REQUEST_SCHEME'].'://'.DOMAIN.(SITE_DIR ? '/'.SITE_DIR : '').'/media/');
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
 3 = Clean Error Message (Production)

 # LOCALE CODE
 country code for local formatting
 complete list: https://msdn.microsoft.com/en-us/library/cc233982.aspx

 # TIMEZONE
 Setting Locale info for date and time
 support timezone: http://php.net/manual/en/timezones.php
*/
$conf['debug_level'] = 2;
$conf['locale_code'] = 'id_ID';
$conf['timezone'] = 'Asia/Jakarta';


/* 
 * --------------------------------------------------------------------------------
 * WEBSITE SETTING
 * --------------------------------------------------------------------------------
*/
$conf['skip_ext'] = 'html';
$conf['site_name'] = 'AJN Framework';
$conf['site_status'] = 'online'; // option: online, maintenance, offline
$conf['template'] = 'default';

$conf['404_mode'] = 'standalone'; // option: standalone, full
$conf['default_module'] = 'home';
$conf['autoload_module'] = true;
$conf['default_paging'] = 10;
$conf['db_setting'] = ''; // table name

$conf['db_service'] = false;
$conf['service_security'] = 'none'; // option: none, whitelist, blacklist, auth (soon)
$conf['service_prettyprint'] = false;
