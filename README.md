# SPK Wisata Bahari Pesawaran

Aplikasi web Sistem Pendukung Keputusan (SPK) pemilihan destinasi wisata bahari Kabupaten Pesawaran menggunakan metode **Simple Additive Weighting (SAW)** berbasis profil wisatawan.

- Framework: **Laravel 11** (PHP 8.3)
- Basis data: **PostgreSQL 16** (Database: `spk_saw_db`)
- Tampilan: **Bootstrap 5.3** + **Bootstrap Icons** + **Plus Jakarta Sans**
- Pengujian: **PHPUnit** (`php artisan test`) + **Verifikasi SAW Otomatis** (`php artisan saw:verify`)

---

## 1. Spesifikasi Sistem

| Komponen | Spesifikasi / Konfigurasi | Keterangan |
|---|---|---|
| PHP | 8.3 (Laragon / System) | Ekstensi: `pdo_pgsql`, `pgsql`, `zip` |
| Basis Data | PostgreSQL 16 | Host: `127.0.0.1:5432`, DB: `spk_saw_db` |
| Akun DB | User: `postgres` | Password: disesuaikan di file `.env` |
| Jumlah Destinasi | 14 Destinasi Riil (A1–A14) | Sesuai data survei & pemetaan Pesawaran |
| Bobot Kriteria | Survei 60 Responden | Uji reliabilitas Cronbach's Alpha = 0,751 |

---

## 2. Cara Menjalankan Aplikasi

### 2.1 Konfigurasi Database (.env)
Pastikan berkas `.env` sudah sesuai:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=spk_saw_db
DB_USERNAME=postgres
DB_PASSWORD=*****
```

### 2.2 Migrasi dan Pengisian Data (Seeding)
Jalankan migrasi tabel dan seeding 14 destinasi riil serta bobot 60 responden:
```powershell
php artisan migrate:fresh --seed
```

### 2.3 Menjalankan Server Lokal
```powershell
php artisan serve
```
Akses di peramban (browser): **http://127.0.0.1:8000**

- **Akun Administrator**: `admin@spk.test`
- **Kata Sandi (Password)**: `admin12345`

---

## 3. Perintah Utama (Testing & Verifikasi)

| Perintah | Deskripsi |
|---|---|
| `php artisan serve` | Menjalankan web server lokal Laravel |
| `php artisan test` | Menjalankan 13 unit & feature automated test (100% Pass) |
| `php artisan saw:verify` | Memverifikasi 41 titik perhitungan SAW terhadap data acuan skripsi |
| `php artisan migrate:fresh --seed` | Mengatur ulang seluruh tabel dan mengisi data riil dari nol |

---

## 4. Struktur Folder Penting

```
Aplikasi_SPK_Wisata_Bahari/
├── app/
│   ├── Console/Commands/VerifikasiSaw.php   # Perintah artisan saw:verify
│   ├── Http/
│   │   ├── Controllers/                     # Controller publik & wisatawan
│   │   │   ├── BerandaController.php
│   │   │   ├── RekomendasiController.php
│   │   │   ├── DestinasiController.php
│   │   │   └── Admin/                       # Controller panel administrator
│   │   └── Requests/ProfilRequest.php       # Validasi form profil wisatawan
│   ├── Models/                              # Model Eloquent (Destinasi, Bobot, Aturan, dll.)
│   └── Services/SawService.php              # Inti algoritma Simple Additive Weighting (SAW)
├── database/
│   ├── migrations/                          # Definisi skema tabel PostgreSQL
│   └── seeders/                             # Seeder 14 destinasi riil & bobot 60 responden
├── resources/views/                         # Tampilan antarmuka Blade & Bootstrap 5
│   ├── layouts/app.blade.php                # Layout utama bernuansa bahari modern
│   ├── beranda.blade.php                    # Form pemilihan profil dengan kartu interaktif
│   ├── rekomendasi.blade.php                # Hasil peringkat rekomendasi & visualisasi Vi
│   ├── destinasi/show.blade.php             # Rincian destinasi, fasilitas & matriks SAW
│   └── admin/                               # Antarmuka panel admin (Destinasi, Bobot, Aturan, Analisis)
├── routes/web.php                           # Definisi rute URL aplikasi
└── tests/
    ├── Feature/AlurRekomendasiTest.php      # Pengujian alur rekomendasi & autentikasi
    └── golden_tests.json                    # Matriks acuan perhitungan numerik SAW
```

---

## 5. Alur Algoritma SAW

1. **Input Profil Wisatawan**: Minat (4 pilihan), Anggaran (3 level), Jarak (3 level).
2. **Kombinasi Bobot**: $W_{akhir} = \frac{W_{minat} + W_{anggaran} + W_{jarak}}{3}$, dinormalisasi hingga $\sum W_j = 1,0000$.
3. **Penyaringan Kelayakan**: Memeriksa batas ambang minimal kriteria aktivitas ($C_6 \ge 2$).
4. **Normalisasi Matriks**:
   - Biaya & Jarak (*Cost*, C1 & C2): $r_{ij} = \frac{\min(x_j)}{x_{ij}}$
   - Fasilitas, Keindahan, Kebersihan, Aktivitas (*Benefit*, C3 s/d C6): $r_{ij} = \frac{x_{ij}}{\max(x_j)}$
5. **Perangkingan Nilai Preferensi**: $V_i = \sum_{j=1}^{6} (W_j \times r_{ij})$ diurutkan dari yang tertinggi untuk menghasilkan Top 5 destinasi rekomendasi.
