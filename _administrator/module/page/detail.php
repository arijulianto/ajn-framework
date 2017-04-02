<?php

if($_POST['save'] && $_POST['title'] && $_POST['body']){
	$save = $db->update('tbl_page', array('title'=>$_POST['title'], 'body'=>$_POST['body']), array('slug'=>$slug1))->affectedRows();


	if($save>0)
		$msg['sukses'] = 'Perubahan berhasil disimpan';
	else
		$msg['warning'] = 'Perubahan gagal disimpan atau tidak ada yang diubah. Silahkan ulangi lagi!';

}

$data = $db->query("SELECT title,body from tbl_page where slug='$slug1'")->result();

?><h1 class="title">Edit Halaman Statis: <?php echo $data['title'] ?></h1>
<div class="page">



<form action="<?php echo MODULE_URI,'/',$slug1 ?>" method="POST">
<?php
if($msg['sukses'])
	echo '<p class="sukses">',$msg['sukses'],'</p>';
elseif($msg['warning'])
	echo '<p class="warning">',$msg['warning'],'</p>';

?>
<label>Judul<br />
<textarea name="title" style="width: 800px; height: 50px" placeholder="Judul"><?php echo $data['title'] ?></textarea></label><br />
<textarea name="body" class="editor" style="width: 600px; height: 200px" placeholder="Konten Body" autofocus><?php echo $data['body'] ?></textarea><br />
<input type="button" class="btn btn-inverse" value=" &laquo; Kembali " onclick="location.href='<?php echo MODULE_URI ?>'" />
<input type="submit" name="save" class="btn btn-primary" value=" Simpan Perubahan " onClick="editor.post();" />
</form>
</div>