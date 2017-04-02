<?php
if($slug1 && !$data['idcontact']){
	include MODULE_PATH.'404/index.php';
}else{
if($_POST['save'] && trim($_POST['reply'])){
	$save = $db->update('tbl_contact', array('jawaban'=>$_POST['reply']), array('idcontact'=>$theID))->affectedRows();

	if($save>0){
		$msg['sukses'] = 'Jawaban berhasil dikirim';
		include PLUGIN_PATH.'PHPMailerAutoload.php';
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPDebug  = 0; 
		$mail->Host= "mail.dutaniaga.co.id";
		$mail->Port = 26;
		$mail->Username = "info@dutaniaga.co.id";
		$mail->Password = "Sejahter@";
		$mail->SetFrom('info@dutaniaga.co.id', 'Admin Duta Niaga');
		$mail->AddReplyTo('info@rai.events', 'Admin Duta Niaga');
		$mail->AddAddress($contact['email'], $contact['nama']);
		$mail->Subject = 'Re: Email dari '.$contact['nama'].' (Hubungi Kami) - Duta Niaga';
		$mailbody = file_get_contents(PLUGIN_PATH.'format-email/email-contact-reply.php');
		$mailbody = str_replace('{[NAMA]}', $contact['nama'], $mailbody);
		$mailbody = str_replace('{[TGL]}', tanggal('full',$data_contact['tgl']), $mailbody);
		$mailbody = str_replace('{[PESAN]}', nl2br($contact['pesan']), $mailbody);
		$mailbody = str_replace('{[BALASAN]}', nl2br($_POST['reply']), $mailbody);
		$mail->MsgHTML($mailbody);
		$mail->Send();
	}else{
		$msg['warning'] = 'Jawaban gagal dikirim atau tidak ada yang diubah. Silahkan ulangi lagi!';
	}

}





$data = $db->query("SELECT * from tbl_contact where idcontact='$theID'")->result();

?><h1 class="title">Detail Pesan</h1>
<div class="page">

<form action="<?php echo MODULE_URI,'/',$slug1,'/',$slug2 ?>" method="POST">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';

?>
<table>
<tr>
	<td width=120 class=r>Tanggal :&nbsp;</td>
	<td><label class="input"><?php echo tanggal('full', $data['tgl']) ?></label></td>
</tr>
<tr>
	<td class=r>IP Address :&nbsp;</td>
	<td><label class="input"><?php echo $data['ip_address'] ?></label></td>
</tr>
<tr>
	<td class=r>Nama :&nbsp;</td>
	<td><label class="input"><?php echo $data['nama'] ?></label></td>
</tr>
<tr>
	<td class=r>Email :&nbsp;</td>
	<td><label class="input"><?php echo $data['email'] ?></label></td>
</tr>
<tr>
	<td class=r>Telp/HP. &nbsp;</td>
	<td><label class="input"><?php echo $data['telp'] ? $data['telp'] : '&nbsp;' ?></label></td>
</tr>
<tr>
	<td class=r>Kota :&nbsp;</td>
	<td><label class="input"><?php echo $data['kota'] ? $data['kota'] : '&nbsp;' ?></label></td>
</tr>
<tr>
	<td class=r>Pesan :&nbsp;</td>
	<td><label class="input"><?php echo nl2br($data['pesan']) ?></label></td>
</tr>
<tr>
	<td class=r>Jawaban :&nbsp;</td>
	<td><textarea name="reply" cols="30" rows="3" placeholder="Ketik jawaban di sini"><?php echo $data['jawaban'] ? $data['jawaban'] : $_POST['reply'] ?></textarea></td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td><input type="button" class="btn btn-inverse" value=" &laquo; Kembali " data-href="<?php echo MODULE_URI ?>" /> <input type="submit" name="save" class="btn btn-primary" value=" Kirim "<?php echo ($save>0 || $data['jawaban']) ? ' disabled' : '' ?> /></td>
</tr>
</table>
</form>
</div>
<?php } ?>