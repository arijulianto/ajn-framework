<?php
/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
if (defined('DEBUG')){
	if(DEBUG_LEVEL==1)
		error_reporting(E_ALL);
	elseif(DEBUG_LEVEL==2)
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
date_default_timezone_set(LOCALE_TIMEZONE);
setlocale(LC_ALL, LOCALE_CODE);



