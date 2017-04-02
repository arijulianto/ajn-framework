<h1 class="title">Data Halaman Statis</h1>
<div class="page">
<table class="table table-bordered table-striped table-hover table-grid">
<tr>
	<th width="30">No.</th>
	<th>Judul Halaman</th>
	<th>URI</th>
	<th width="150">Aksi</th>
</tr>
<?php
$datas = $db->query("SELECT idpage,title,body,slug from tbl_page where slug!='home' order by title")->results();

foreach($datas as $no=>$data){
$no++;
$uri = $data['slug']!='home' ? $data['slug'].'.html' : '';
echo "<tr>
	<td>$no.</td>
	<td>$data[title]</td>
	<td>/$uri</td>
	<td class=c><a href=\"",MODULE_URI,"/$data[slug]\">Edit</a></td>
</tr>\n";
}
?>
</table>
</div>