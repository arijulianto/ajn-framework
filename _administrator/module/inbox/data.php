<?php
$site['title'] = 'Mail Inbox ';
if($slug2){
	$theID = decode($slug2);
	$theID = str_replace('cont','',$theID);
	$data = $db->query("SELECT * from tbl_contact where idcontact='$theID'")->result();
	if($data['idcontact']>0)
		$site['title'] = ucfirst($slug1).' Mail';
	else
		$site['title'] = 'Error 404: Halaman Tidak Ditemukan';
}
