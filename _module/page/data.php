<?php
if(isset($slug1) && $slug1=='about')
	$site['title'] = 'Tentang Kami';
elseif(isset($slug1) && $slug1=='contact')
	$site['title'] = 'Hubungi Kami';
else
	$site['title'] = 'Halaman Statis';