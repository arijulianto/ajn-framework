<div style="width:90%;margin:auto">
<p><strong><?php echo APP_NAME ?></strong> is a web based solution that your employees can use anytime and anywhere</p>
<p>It is very important and can used by the company in organizing and managing employees perform.</p>

<h1 class="title">LOGIN MEMBER</h1>
<form action="<?php echo SITE_URL ?>login" method="post">
	<input type="hidden" name="next" value="<?php echo substr($_SERVER['REQUEST_URI'], 0, strlen(SITE_URI)+5)==SITE_URI.'login' ? SITE_URI : $_SERVER['REQUEST_URI']; ?>" />
	<?php if($msg['warning']) echo '<p class="warning">',$msg['warning'],'</p>'; ?>

	<p><label for="user">Username atau Email</label><br />
	<input type="text" data-type="safename" name="user" id="user" size="30" value="<?php echo $_POST['user']; ?>" class="input" placeholder="Username atau Email" required<?php echo empty($_POST['user'] || ($msg['warning'])) ? ' autofocus' : ''; ?> /></p>

	<p><label for="pass">Password</label><br />
	<input type="password" name="pass" id="pass" size="30" class="input" placeholder="password" required<?php echo $_POST['user'] ? ' autofocus' : ''; ?> /></p>

	<p><label><input type="checkbox" name="rememberme"<?php if($_POST['rememberme']) echo " checked"; ?> /> Remember me</label> | <a data-href="<?php echo SITE_URI ?>login/recover">Lupa Password?</a></p>
	<p><input type="submit" name="login" class="btn btn-success btn-large" value="  Login  " /></p>
</form>
</div>