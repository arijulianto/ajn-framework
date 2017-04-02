<?php
session_start();
// Direktori dasar yang utama
define('BASEPATH', str_replace('/_administrator/','/',str_replace('\\','/',dirname(__FILE__)).'/'));
define('ADMIN_PATH', str_replace('\\','/',dirname(__FILE__)).'/');

// Direktori file sistem (CORE & LIBRARY)
define('SYS_DIR', BASEPATH.'_system/');
define('SYS_CORE', SYS_DIR.'core/');
define('SYS_LIB', SYS_DIR.'library/');
define('SYS_CONFIG', SYS_DIR.'config/');
define('ADMIN_INC', ADMIN_PATH.'inc/');


// Direktori file module
define('MODULE_PATH', ADMIN_PATH.'module/');

// Direktori plugin
define('PLUGIN_PATH', BASEPATH.'_plugin/');



/* Load config */
include SYS_CONFIG . 'website.php';
include SYS_CONFIG . 'database.php';
include SYS_CONFIG . 'plugin.php';


include SYS_CORE . 'library.php';
include SYS_CORE . 'database.php';
include ADMIN_INC . 'framework.php';
include SYS_CORE . 'app.setting.php';



/* Load Template Module */
include ADMIN_INC . 'template.php';