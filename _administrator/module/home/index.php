<?php
$level = array(1=>'User','Kontributor','Administrator',99=>'Developer', 999=>'Admin Force');
?>
<h1 class="title">Administrator Panel</h1>
<div class="content">
<p>Selamat datang <?php echo $_SESSION['admin_nama'] ?>, Anda login sebagai <?php echo $level[$_SESSION['admin_level']],' pada ',tanggal('full', $_SESSION['admin_login']),' (',durasi($_SESSION['admin_login']),')' ?>. Demi keamanan, jangan lupa lakukan logout setelah selesai.<br />&nbsp;</p>
</div>
