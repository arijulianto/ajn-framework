<?php
session_start();
// Direktori dasar yang utama
define('BASEPATH', str_replace('\\','/',dirname(__FILE__)).'/');

// Direktori file sistem (CORE & LIBRARY)
define('SYS_DIR', BASEPATH.'_system/');
define('SYS_CORE', SYS_DIR.'core/');
define('SYS_LIB', SYS_DIR.'library/');
define('SYS_CONFIG', SYS_DIR.'config/');

// Direktori file module
define('MODULE_PATH', BASEPATH.'_module/');

// Direktori file template
define('TEMPLATE_PATH', BASEPATH.'_template/');

// Direktori file media
define('MEDIA_PATH', BASEPATH.'_media/');

// Direktori plugin
define('PLUGIN_PATH', BASEPATH.'_plugin/');




/* Load config */
include SYS_CONFIG . 'website.php';
include SYS_CONFIG . 'database.php';
include SYS_CONFIG . 'plugin.php';


include SYS_CORE . 'library.php';
include SYS_CORE . 'database.php';
include SYS_CORE . 'framework.php';


/* Load Template Module */
include SYS_CORE . 'template.php';
