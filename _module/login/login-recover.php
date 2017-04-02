<div style="width:90%;margin:auto">
<h1 class="title">Pemulihan Password</h1>
<p>Kami akan menuntun Anda untuk mereset password Anda melalui beberapa langkah aman</p>

<div class="uiBox corner-all">
<div class="uiContent">
<form action="" method="post">
<?php
$resetinfo = array();
$btnproses = 1;

// proses pemeriksaan input user
if($_POST['s']==1)
	$resetinfo[1] = "sip 1";
elseif($_POST['s']==2)
	$resetinfo[2] = "sip 2";
elseif($_POST['s']==3)
	$resetinfo[3] = "sip 3";
elseif($_POST['s']==4)
	$resetinfo[4] = "sip 4";
elseif($_POST['s']==5)
	$resetinfo[5] = "sip 5";


if($resetinfo[5]){
echo '<p>Sip... password password telah diubah jadi <b>xlasGSLH</b>. Harap SEGERA UBAH</p>';
$btnproses = 0;
}
elseif($resetinfo[4]){
echo '<h2>Password Baru</h2>
<p>Silahkan masukkan passsword baru Anda</p>

<input type="hidden" name="s" value="5" />';
}
elseif($resetinfo[3]){
echo '<h2>Review Identitas</h2>
<p>Silahkan periksa ulang informasi akun Anda. Jika Anda sudah yakin ingin mengubah password akun silahkan klik tombol Lanjutkan!</p>

<input type="hidden" name="s" value="4" />';
}
elseif($resetinfo[2]){
echo '<h2>Pertanyan Kemanan</h2>
<p>Silahkan jawab pertanyaan keamanan Anda</p>

<input type="hidden" name="s" value="3" />';
}
elseif($resetinfo[1]){
echo '<h2>Masukkan Informasi Pribadi</h2>
<p>Silahkan isi informasi pribadi di bawah ini untuk memastikan bahwa Anda adalah pemilik sah akun ini</p><br>

<input type="hidden" name="s" value="2" />';
}
else{
echo '<h2>Identifikasi Akun</h2>
<p>Silahkan masukkan username, Email, Telepon, atau Nama Lengkap</p><br>

<input type="hidden" name="s" value="1" />';
}
?>
</div>
<div class="uiNav"><?php echo $btnproses==1 ? '<input type="button" class="btn btn-inverse" value=" Batal " onclick="location.href=\'../home\'" /> <input type="submit" name="procc" class="btn btn-primary" value=" Selanjutnya&raquo; " />' : '<input type="button" class="btn orange large" value=" Login Sekarang " />'; ?>
</form>
</div>
</div>
</form>