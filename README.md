<div align="center">

# 📚 Sistem Informasi Manajemen Tugas Mahasiswa

**REST API** berbasis **Laravel 13** untuk manajemen tugas akademik mahasiswa dengan autentikasi berbasis token dan kontrol akses berbasis peran.

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![Sanctum](https://img.shields.io/badge/Laravel_Sanctum-4.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

</div>

---

## 📋 Daftar Isi

- [Tentang Proyek](#-tentang-proyek)
- [Fitur Utama](#-fitur-utama)
- [Tech Stack](#-tech-stack)
- [Struktur Database](#-struktur-database)
- [Instalasi & Setup](#-instalasi--setup)
- [Dokumentasi API](#-dokumentasi-api)
- [Format Respons](#-format-respons-standar)
- [Autentikasi](#-autentikasi)
- [Kontrol Akses (Role)](#-kontrol-akses-role)
- [Contoh Penggunaan](#-contoh-penggunaan)
- [Struktur Proyek](#-struktur-proyek)

---

## 🎯 Tentang Proyek

Proyek ini adalah implementasi **RESTful API** untuk sistem manajemen tugas mahasiswa yang dibangun sebagai bagian dari **Ujian Akhir Semester (UAS)** mata kuliah **Pemrograman Komputer Perangkat Lunak (PKPL)**.

API ini memungkinkan mahasiswa untuk mengelola tugas-tugas akademik mereka secara terstruktur, dengan fitur autentikasi, manajemen prioritas, filter tugas, dan pemantauan status penyelesaian.

> **NIM:** 1202307011

---

## ✨ Fitur Utama

| Fitur | Keterangan |
|-------|-----------|
| 🔐 **Autentikasi Sanctum** | Token-based authentication menggunakan Laravel Sanctum |
| 👥 **Manajemen Peran** | Dua peran: `admin` dan `user` dengan hak akses berbeda |
| ✅ **CRUD Tugas** | Buat, baca, ubah, dan hapus tugas secara penuh |
| 🔍 **Filter & Pencarian** | Filter tugas berdasarkan status, prioritas, dan judul |
| ✔️ **Penandaan Selesai** | Endpoint khusus untuk menandai tugas sebagai selesai |
| 🛡️ **Policy-based Authorization** | Otorisasi akses data menggunakan Laravel Policy |
| 📦 **Respons JSON Konsisten** | Seluruh endpoint mengembalikan format JSON yang seragam |
| 🚫 **Global Exception Handler** | Semua error (401, 403, 404, 422, 500) dikembalikan sebagai JSON |

---

## 🛠️ Tech Stack

- **Framework:** [Laravel 13](https://laravel.com)
- **Language:** PHP 8.3
- **Database:** MySQL 8.0
- **Autentikasi:** [Laravel Sanctum](https://laravel.com/docs/sanctum) v4.x
- **Authorization:** Laravel Policies & Gates
- **Server Development:** MAMP / PHP Built-in Server

---

## 🗄️ Struktur Database

### Tabel `users`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | bigint | Primary key |
| `name` | varchar(255) | Nama lengkap |
| `email` | varchar(255) | Email unik |
| `password` | varchar(255) | Password (bcrypt) |
| `role` | enum('admin','user') | Peran pengguna, default `user` |
| `email_verified_at` | timestamp | Nullable |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

### Tabel `tasks`
| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| `id` | bigint | Primary key |
| `user_id` | bigint | Foreign key → `users.id` |
| `title` | varchar(255) | Judul tugas (wajib) |
| `description` | text | Deskripsi (opsional) |
| `priority` | enum('low','medium','high') | Prioritas tugas |
| `status` | enum('pending','progress','completed') | Status tugas |
| `due_date` | date | Tenggat waktu (wajib) |
| `completed_at` | datetime | Nullable, diisi saat selesai |
| `created_at` | timestamp | — |
| `updated_at` | timestamp | — |

---

## 🚀 Instalasi & Setup

### Prasyarat
- PHP >= 8.3
- Composer
- MySQL 8.0
- MAMP / Laragon / XAMPP (opsional)

### Langkah Instalasi

**1. Clone repositori**
```bash
git clone https://github.com/<username>/UAS-PKPL-1202307011.git
cd UAS-PKPL-1202307011
```

**2. Install dependensi**
```bash
composer install
```

**3. Salin file environment**
```bash
cp .env.example .env
```

**4. Konfigurasi `.env`**
```env
APP_NAME="Sistem Informasi Manajemen Tugas Mahasiswa"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uas_pkpl_1202307011
DB_USERNAME=root
DB_PASSWORD=your_password
```

**5. Generate application key**
```bash
php artisan key:generate
```

**6. Buat database MySQL**
```sql
CREATE DATABASE uas_pkpl_1202307011 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

**7. Jalankan migration & seeder**
```bash
php artisan migrate:fresh --seed
```

> Seeder akan membuat 2 akun default:
> - **Admin:** `admin@example.com` / `password123`
> - **User:** `user@example.com` / `password123`

**8. Jalankan server**
```bash
php artisan serve
```

API tersedia di: **`http://127.0.0.1:8000/api`**

---

## 📡 Dokumentasi API

### Base URL
```
http://127.0.0.1:8000/api
```

### Header Wajib (Endpoint Terlindungi)
```
Authorization: Bearer {your_token}
Accept: application/json
Content-Type: application/json
```

---

### 🔓 A. Endpoint Publik

#### `POST /api/register`
Mendaftarkan pengguna baru.

**Request Body:**
```json
{
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

**Response `201`:**
```json
{
  "success": true,
  "message": "Registrasi berhasil.",
  "data": {
    "user": { "id": 3, "name": "Budi Santoso", "email": "budi@example.com", "role": "user" },
    "access_token": "1|xxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

---

#### `POST /api/login`
Login dan mendapatkan Bearer Token.

**Request Body:**
```json
{
  "email": "admin@example.com",
  "password": "password123"
}
```

**Response `200`:**
```json
{
  "success": true,
  "message": "Login berhasil.",
  "data": {
    "user": { "id": 1, "name": "Administrator", "role": "admin" },
    "access_token": "1|xxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

---

### 🔐 B. Endpoint Terlindungi (Butuh Bearer Token)

#### `GET /api/user`
Melihat profil pengguna yang sedang login.

---

#### `POST /api/logout`
Logout dan menghapus token aktif.

---

#### `GET /api/tasks`
Mengambil daftar tugas.
- **User:** hanya tugasnya sendiri
- **Admin:** seluruh tugas semua pengguna

**Query Parameters (opsional):**
| Parameter | Nilai | Contoh |
|-----------|-------|--------|
| `status` | `pending` / `progress` / `completed` | `?status=pending` |
| `priority` | `low` / `medium` / `high` | `?priority=high` |
| `search` | string bebas | `?search=laporan` |

**Contoh kombinasi:**
```
GET /api/tasks?status=progress&priority=high&search=skripsi
```

---

#### `POST /api/tasks`
Menambahkan tugas baru.

**Request Body:**
```json
{
  "title": "Membuat Laporan Akhir",
  "description": "Laporan PKL semester ini",
  "priority": "high",
  "status": "pending",
  "due_date": "2025-12-31"
}
```

---

#### `GET /api/tasks/{id}`
Melihat detail tugas berdasarkan ID.

---

#### `PUT /api/tasks/{id}` atau `PATCH /api/tasks/{id}`
Memperbarui data tugas. Semua field bersifat opsional.

**Request Body:**
```json
{
  "status": "progress",
  "priority": "medium"
}
```

---

#### `DELETE /api/tasks/{id}`
Menghapus tugas berdasarkan ID.

---

#### `PATCH /api/tasks/{id}/complete`
Menandai tugas sebagai **selesai**. Secara otomatis mengisi `status = completed` dan `completed_at = now()`.

---

### 👑 C. Endpoint Khusus Admin

> Membutuhkan akun dengan `role = admin`.

#### `GET /api/admin/users`
Melihat seluruh daftar pengguna beserta jumlah tugasnya.

#### `GET /api/admin/tasks`
Melihat seluruh tugas dari semua pengguna beserta data pemilik (relasi user).

---

## 📐 Format Respons Standar

Seluruh endpoint **wajib** mengembalikan format JSON konsisten berikut:

```jsonc
// ✅ Sukses (200 / 201)
{
  "success": true,
  "message": "Pesan sukses.",
  "data": { ... }
}

// ❌ Validasi Gagal (422)
{
  "success": false,
  "message": "Data yang diberikan tidak valid.",
  "errors": {
    "email": ["Email sudah terdaftar."],
    "password": ["Password minimal 8 karakter."]
  }
}

// ❌ Unauthenticated (401)
{
  "success": false,
  "message": "Unauthenticated."
}

// ❌ Forbidden (403)
{
  "success": false,
  "message": "Anda tidak memiliki hak akses."
}

// ❌ Not Found (404)
{
  "success": false,
  "message": "Data tugas tidak ditemukan."
}

// ❌ Server Error (500)
{
  "success": false,
  "message": "Pesan error internal."
}
```

---

## 🔑 Autentikasi

Proyek ini menggunakan **Laravel Sanctum** dengan mekanisme token berbasis API.

1. Lakukan `POST /api/login` → dapatkan `access_token`
2. Sertakan token pada setiap request terlindungi:
   ```
   Authorization: Bearer {access_token}
   ```
3. Lakukan `POST /api/logout` untuk menghapus token

---

## 👥 Kontrol Akses (Role)

| Aksi | Role `user` | Role `admin` |
|------|:-----------:|:------------:|
| Register & Login | ✅ | ✅ |
| Lihat profil sendiri | ✅ | ✅ |
| Buat tugas | ✅ | ✅ |
| Lihat daftar tugas | ✅ hanya miliknya | ✅ semua |
| Lihat detail tugas | ✅ hanya miliknya | ✅ semua |
| Edit tugas | ✅ hanya miliknya | ✅ semua |
| Hapus tugas | ✅ hanya miliknya | ✅ semua |
| Tandai selesai | ✅ hanya miliknya | ✅ semua |
| `GET /api/admin/users` | ❌ 403 | ✅ |
| `GET /api/admin/tasks` | ❌ 403 | ✅ |

---

## 💡 Contoh Penggunaan (cURL)

**Register:**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"name":"Budi","email":"budi@mail.com","password":"password123","password_confirmation":"password123"}'
```

**Login:**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password123"}'
```

**Buat Tugas:**
```bash
curl -X POST http://127.0.0.1:8000/api/tasks \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"title":"Kerjakan UAS","priority":"high","status":"pending","due_date":"2025-12-31"}'
```

**Filter Tugas:**
```bash
curl http://127.0.0.1:8000/api/tasks?status=pending&priority=high \
  -H "Authorization: Bearer {token}" \
  -H "Accept: application/json"
```

---

## 📁 Struktur Proyek

```
UAS-PKPL-1202307011/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── AuthController.php      # Register, Login, Profile, Logout
│   │   │       ├── TaskController.php      # CRUD Tugas + Complete
│   │   │       └── AdminController.php     # Admin: Users & Tasks
│   │   ├── Middleware/
│   │   │   └── AdminMiddleware.php         # Cek role admin
│   │   └── Requests/
│   │       ├── RegisterRequest.php         # Validasi registrasi
│   │       ├── LoginRequest.php            # Validasi login
│   │       ├── StoreTaskRequest.php        # Validasi buat tugas
│   │       └── UpdateTaskRequest.php       # Validasi update tugas
│   ├── Models/
│   │   ├── User.php                        # Model User + HasApiTokens
│   │   └── Task.php                        # Model Task
│   └── Policies/
│       └── TaskPolicy.php                  # Otorisasi akses tugas
├── bootstrap/
│   └── app.php                             # Global Exception Handler JSON
├── database/
│   ├── migrations/
│   │   ├── ..._create_users_table.php      # + kolom role
│   │   └── ..._create_tasks_table.php      # Tabel tasks
│   └── seeders/
│       ├── AdminSeeder.php                 # Seed akun admin & user
│       └── DatabaseSeeder.php
└── routes/
    └── api.php                             # Semua route API
```

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan akademik — **Ujian Akhir Semester PKPL**.

<div align="center">
  <p>Dibuat dengan ❤️ menggunakan <strong>Laravel 13</strong></p>
  <p>NIM: 1202307011</p>
</div>
