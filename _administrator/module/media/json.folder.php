<?php

if($slug2=='create'){
	if(mkdir(MEDIA_DIR.$_POST['name']))
		$output = array('status'=>1, 'message'=>"Folder '$_POST[name]' was created successfully");
	else
		$output = array('status'=>0, 'message'=>'Folder failed to create. Please re-check the name and try again!');
}elseif($slug2=='delete'){
	if(rmdir(MEDIA_DIR.$_POST['name']))
		$output = array('status'=>1, 'message'=>"Folder '$_POST[name]' was deleted successfully");
	else
		$output = array('status'=>0, 'message'=>'Folder failed to delete. Please re-check the name and try again!');
}


echo array2json($output);