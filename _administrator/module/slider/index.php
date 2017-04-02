<article>
<h1 class="title">Data Slider</h1>
<?php
if($slug1=='edit' || $slug1=='new')
	include MODULE_DIR.'detail.php';
elseif($slug1=='activate' || $slug1=='deactivate' || $slug1=='delete')
	include MODULE_DIR.'action.php';
else
	include MODULE_DIR.'list.php';
?>
</article>