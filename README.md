| Nama                     | Kelas    | NIM       | Mata Kuliah           | Ujian |
|---------------------------|----------|------------|------------------------|--------|
| Friska Pebriana Lestari   | I. 24. 1A | 312410160  | Pemrograman Web 2      | UAS WEB 2    |


## Judul Proyek:
## E-Library (Sistem Informasi Perpustakaan Digital Berbasis Single Page Application)

Deskripsi Singkat:
- E-Library merupakan aplikasi perpustakaan digital berbasis web yang dibangun menggunakan VueJS sebagai frontend Single Page Application (SPA), CodeIgniter 4 sebagai backend REST API, MySQL sebagai basis data, serta TailwindCSS untuk desain antarmuka pengguna. Sistem ini memungkinkan administrator untuk mengelola data buku melalui fitur tambah, ubah, hapus, dan melihat data buku, serta mengelola data peminjaman dan pengembalian buku. Pengunjung dapat melihat daftar buku yang tersedia dan melakukan pengajuan peminjaman buku melalui halaman utama. Aplikasi ini bertujuan untuk mempermudah proses pengelolaan perpustakaan secara digital, meningkatkan efisiensi pencatatan data, serta memberikan kemudahan akses informasi bagi pengguna.

## Screenshot skema relasi tabel database 
- berikut adalah screenshot dari skema relasi tabel database yang telah dibuat

  <br> <img width="898" height="614" alt="skema" src="https://github.com/user-attachments/assets/7218bed9-8ba6-41fb-8d4d-3b9bd0e9fc39" />

- Penjelasan
<br> Skema relasi database E-Library terdiri dari empat tabel utama, yaitu users, kategori, buku, dan peminjaman. Tabel kategori memiliki relasi one-to-many (1) dengan tabel buku melalui atribut kategori_id, yang berarti satu kategori dapat memiliki banyak data buku. Selanjutnya, tabel buku memiliki relasi one-to-many (1) dengan tabel peminjaman melalui atribut buku_id, yang berarti satu buku dapat dipinjam oleh banyak pengguna pada waktu yang berbeda. Tabel users digunakan sebagai penyimpanan data administrator untuk proses autentikasi dan tidak memiliki relasi langsung dengan tabel lainnya.
  
## Screenshot uji coba tembak API gagal (Error 401) melalui POSTMAN
- berikut adalah screenshot dari uji coba tembak API Error 401

  <br>  <img width="1913" height="1128" alt="error401" src="https://github.com/user-attachments/assets/ba331a6b-67a5-40a2-84a1-db301f3c2e67" />

- Pengujian Endpoint POST /buku Menggunakan Postman
  <br> Pada pengujian API menggunakan Postman, dilakukan request dengan metode **POST** ke endpoint:

  http://localhost:8080/buku
  
  Request body dikirim menggunakan format **x-www-form-urlencoded** dengan parameter:
  
  * judul : Test
  * penulis : Test
  
  Namun, sistem mengembalikan response:
  
  ```json
  {
    "status": 401,
    "message": "Unauthorized"
  }
  ```
  
  Status **401 Unauthorized** menunjukkan bahwa pengguna tidak memiliki hak akses untuk menjalankan endpoint tersebut karena belum melakukan autentikasi atau tidak mengirimkan token yang valid.
  
  Hal ini terjadi karena pada file `Routes.php`, endpoint POST `/buku` diproteksi menggunakan filter autentikasi (`auth`):
  
  ```php
  $routes->post('/buku', 'Buku::create', ['filter' => 'auth']);
  ```
  
  Artinya, setiap request untuk menambahkan data buku harus menyertakan **JWT Token** pada header Authorization, misalnya:
  
  ```text
  Authorization: Bearer <token>
  ```
  
  Apabila token tidak dikirimkan atau token tidak valid, maka server secara otomatis akan menolak request dan mengembalikan response **401 Unauthorized** sebagai mekanisme keamanan untuk mencegah pengguna yang tidak terautentikasi mengakses atau memodifikasi data buku.

## Screenshot antarmuka aplikasi
- Halaman Login
  
  <br> <img width="1920" height="1128" alt="login" src="https://github.com/user-attachments/assets/240142f7-ea56-43d4-bf60-842f5c6d0110" />

- Halaman Dashboard Admin

  <br> <img width="1920" height="1128" alt="dashboard_admin" src="https://github.com/user-attachments/assets/bac9990d-b3da-44f1-9530-2c68911d729a" />

- Halaman Kelola Buku (tempat aktivitas CRUD)

  <br> <img width="1920" height="1128" alt="kelola_buku" src="https://github.com/user-attachments/assets/d0267181-f263-4afe-90e2-072ac0061167" />

- Halaman Peminjaman (Showing daftar peminjaman buku)

  <br> <img width="1920" height="1128" alt="peminjaman" src="https://github.com/user-attachments/assets/285dddb5-43f6-43e5-87ec-6c621423e5ce" />

## Petunjuk instalasi singkat cara menjalankan proyek backend dan frontend di komputer lokal.
<br> **Backend (CodeIgniter 4)**
1. Buka folder backend-api.
2. Install dependency:
   
   ```
   composer install
   ```
3. Jalankan server:

   ```
   php spark serve
   ```
4. Backend dapat diakses melalui:

   ```
   http://localhost:8080
   ```
<br> **Frontend (VueJS + Vite)**

1. Buka folder frontend-spa.
2. Install dependency:

   ```
   npm install
   ```
3. Jalankan aplikasi:

   ```
   npm run dev
   ```
4. Frontend dapat diakses melalui alamat yang diberikan oleh Vite, misalnya:

   ```
   http://localhost:5173
   ```
   Apabila berhasil dijalankan, terminal akan menampilkan alamat lokal Vite dan aplikasi E-Library dapat diakses melalui browser pada alamat tersebut.

So far skema sistem saya pada project ini adalah sebagai berikut:
```
Backend (CI4)
│
├── /                → Welcome to CodeIgniter 4
├── /login           → API Login
├── /buku            → API Buku
├── /kategori        → API Kategori
└── /peminjaman      → API Peminjaman

Frontend (Vue + Vite)
└── http://localhost:5173
```

**Catatan**
<br> Setelah menjalankan perintah php spark serve, backend akan aktif pada alamat http://localhost:8080. Apabila halaman default CodeIgniter 4 belum diubah, maka browser akan menampilkan halaman "Welcome to CodeIgniter 4" sebagai tanda bahwa server backend berhasil dijalankan.
