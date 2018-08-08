<?php
$adm['login_id'] = 'idadmin';
$adm['login_source'] = ['username'=>'admin', 'password'=>'admin'];
$adm['login_session'] = array('uid'=>'999', 'nama'=>'Admin Demo');
$adm['login_cek'] = array('username', 'passw');
$adm['login_encrypt'] = '';
$adm['login_recent'] = true;

$adm['menu_media'] = true;
$adm['menu_setting'] = [
				'' => 'Pengaturan Website',
				'bank.php' => 'Data Bank',
				'sosmed' => 'Data SOsmed',
			];



$menu['nav'] = [
	'page.php' => array('icon'=>'newspaper-o', 'label'=>'Halaman Statis'),
	'#grup_menu1' => array('icon'=>'book','label'=>'Test Menu', 'menu'=>[
					'menu1.php' => 'Menu 1',
					'menu2.php' => 'Menu 2',
				]),
	'#grup_menu2' => array('icon'=>'user','label'=>'Data User', 'menu'=>[
					'user.php' => 'Data User',
					'admin.php' => 'Data Admin',
				]),
];

