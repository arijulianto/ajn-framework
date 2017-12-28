<?php
$adm['login_source'] = 'tbl_user';
//$adm['login_session'] = array('uid'=>'iduser', 'nama'=>'nama', 'level'=>'level');
$adm['login_session'] = array('uid'=>'iduser', 'nama'=>'nama');
$adm['login_cek'] = array('user_login'=>'user_email', 'user_password'=>'user_password');
$adm['login_encrypt'] = 'md5+base64';
$adm['login_recent'] = true;