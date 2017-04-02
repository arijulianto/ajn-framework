<article>
<?php
if(file_exists(MODULE_DIR.$slug1.'.php'))
	include MODULE_DIR.$slug1.'.php';
else
	include MODULE_DIR.'setting.php';
?>
</article>