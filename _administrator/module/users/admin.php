<h1 class="title">Data Pengelola Website</h1>
<?php
if($slug1=='new' || $slug1=='edit')
	include MODULE_DIR . 'admin_detail.php';
elseif($slug1=='activate' || $slug1=='deactivate' || $slug1=='delete')
	include MODULE_DIR . 'admin_action.php';
else
	include MODULE_DIR . 'admin_list.php';