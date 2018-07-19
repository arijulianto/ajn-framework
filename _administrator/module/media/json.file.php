<?php

if($_POST['action']=='upload'){
	debug($_POST);
	debug($_FILES);
}elseif($_POST['action']=='rename'){
	$fn = explode('/', $_POST['file_name']);
	if(is_file(MEDIA_DIR.$_POST['path'].'/'.$_POST['new_name'])){
		$output = array('status'=>0, 'message'=>"Cannot rename file. The file '$_POST[new_name]' was exist in this folder!");
	}else{
		if(rename(MEDIA_DIR.$_POST['file_name'],MEDIA_DIR.$_POST['path'].'/'.$_POST['new_name']))
			$output = array('status'=>1, 'message'=>"File '$fn[1]' was renamed to '$_POST[new_name]'");
		else
			$output = array('status'=>0, 'message'=>'Failed to rename file. Please re-check the name and try again!');
	}
}elseif($_POST['action']=='delete'){
	$file = end(explode('/',$_POST['file_name']));
	if(is_file(MEDIA_DIR.$_POST['file_name'])){
		if(unlink(MEDIA_DIR.$_POST['file_name']))
			$output = array('status'=>1, 'message'=>"File '$file' was deleted successfully");
		else
			$output = array('status'=>0, 'message'=>'File failed to delete. Please re-check the name and try again!');
	}else{
		$output = array('status'=>0, 'message'=>'File failed to delete. File not exist or was deleted!');
	}
}


echo array2json($output);