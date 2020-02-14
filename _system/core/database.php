<?php
if(DB_NAME!=''){
	include SYS_DIR.'database/'.DB_TYPE.'.php';
}