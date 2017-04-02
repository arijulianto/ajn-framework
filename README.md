# AJN Framework
AJN Framework adalah framework untuk website dengan menggunakan bahasa pemrograman PHP

## System Requirement
1. PHP versi 5.4

## Installasi
Ubah <project-folder> dengan nama folder sesuai nama folder project
Jika sudah online atau disimpan di folder utama (public_html, www_root, htdocs) atur saja `RewriteBase /`
1. Setting `.htaccess` di folder utama
```
RewriteBase /<project-folder>/
```
2. Setting Default Website di file `<project-folder>/_system/config/website.php`
```
define('SITE_DIR', '<project-folder>');
```
3. Setting Database di file `<project-folder>/_system/config/database.php`
```
// The name of the database
define('DB_NAME', '');

// database username
define('DB_USER', 'root');

// database password
define('DB_PASSWORD', '');

// hostname
define('DB_HOST', 'localhost');

// database type
define('DB_TYPE', 'mysql');
```

Note:
* `DB_NAME`: nama database yang akan digunakan. Harap isi dengan `''` jika tidak ingin melakukan koneksi ke database (website statis)
* `DB_USER`: username untuk login (otentifkasi) ke database
* `DB_PASSWORD`" password dari username login database
* `DB_HOST`: server database yang digunakan (`localhost` untuk database MySQL, IP Address untuk remote database)
* `DB_TYPE`: tipe database yang akan digunakan (pilihan `mysql` untuk MySQL, `sqlsrv` untuk SQL Server)

## Database Class Wrapper
