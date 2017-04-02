<?php
$users['module'] = 'Daat User';
$users['mode'] = array('list','detail','action');
$users['table'] = 'tbl_users';
$users['view'] = 'view_users';
$users['filter'] = array('status::[=>Semua||1=>Aktif||0=>Tidak Aktif]','keterangan');
$users['urutan'] = array('1=[Aktif]','0=[Tidak Aktif]');

$users['list_header'] = array('No','Nama Lengkap','L/P','Kota','Tgl Lahir','Jabatan','Status','Aksi');
$users['list_detail'] = array('iduser','nama','gender','kota','tgl_lahir','jabatan','aktif','edit,activatedeactivate,delete');
$users['list_multiaction'] = true;
