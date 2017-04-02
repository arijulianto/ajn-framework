<article>
<?php
if($slug1=='customer')
	include MODULE_DIR.'customer.php';
elseif($slug1=='profile')
	include MODULE_DIR.'profile.php';
else
	include MODULE_DIR.'admin.php';
?>
</article>