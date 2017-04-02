<h1 class="title">Daftar Mailbox</h1>
<div class="page">
<table class="table table-bordered table-striped table-hover table-grid">
<tr>
	<th width=30>No</th>
	<th width=120>Tanggal</th>
	<th width=100>Nama</th>
	<th>Email</th>
	<th>Pesan</th>
	<th width=80>Aksi</th>
</tr>
<?php
$no=1;
$new_pesan = $db->query("SELECT idcontact,tgl,nama,email,pesan,jawaban from tbl_contact order by tgl desc limit $start,$limit")->results();
$jml_data = $db->query("SELECT count(idcontact) as jml from tbl_contact")->result();


if($new_pesan){
foreach($new_pesan as $new){
$pesan = ringkasan($new['pesan'], 8);
$jawaban = $new['jawaban'] ? '<hr /><em>'.ringkasan($new['jawaban'], 5).'</em>' : '';
if(substr($new['tgl'],0,10)==date('Y-m-d'))
	$waktu = 'jam '.tanggal('G:i', $new['tgl']);
elseif(substr($new['tgl'],0,7)==date('Y-m'))
	$waktu = tanggal('j M H:i', $new['tgl']);
else
	$waktu = tanggal('j M Y H:i', $new['tgl']);
$idstr = encode('cont'.$new['idcontact']);
$txt = $new['jawaban'] ? 'Lihat' : 'Balas';
echo "<tr>
	<td>$no.</td>
	<td>$waktu</td>
	<td>$new[nama]</td>
	<td>$new[email]$kateg</td>
	<td>$pesan$jawaban</td>
	<td class=c><a href=\"",MODULE_URI,"/reply/$idstr\">$txt</a></td>
</tr>\n";
$no++;
}
}
?>
</table>
<?php paging($jml_data['jml']); ?>

</div>