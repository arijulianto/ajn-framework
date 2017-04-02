<h1 class="title">Data Customer</h1>
<?php
if($slug2=='new' || $slug2=='edit')
	include MODULE_DIR . 'customer_detail.php';
elseif($slug2=='activate' || $slug2=='deactivate' || $slug2=='delete')
	include MODULE_DIR . 'customer_action.php';
else
	include MODULE_DIR . 'customer_list.php';