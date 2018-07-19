# AJN Framework
AJN Framework adalah framework untuk website dengan menggunakan bahasa pemrograman PHP. AJN Framework berbeda dengan framework yang sudah beredar seperti CI, Laravel, dll. AJN Framework menggunakan konsep module, bukan dengan konsep MVC sehingga developer dibuat lebih leluasa karena bekerja persis seperti native coding namun sudah memiliki library dan struktur yang diperlukan dalam development.


## Requirement
- PHP versi 5.x (disarankan menggunakan PHP 5.4 ke atas)
- Database MySQL (atau database lain yang akan digunakan)


## Fitur
Fitur standar dalam development sebuah website statis hingga dinamis sudah tersedia di AJN Framework. Adapun fitur yang dimaksud adalah seperti berikut:
- Library: berupa fungsi dasar yang sudah ditranslasikan mencakup fungsi olah tanggal dan waktu, formatting, debugging, enkripsi, dekripsi, converter, dll.
- Plugin: pengguna bisa membuat atau menambahkan plugin buatannya maupun plugin pihak ketiga yang bisa diseting autoload (otomatis) atau manual
- Database: sudah disediakan fungsi olah database untuk melakukan query dengan support beberapa jenis database. Sementara hanya support MySQL, namun kedepannya akan mendukung berbagai jenis database lainnya seperi SQL Server, Oracle, dll
- Template: dukungan multi template dengan template bawaan. gunakan framework ternama seperti bootstrap
- Login otomatis: sistem login untuk user dan admin yang simpel dan otomatis (tidak perlu buat halaman cek login, semua hanya butuh konfigurasi yang simpel)
- Admin dinamis: tidak perlu repot untuk membuat halaman CRUD di halaman admin, AJN Framework sudah mendukung admin dinamis, cukup deklarasikan kebutuhan, halaman daftar, edit, input, hapus dan aktif/nonaktif langsung bisa digunakan
- Force login: manfaatkan fitur force login untuk admin tanpa membuat tabel user


## Setup
Untuk menggunakan AJN Framework dibutuhkan beberapa step setup yang sangat mudah.
* Folder tempat framework akan diinstall
* Seting default website
* Seting database (jika diperlukan koneksi database)

## Database Support
AJN Framework mendukung beberapa jenis database
- MySQL
- SQL Server (partial)
- SQLite (segera)

## Dokumentasi Lengkap: menyusul (sedang dirancang)
