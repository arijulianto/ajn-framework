<div class="starter-template">
<h1 class="judul animated bounce infinite">AJN Framework + Bootstrap</h1>
<p class="lead animated shake infinite">Gunakan bootstrap untuk memulai proyek baru dengan cepat. <br />
Ini adalah halaman default untuk project pertama Anda.<br />Manfaatkan kehebatan Bootstrap menggunakan AJN Framework!</p>
</div>

<script>
$(function(){
    $('.judul').on('click', function(){
        $('.judul').addClass('animated shake');
        setTimeout(function(){
            $('.judul').attr('class', 'judul');
        }, 1000)
    })
})
</script>