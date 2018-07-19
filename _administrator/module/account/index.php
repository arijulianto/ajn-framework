<h1 class="title">Pengaturan Akun</h1>
<div class="content">
<form action="" method="post">
<ul class="nav nav-tabs" role="tablist">
	<li<?php echo !$slug1 ? ' class="active"' : '' ?>><a href="<?php echo MODULE_URI ?>">Profil Saya</a></li>
	<li<?php echo $slug1 ? ' class="active"' : '' ?>><a href="<?php echo MODULE_URI ?>/password">Ganti Password</a></li>
</ul>
<div class="tab-content">
<?php
if($slug1=='password')
	include MODULE_DIR.'password.php';
else
	include MODULE_DIR.'akun.php';
?>
<table class="table-form">
<tr>
	<td>&nbsp;</td>
	<td><input type="submit" name="save" class="btn btn-large btn-primary" value="Simpan Perubahan" /> &nbsp; <a onclick="history.back()" class="btn btn-inverse btn-large">&laquo; Kembali</a></td>
</tr>
</table>
</form>
</div>