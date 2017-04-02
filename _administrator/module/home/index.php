<article>
<h1 class="title">Dashboard</h1>
<div class="action-bar">
Hallo <strong><?php echo $_SESSION['admin_nama'] ?></strong>, Selamat Datang di Administrator Panel<br />
Anda login sebagai <em><?php echo $_SESSION['admin_posisi_nama'] ?></em> pada <?php echo tanggal('H:i', $_SESSION['admin_login_time']) ?> (<?php echo durasi($_SESSION['admin_login_time']) ?>)
</div>
<div class="page">
<h2 class="title">New Inbox Message</h2>
<table class="table table-bordered table-grid table-striped table-hover">
<tr>
	<th width=30>No</th>
	<th>Tanggal</th>
	<th>Nama</th>
	<th>Email</th>
	<th>Pesan</th>
	<th>Aksi</th>
</tr>
<?php
$no=1;
$new_pesan = $db->query("SELECT idcontact,tgl,nama,email,pesan from tbl_contact where jawaban='' order by tgl desc limit 5")->results();
if($new_pesan){
foreach($new_pesan as $new){
$pesan = ringkasan($new['pesan'], 8);
if(substr($new['tgl'],0,10)==date('Y-m-d'))
	$waktu = 'jam '.tanggal('G:i', $new['tgl']);
elseif(substr($new['tgl'],0,7)==date('Y-m'))
	$waktu = tanggal('j M H:i', $new['tgl']);
else
	$waktu = tanggal('j M Y H:i', $new['tgl']);
$idstr = encode('cont'.$new['idcontact']);
echo "<tr>
	<td>$no.</td>
	<td>$waktu</td>
	<td>$new[nama]</td>
	<td>$new[email]$kateg</td>
	<td>$pesan</td>
	<td><a href=\"",ADMIN_URI,"inbox.php/reply/$idstr\" class=\"btn btn-mini\">Balas</a></td>
</tr>\n";
$no++;
}
}else{
echo "<tr>
<td colspan=6><div class=\"warning\">Belum ada pesan masuk...</div></td>
</tr>\n";
}
?>
</table>
<div class="wrap">
<div class="col-3-4">
<h2 class="title">Recent Product</h2>
<table class="table table-bordered table-grid table-striped table-hover">
<tr>
<th>No</th>
<th>Nama Barang</th>
<th>Kategori</th>
<th>Brand</th>
<th>Keterangan</th>
</tr>
<?php
$no=1;
$prod_new = $db->query("SELECT kode_barang,nama_barang,kategori,brand,diskon_persen,diskon_nominal from view_produk order by tgl_input desc limit 5")->results();
if($prod_new){
foreach($prod_new as $new){
$diskon = $new['diskon_persen']>0 ? "$new[diskon_persen]%" : format_angka($new['diskon_nominal']);
$diskon = $diskon ? "Disc Promo $diskon" : '&nbsp;';
echo "<tr>
<td>$no.</td>
<td>[$new[kode_barang]] $new[nama_barang]</td>
<td>$new[kategori]</td>
<td>$new[brand]</td>
<td>$diskon</td>
</tr>\n";
$no++;
}
}else{
echo "<tr>
<td colspan=5><div class=\"warning\">Belum ada data barang...</div></td>
</tr>\n";
}
?>
</table>
</div>
<div class="col-1-4">
<div class="pad">
<h3 class="title">Overview Stats</h3>
<?php
$overview['barang'] = $db->query("SELECT count(idproduk) as jml from tbl_produk")->result();
$overview['kategori'] = $db->query("SELECT count(idkategori) as jml from tbl_produk_kategori")->result();
$overview['brand'] = $db->query("SELECT count(idbrand) as jml from tbl_produk_brand")->result();
$overview['slider'] = $db->query("SELECT count(idslider) as jml from tbl_slider")->result();
$overview['customer'] = $db->query("SELECT count(iduser) as jml from tbl_user where posisi='10'")->result();
?>
<table>
<tr><td class=r width=30><?php echo format_angka($overview['barang']['jml']) ?></td><td>Barang</td></tr>
<tr><td class=r><?php echo format_angka($overview['kategori']['jml']) ?></td><td>Kategori</td></tr>
<tr><td class=r><?php echo format_angka($overview['brand']['jml']) ?></td><td>Brand</td></tr>
<tr><td class=r><?php echo format_angka($overview['slider']['jml']) ?></td><td>Slider</td></tr>
<tr><td class=r><?php echo format_angka($overview['customer']['jml']) ?></td><td>Customer</td></tr>
</table>
</div>
</div>
</div>

<div class="wrap">
<div class="col-1-3">
<h3 class="title">Recent Kategori</h3>
<ul>
<?php
$last_cat = $db->query("SELECT kategori,kategori_sub,tgl_input,creator from view_produk_kategori order by tgl_input desc limit 5")->results();
if($last_cat){
foreach($last_cat as $cat){
$kategori = $cat['kategori_sub'] ? "$cat[kategori_sub] &raquo; $cat[kategori]" : $cat['kategori'];
echo "<li>$kategori<br /><small>oleh $cat[creator] (",durasi($cat['tgl_input']),")</small></li>";
}
}else{
echo '<li>Belum ada data kategori</li>';
}
?>
</ul>
</div>
<div class="col-1-3">
<div class="pad row">
<h3 class="title">Recent Customer</h3>
<ul>
<?php
$last_user = $db->query("SELECT nama,tgl_register,kota,gender from view_datauser where posisi='10' order by tgl_register desc limit 5")->results();
if($last_user){
foreach($last_user as $user){
echo "<li>$user[nama] ($user[gender]) - $user[kota]<br /><small>",durasi($user['tgl_register']),"</small></li>";
}
}else{
echo '<li>Belum ada data Customer</li>';
}
?>
</ul>
</div>
</div>
<div class="col-1-3">
<h3 class="title">Recent Promo</h3>
<ul>
<?php
$last_promo = $db->query("SELECT nama_barang,kategori,diskon_persen,diskon_nominal,tgl_input from view_produk where diskon_persen>0 OR diskon_nominal>0 order by tgl_input desc limit 5")->results();
if($last_promo){
foreach($last_promo as $promo){
$diskon = $promo['diskon_persen']>0 ? "Disc $promo[diskon_persen]%" : 'Pot. Rp. '.format_angka($promo['diskon_nominal']);
echo "<li>$promo[nama_barang] &laquo; $promo[kategori]<br /><small>$diskon (",durasi($promo['tgl_input']),")</small></li>";
}
}else{
echo '<li>Belum ada promo terbaru</li>';
}
?>
</ul>
</div>
</div>
</div>
<div class="action-bar">
<p>Jangan lupa lakukan Logout setelah selesai demi keamanan website Anda!</p>
</div>
</article>