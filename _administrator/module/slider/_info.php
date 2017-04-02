<?php
$slider['module'] = 'Slider';
$slider['mode'] = array('list','detail','action');
$slider['table'] = 'tbl_slider';
$slider['view'] = 'tbl_slider';
$slider['filter'] = array('status::[=>Semua||1=>Aktif||0=>Tidak Aktif]','keterangan');
$slider['urutan'] = array('1=[Aktif]','0=[Tidak Aktif]');

$slider['list_header'] = array('No','Gambar','Keterangan','Urutan','Status','Aksi');
$slider['list_detail'] = array('idgambar','gambar','<strong>title</strong><br />keterangan','urutan','aktif','edit,activatedeactivate,delete');
$slider['list_multiaction'] = true;
