<?php
if($_SESSION['admin_uid']>0){
echo '<h1 class="title">Login</h1>
<article>
<div class="page">
';
}else{
echo '<div class="section-login">
';
}
?>
<style type="text/css">
.section-login{
	width: 1000px;
	margin:auto;
	border:1px solid #ccc;
	background:#f2f2f2;
}
.section-login h1{
	margin:0;
	padding:10px;
	font-size:18px;
	background:#777;
	color:#fff;
}
.section-login .center{margin:auto}
.section-login .frm-login{
	padding:100px 0;
	width:400px;
	text-align:center;
}
.section-login .frm-login input{
	width:80%;
	padding:10px 8px;
	text-align:center;
	font-size:18px;
}
</style>

<form action="<?php echo ADMIN_URL ?>login.php" method="post" class="center frm-login">
<?php
if($_GET['next']) echo '<input type="hidden" name="next" value="',$_GET['next'],'" />';
if($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';
elseif($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
else
	echo '<p class="warning">Silahkan lakukan login dengan akun login Anda.</p>';
?>
<p><input type="text" name="user_login" size="25" id="ulog" placeholder="Username or Email" value="<?php echo $_POST['user_login'] ?>"<?php echo empty($_POST['user_login']) ? ' autofocus' : '' ?> required /></p>
<p><input type="password" name="user_passwd" size="25" id="upass" placeholder="Password"<?php echo isset($_POST['user_login']) ? ' autofocus' : '' ?> required /></p>
<p><input type="submit" name="login" value="Login" class="btn btn-large btn-inverse" /></p>
</form>

<?php
if($_SESSION['admin_uid']>0){
echo '</div>
</article>
';
}else{
echo '</div>';
}
?>
