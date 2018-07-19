<?php
$adm['login_id'] = 'idadmin';
//$adm['login_source'] = array('username'=>'admin', 'password'=>'admin');
//$adm['login_session'] = array('uid'=>'999', 'username'=>'admin', 'nama'=>'Admin Test', 'level'=>'999');
$adm['login_source'] = 'tbl_admin';
$adm['login_session'] = array('uid'=>'idadmin', 'nama'=>'nama', 'level'=>'levelnum');
$adm['login_cek'] = array('user_email', 'user_password');
$adm['login_encrypt'] = 'md5+base64';
$adm['login_recent'] = true;



$menu['nav'] = [
	'#menu1' => array('icon'=>'calendar-o','label'=>'Konten', 'menu'=>[
					'page.php' => 'Halaman Statis',
					'artikel.php' => 'Artikel',
					'produk.php/kategori' => 'Kategori',
				]),
];

$menu['setting'] = [
					'setting.php' => 'Pengaturan Website',
					'setting.php/contact' => 'Informasi Kontak',
				];