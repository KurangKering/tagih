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

## Persiapan
- php-7.4.19
- mysql-5.7.33
- Composer

## Install

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
php spark serve
```

## Screenshot

### Role Bendahara
![image](https://user-images.githubusercontent.com/20513164/225204866-351be479-d99c-4ed6-9176-24f3d44912d3.png)
![image](https://user-images.githubusercontent.com/20513164/225204913-ab4f57a4-cb10-40fb-b93c-8530541a11cc.png)
![image](https://user-images.githubusercontent.com/20513164/225204954-a339b8e9-e59d-4e97-9e70-661e258fc434.png)
![image](https://user-images.githubusercontent.com/20513164/225204990-58961e35-9961-4c67-9d73-351df24cecb9.png)
![image](https://user-images.githubusercontent.com/20513164/225205021-98346ed4-3c37-426d-9a5b-ce977387e68d.png)
![image](https://user-images.githubusercontent.com/20513164/225205140-d8bb459c-fa74-4844-93cc-7fdc476675a7.png)
![image](https://user-images.githubusercontent.com/20513164/225205229-ca5ecb43-3d82-4d88-99eb-d7a3d7abeb84.png)
![image](https://user-images.githubusercontent.com/20513164/225205259-e141a1b7-2491-42f6-a7b8-ba1a2812d0f0.png)
![image](https://user-images.githubusercontent.com/20513164/225205295-879cbd5c-1ade-4b46-b510-d91681020511.png)
![image](https://user-images.githubusercontent.com/20513164/225205322-8443a662-38e9-411c-a130-628ecce082ea.png)


### Role Mahasiswa
![image](https://user-images.githubusercontent.com/20513164/225205386-05ec4e4c-b820-43b3-b743-be2663ba16e1.png)
![image](https://user-images.githubusercontent.com/20513164/225205408-c05318b6-0709-4e60-95ff-8948a13e32fc.png)
![image](https://user-images.githubusercontent.com/20513164/225205427-f093cbc6-ba2b-4da1-a04e-47e42034353e.png)

### Role Pimpinan
![image](https://user-images.githubusercontent.com/20513164/225205500-48038b8f-8793-4b96-bbd1-4f93708ddeaf.png)
![image](https://user-images.githubusercontent.com/20513164/225205550-3b070891-38b5-4f09-b9ae-922d240e9d68.png)

