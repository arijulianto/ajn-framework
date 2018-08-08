<?php
/* Module Setting */
$module['module'] = 'Halaman Statis';
$module['mode'] = 'list,new,edit,activate,delete';
$module['fields'] = 'idpage,slug,title,body,aktif';
$module['table'] = 'tbl_page';
$module['view'] = 'tbl_page';
$module['id'] = 'idpage';
$module['input'] = 'Tambah Baru';
$module['filter'] = array(
					'aktif'=>['combo'=>'st:=>Semua||1=>Aktif||0=>Tidak Aktif'],
					'title/body'=>['search'=>'q1:=>%||Cari Konten{size="10"}'],
				);
$module['vars'] = array('aktif'=>'1=>Aktif||0=>Tidak Aktif');

/* Table List Page */
$module['list_header'] = array('No//15','Judul//0','Halaman','Konten','Status','Aksi');
$module['list_data'] = array('idpage','title','slug','body','aktif','action');
$module['list_format'] = array('slider'=>'image','slug'=>'titlecase','body'=>'concat(bold:title,<br />,ringkasan:body*2');
$module['list_align'] = array('tgl'=>'center','urutan'=>'center','aktif'=>'center');
$module['list_multiaction'] = false;
$module['list_order'] = '';
$module['where'] = "";


/* Form */
$module['form'] = array(
		'slug'=>'Slug URI::text(placeholder="URI")',
		'title'=>'Judul::text(class="input-block" size="30" placeholder="Judul")',
		'body'=>'Konten::textarea(cols="30" rows="10" class="editor" placeholder="Konten")',
	);
$module['input_required'] = 'slug,body';
$module['edit_required'] = 'slug';
$module['unique'] = '';
//$module['upload'] = true;
//$module['track'] = true;
//$module['track_user'] = true;
//$module['track_time'] = true;
