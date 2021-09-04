<h1 align="center">Selamat datang di Sistem Tagihan Mahsiswa</h1>

## Apa itu Sistem Tagihan Mahasiswa?

Sistem tagihan mahasiswa adalah sistem yang mengelola tagihan mahasiswa. **Sistem ini dibuat dalam rangka tes Programmer di Garuda Cyber Indonesia**

## Fitur apa saja yang tersedia?

- Autentikasi Bendahara, Pimpinan, Mahasiswa
- Data Mahasiswa 
- Kelola Periode Semester
- Data Tagihan
- Menambah Data Tagihan
- Menghapus Data Tagihan
- Membayar Data Tagihan
- Melihat Grafik Tagihan Lunas dan Belum
- Dan lain-lain


## Akun default

**Pimpinan Default Account**

- username: pimpinan
- Password: asd

---
**Bendahara Default Account**

- username: bendahara
- Password: asd

---

## Install

- **Persiapan**

	- php-7.4.19
	- mysql-5.7.33



1. **Clone Repository**

```bash
git clone https://github.com/KurangKering/tagih.git
cd tagih
composer install
```

2. **Buka `.env` lalu ubah baris berikut sesuai dengan databasemu yang ingin dipakai**

```bash
database.default.hostname = localhost
database.default.database = tagihan
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.DBPrefix =
```

	jangan lupa create database nya terlebih dahulu.

3. **Instalasi sistem**

```bash
php spark migrate
php spark db:seed RunMeSeeder
```

apabila berhasil, anda akan melihat data user yang dapat digunakan untuk login.
untuk melihat seluruh data user menggunakan mysql manager seperti phpmyadmin.

4. **Jalankan Sistem**

```bash
php artisan serve
```