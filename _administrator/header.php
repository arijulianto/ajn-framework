<!DOCTYPE HTML>
<html>
<head>
<title><?php echo $site['title'] ? $site['title'].' - ' : '' ?>Administrator Panel</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Web Admin Template" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/bootstrap-3.3.4.min.css"  type="text/css" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/style.min.css"  type="text/css" />
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/fonts-icons.min.css" /> 
<link rel="stylesheet" href="<?php echo ADMIN_URI ?>scripts/jquery-ui-1.12.1.min.css" /> 
<link rel="shortcut icon" href="<?php echo SITE_URI ?>favicon.ico" />
<!--[if IE]>
<script>(function(){var a=("abbr,article,aside,audio,canvas,datalist,details,figure,footer,header,hgroup,mark,menu,meter,nav,output,progress,section,time,video").split(",");for(var b=0;b<a.length;b++){document.createElement(a[b])}try{document.execCommand("BackgroundImageCache",false,true)}catch(c){}})();</script>
<![endif]-->
<script src="<?php echo ADMIN_URI ?>scripts/jquery-1.11.2.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/plugins.validInput.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/bootstrap-3.3.4.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/jquery-ui-1.12.1.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/metisMenu.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/tinymce.min.js"></script>
<script src="<?php echo ADMIN_URI ?>scripts/custom.min.js"></script>
<script>
$(function(){
$.fn.autoRemove=function(){
    var $this = this;
    $(this).fadeTo(3e3, 500).slideUp(500,function(){$this.remove()});
}
$.fn.center=function(){
    var el=$(this),
    o=$(window),
    ew=el.width(),
    eh=el.height(),
    ow=o.outerWidth(),
    oh=o.outerHeight();
    $(this).css({'right':(ow/2)-(ew/2),'top':(oh/2)-(eh/2)})
};

var adm = <?php
$adm_login = array('uuid'=>(int)str2num('adm'.$_SESSION['admin_uid']),'host'=>ADMIN_URI,'user'=>$_SESSION['admin_nama'], 'hash'=>str2hex('nDbKcx'.date('H:i')).'-'.str2hex(substr(DOMAIN,0,2).date('ymdi')).'-'.str2hex(random()));
echo json_encode($adm_login);
?>;
$('[data-type="datepicker"]').on('focusin',function(){
    var format = $(this).attr('data-format') || 'dd/mm/yy';
    var minDate = $(this).attr('data-mindate') || null;
    var maxDate = $(this).attr('data-maxdate') || null;
    var noLibur = $(this).attr('data-nolibur') || null;
    var $this = $(this);

    $('[data-type="datepicker"]').datepicker("destroy"); 
    $(this).datepicker({
        dateFormat:format,
        minDate:minDate,
        maxDate:maxDate,
        changeMonth:true,
        changeYear:true,
        beforeShowDay:function(date){
            var ymd = date.getFullYear()+''+(date.getMonth()+1)+''+date.getDate();
            var day = date.getDay();
            var res = [true];
            if (day == 0) {
                res = [true, 'dateMing']
            }
            return res
        }
    });
});
$('.delete-item').on('click', function(){
    var tanya = confirm('Anda yakin ingin menghapus item gambar ini?');
    if(tanya){
        if($(this).closest('.item').hasClass('edit')){
            $(this).closest('.action').find('[type="checkbox"]').prop('checked',true);
            $(this).closest('.item').hide();
        }else{
            $(this).closest('.item').remove();
        }
    }
});
$('.inline-form-edit').on('click', function(){
    var data = JSON.parse($(this).attr('data-detail').replace(/'/g,'"'));
    var form = $(this).attr('data-form');
    var Image = $(this).attr('data-image');
    if(data){
        $.each(data, function(k,v){
            $(form).find('[name="'+k+'"]').val(v);
        });
        if(Image){
            $(form).find(Image).prop('src', data.picture);
        }
        $(form).find('.t-clear').show();
        $('html,body').animate({
            scrollTop:$(form).offset().top,
        }, 300);
    }
});
$('.t-clear').on('click', function(){
    $(this).closest('form').find('[name]').val('');
    $(this).hide();
});
$('[data-slug]').on('keyup', function(){
    var char = char = $(this).val().replace(/[:!@#$%^&*()+ ]/g,'-').toLowerCase();
    var targ = $(this).attr('data-slug');
    char = char.replace(/---/g,'-');
    char = char.replace(/--/g,'-');
    if(char.length>120){
        char = char.substring(0,120);
    }
    $(targ+'.val').val(char);
    $(targ+'.txt').html(char);
});
$('[data-copyto]').on('keyup', function(){
    var char = char = $(this).val();
    var targ = $(this).attr('data-copyto');
    $(targ+'.val').val(char);
    $(targ+'.txt').html(char);
});
$('.do-action').on('click',function(){
    var targ = $(this).attr('data-href');
    var type = $(this).attr('data-type');
    var action = $(this).attr('class').replace('do-action ','');
    var contents = {
        activate:{title:"Aktifkan "+type.toTitleCase(),body:"Anda yakin ingin mengaktifkan "+type+" ini?",class:"btn-success"},
        deactivate:{title:"Nonaktifkan "+type.toTitleCase(),body:"Anda yakin ingin menonaktifkan "+type+" ini?",class:"btn-warning"},
        delete:{title:"Hapus "+type.toTitleCase(),body:"Data yang sudah dihapus tidak dapat dikembalikan.<br />Anda yakin ingin menghapus "+type+" ini?",class:"btn-danger"},
    }
    var html = '<div class="modal modal-action fade"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title">'+contents[action].title+'</h4></div><div class="modal-body"><p>'+contents[action].body+'</p></div><div class="modal-footer"><input type="hidden" class="data-pid" value="'+$(this).attr('data-pid')+'" /><input type="hidden" class="data-act" value="'+action+'" /><button type="button" class="btn '+contents[action].class+' modal-action-ok">Yes</button><button type="button" class="btn btn-default" data-dismiss="modal">No</button></div></div></div></div>';
    $('.modal-action').remove();
    $(html).appendTo('body').modal({show:true, backdrop:'static'});
    return false;
});

$('.autosubmit').on('change', function(){
    $(this).closest('form').submit();
});
$('.check-file').on('change', function(){
    var cek = $(this).is(':checked');
    if(cek){
        $('.overlay:not(:first),.popup-dialog:not(:first)').remove();
        var file = JSON.parse($(this).closest('li').find('label').attr('data-file').replace(/'/g, '"'));
        $('.dialog-file-action').find('.file-preview').prop('src',file.URI+'/'+file.name);
        $('.dialog-file-action').find('.sFileName').html(file.name);
        $('.dialog-file-action').find('.sFileSize').html(file.size);
        $('.dialog-file-action').find('.sFileType').html(file.type);
        $('.dialog-file-action').find('.sFileURL').val(file.URL+'/'+file.name);
        $('.dialog-file-action').find('.dTarget').val(file.dir+'/'+file.name);
        $('.dialog-file-action').show();
        console.log(file)

        $('.popup-dialog').center();
        //$('.popup-dialog').draggable({handle:'div.title-pop',containment:'section',scroll:false});
    }
});
$('.hide-popup-dialog').on('click', function(){
    $('.dialog-file-action').hide();
});
$('.ajax-upload').on('change', function(){
    if(this.files.length){
        var strFile = [];
        $.each(this.files,function(i,file){
            strFile.push(file.name+' ('+file.size.fileSizeName()+')')
        })
        var tanya = confirm(this.files.length+' file ini akan diupload.\n'+strFile.join('\n')+'\nAnda yakin ingin mengupload '+this.files.length+' file ini sekarang?');
        if(tanya){
            $(this).closest('form').submit();
            /*$(this).ajaxForm({
                url:'<?php echo MODULE_URI ?>/file.json',
                type:'post',
                success:function(res){
                    alert(res.message)
                    if(res.status==1) self.location.reload()
                }
            });*/
        }
    }
});
$('.btn-rename-file').on('click', function(){
    var name = $(this).closest('.dialog-file-action').find('.sFileName').html();
    $('.btn-rename-file,.btn-delete-file,.file-details,.hide-popup-dialog').hide();
    $('.btn-rename-file-ok,.file-rename,.cancel-rename').show();
    $('.new-file-name').val(name).focus();
    $('.dAction').val('rename');
});
$('.btn-rename-file-ok').on('click', function(){
    var form = $('.dialog-file-action');
    $.ajax({
        url:form.attr('action'),
        type:'post',
        data:form.serialize(),
        dataType:'json',
        success: function(res){
            alert(res.message);
            if(res.status==1) self.location.reload()
        }
    })
});
$('.btn-delete-file').on('click', function(){
    $('.dAction').val('delete');
    var form = $('.dialog-file-action');
    var tanya = confirm('Hati-hati dalam menghapus file. Jika file telah digunakan dapat menyebabkan file tidak tampil di web!\nAnda yakin ingini menghapus file \''+$('.dialog-file-action .sFileName').text()+'\' secara permanen?');
    if(tanya){
        $.post(form.attr('action'), form.serialize(), function(res){
            alert(res.message);
            if(res.status==1){
                self.location.reload();
            }
        })
    }
});
$('.cancel-rename').on('click', function(){
    $('.btn-rename-file,.btn-delete-file,.file-details,.hide-popup-dialog').show();
    $('.btn-rename-file-ok,.file-rename,.cancel-rename').hide();
});

$(document).on('click', '.modal-action-ok', function(){
    console.log('okeeeee')
    var pid = $('.modal-action').find('.data-pid').val();
    var act = $('.modal-action').find('.data-act').val();
    var type = $('.do-action[data-pid="'+pid+'"]').attr('data-type');
   $('.modal-action').modal('hide');
   $.ajax({
    url:$('.do-action.'+act+'[data-pid="'+pid+'"]').attr('data-href'),
    type:'post',
    data:{type:type,pid:pid,action:act},
    cache:false,
    success:function(res){
        var sukses = $(res).find('section').find('.alert.actions')
        if(sukses){
            alert(sukses.text());
            if(sukses.hasClass('alert-success')) self.location.href = self.location.href;
        }else{
            alert('Permintaan tidak dapat diproses. Silahkan coba lagi!');
        }
    }
   })
});
$(document).on('change','input[type="file"][data-preview]', function(){
    var targ = $(this).attr('data-preview');
    var fs = $(this).attr('data-maxsize') ? parseInt($(this).attr('data-maxsize')) : -1;
    var dim = $(this).attr('data-dimension') ? $(this).attr('data-dimension').split('x') : [0,0];
    var src = $(this).attr('src')||'';
    var $this = $(this);

    if(fs==-1 || this.files[0].size<=fs){
        if(this.files && this.files[0] && this.files[0].type.match('image*')){
            var reader = new FileReader();
            var newimage = new Image();
            var files = this.files[0];
            reader.onload = function(e){
                dim[0] = isNaN(dim[0]) ? parseInt(dim[0]) : dim[0];
                dim[1] = isNaN(dim[1]) ? parseInt(dim[1]) : dim[1];
                newimage.src = e.target.result;
                if(dim[0]>0 && dim[1]>0){
                    newimage.onload = function(){
                        if(newimage.width<dim[0] || newimage.height<dim[1]){
                            alert('File \''+files.name+'\' terlalu kecil ('+newimage.width+' x '+newimage.height+' px).\nHarap pilih file dengan ukuran '+dim[0]+' x '+dim[1]+' px atau kelipatannya!');
                            $this.val('');
                        }else if(newimage.width/newimage.height == dim[0]/dim[1]){
                            $this.closest('div').find('img'+targ).attr('src',e.target.result);
                        }else{
                            alert('File \''+files.name+'\' memiliki ukuran '+newimage.width+' x '+newimage.height+' px.\nHarap pilih file dengan ukuran '+dim[0]+' x '+dim[1]+' px atau kelipatannya!');
                            $this.val('');
                        }
                        
                    }
                }else{
                    $this.closest('div').find('img'+targ).attr('src',e.target.result);
                }
            }
            reader.readAsDataURL(this.files[0]);
        }else{
            alert('File \''+this.files[0].name+'\' bukan file gambar. Harap pilih file gambar saja dengan format JPG, PNG, atau GIF');
            $(this).val('');
            //if(src=='') $this.closest('div').find('img'+targ).attr('src','');
        }
    }else{
        alert('Ukuran file \''+this.files[0].name+'\' terlalu besar ('+this.files[0].size.fileSizeName()+')!\nMaksimal diperbolehkan adalah '+fs.fileSizeName());
        $(this).val('');
        //if(src=='') $this.closest('div').find('img'+targ).attr('src','');
    }
});
$(document).on('change', '.check-radio', function(){
    var cek = $(this).is(':checked');
    if(cek){
        $('.check-radio').removeAttr('checked');
        $(this).prop('checked', true);
    }
});
$(document).on('change','.image input[type="file"]', function(){
    if($(this).closest('.item').hasClass('edit')==false){
        var item = $(this).closest('.item').clone();
        var no = $(this).closest('.item').find('input[type="radio"]').attr('id').split('-');
        var nno = parseInt(no[no.length-1])+1;
        $(this).closest('.item').find('.action').show();
        item.find('input[type="radio"]').prop('id','main-im-'+nno);
        item.find('input[type="file"]').val('');
        item.find('.item-main').prop('for','main-im-'+nno);
        item.find('.image input[type="file"]').prop('id','file-im-'+nno);
        item.find('.image label').prop('for','file-im-'+nno);
        item.appendTo($(this).closest('.items'));
    }
});





if($('.editor').length){
    editor()
}
if($('.editor-save').length){
    editor_save()
}
if($('.editor-simple').length){
    editorSimple()
}
if($('.editor-simple2').length){
    editorSimple2()
}
if($('.editor-simple-save').length){
    editorSimple_save()
}
if($('.editor-simple2-save').length){
    editorSimple2_save()
}

function editor(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
        menubar:false,
        toolbar1: 
            "formatselect fontselect fontsizeselect forecolor | copy cut paste | code fullscreen charmap"
        ,
        toolbar2: 
            "bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image media link"
    });
}
function editor_save(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor-save",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
        menubar:false,
        toolbar1: "back save | formatselect fontselect fontsizeselect forecolor | copy cut paste | code fullscreen charmap",
        toolbar2: "bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image media link",
        setup : function(ed) {
            ed.addButton('save', {
                title : 'Save',
                class : 'mce-i-save',
                onclick : function() {
                    tinyMCE.triggerSave();
                    $('.editor-save').parents('form').submit();
                }
            });
            ed.addButton('back', {
                title : 'Back',
                icon : ' mce-i-undo',
                onclick : function() {
                   history.back()
                }
            });
        }
    });
}
function editorSimple(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor-simple",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
        menubar:false,
        toolbar1: "bold italic underline removeformat | alignleft aligncenter alignright alignjustify | link code fullscreen"
    });
}
function editorSimple2(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor-simple2",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap",
        menubar:false,
        toolbar1: "bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image link | code fullscreen"
    });
}
function editorSimple_save(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor-simple-save",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap table",
        menubar:false,
        toolbar1: "back save | bold italic underline removeformat | alignleft aligncenter alignright alignjustify | link code fullscreen",
        setup : function(ed) {
            ed.addButton('save', {
                title : 'Save',
                class : 'mce-i-save',
                onclick : function() {
                    tinyMCE.triggerSave();
                    $('.editor-simple-save').parents('form').submit();
                }
            });
            ed.addButton('back', {
                title : 'Back',
                icon : ' mce-i-undo',
                onclick : function() {
                   history.back()
                }
            });
        }
    });
}
function editorSimple2_save(){
    tinyMCE.remove();
    tinyMCE.init({
        selector: "textarea.editor-simple2-save",
        plugins: "image link code fullscreen wordcount textcolor autoresize charmap table",
        menubar:false,
        toolbar1: "back save | bold italic underline removeformat | alignleft aligncenter alignright alignjustify | bullist numlist | image link | code fullscreen",
        setup : function(ed) {
            ed.addButton('save', {
                title : 'Save',
                class : 'mce-i-save',
                onclick : function() {
                    tinyMCE.triggerSave();
                    $('.editor-simple-save').parents('form').submit();
                }
            });
            ed.addButton('back', {
                title : 'Back',
                icon : ' mce-i-undo',
                onclick : function() {
                   history.back()
                }
            });
        }
    });
}

});
</script>
</head>
<body>
<div id="wrapper">
    <nav class="top1 navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?php echo ADMIN_URI ?>index.php"><img src="<?php echo SITE_URI ?>media/images/logo.png" style="height:50px;float:left;margin-top:-14px;margin-right:10px" class="hidden-xs" />Administrator Panel</a>
        </div>
        <ul class="nav navbar-nav navbar-right hidden-xs">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle avatar" data-toggle="dropdown"><i class="fa fa-gear"></i></a>
                <ul class="dropdown-menu">
                    <li class="m_2">Hallo <strong><?php echo $_SESSION['admin_nama'] ?></strong></li>
                    <li class="m_2"><a href="<?php echo SITE_URI ?>" target="_blank"><i class="fa fa-globe"></i> Lihat Website</a></li>
<?php if(is_string($adm['login_source'])){ ?>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>account.php"><i class="fa fa-user"></i> Profile</a></li>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>account.php/password"><i class="fa fa-usd"></i> Ganti Password</a></li>
<?php } ?>
                    <li class="m_2"><a href="<?php echo ADMIN_URI ?>logout.php"><i class="fa fa-lock"></i> Logout</a></li>   
                </ul>
            </li>
        </ul>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li><a href="<?php echo ADMIN_URI ?>index.php"<?php echo (MODULE=='home' || MODULE=='index') ? ' class="current"' : '' ?>><i class="fa fa-home nav_icon"></i>Dashboard</a></li>
<?php
if($menu['nav']){
foreach($menu['nav'] as $link=>$mnu){
    $links = explode('/', $link);
    if(substr($link,0,1)=='#'){
        $md = '#';
    }else{
        $md = str_replace('.php','',$links[0]);
    }
    if(is_array($mnu)){
        echo "<li><a href=\"",ADMIN_URI,"$link\"",MODULE==$md ? ' class="current"' : '',">",($mnu['icon']?'<i class="fa fa-'.$mnu['icon'].' nav_icon"></i>':''),"$mnu[label]",($mnu['menu']?'<span class="fa arrow"></span>':''),"</a>";
        if($mnu['menu']){
            echo "<ul class=\"nav nav-second-level\">";
            foreach($mnu['menu'] as $sLink=>$sLabel){
                $e = explode('/', $sLink);
                $md = str_replace('.php','',$e[0]);
                echo "<li class=\"nav-item item-sub\"><a class=\"nav-link",((MODULE==$md && $slug1==$e[1]) ? ' current' : ''),"\" href=\"",ADMIN_URI,$sLink,"\">$sLabel</a></li>";
            }
            echo "</ul>";
        }
        echo "</li>\n";
    }else{
        echo "<li><a href=\"",ADMIN_URI,"$link\"",MODULE==$md ? ' class="current"' : '',">$mnu</a></li>\n";
    }
}
}
?>
                    <li><a href="<?php echo ADMIN_URI ?>media.php"<?php echo MODULE=='media' ? ' class="current"' : '' ?>><i class="fa fa-image nav_icon"></i>Media Manager</a></li>
                    <li><a href="<?php echo $menu['setting'] ? '#' : 'setting.php' ?>"<?php echo MODULE=='setting' ? ' class="current"' : '' ?>><i class="fa fa-cogs nav_icon"></i>Seting<?php echo $menu['setting'] ? '<span class="fa arrow"></span>' : '' ?></a><?php
if($menu['setting']){
echo "<ul class=\"nav nav-second-level\">";
foreach($menu['setting'] as $sLink=>$sLabel){
    $e = explode('/', $sLink);
    $e[0] = str_replace('.php','',$e[0]);
    echo "<li class=\"nav-item item-sub\"><a class=\"nav-link",((MODULE==$e[0] && $slug1==$e[1]) ? ' current' : ''),"\" href=\"",ADMIN_URI,$sLink,"\">$sLabel</a></li>";
}
echo '</ul>';
}
                    ?></li>
<?php if(is_string($adm['login_source'])){ ?>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>account"><i class="fa fa-user nav_icon"></i> Profile</a></li>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>account/password"><i class="fa fa-usd nav_icon"></i> Ganti Password</a></li>
<?php } ?>
                    <li class="hidden-md hidden-sm hidden-lg"><a href="<?php echo ADMIN_URI ?>logout"><i class="fa fa-lock nav_icon"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="page-wrapper">
