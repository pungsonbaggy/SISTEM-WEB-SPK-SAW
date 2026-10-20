-- =====================================================================
--  Setup basis data SPK Wisata Bahari Pesawaran
--  Dibuat sesuai Subbab 3.4.7.3 skripsi (PostgreSQL 16)
-- =====================================================================
--
--  CARA MENJALANKAN (sekali saja):
--
--     "C:\Program Files\PostgreSQL\16\bin\psql.exe" -U postgres -h 127.0.0.1 -f database\setup.sql
--
--  Kamu akan diminta password user "postgres" (password yang kamu buat
--  saat memasang PostgreSQL). Password itu hanya dipakai untuk membuat
--  user aplikasi, tidak disimpan di mana pun.
--
--  Script ini membuat:
--    - user   : spk_user   (password: spk_wisata_2026)
--    - database: spk_wisata
-- =====================================================================

-- Hapus dulu kalau sudah pernah dibuat (aman untuk dijalankan ulang).
DROP DATABASE IF EXISTS spk_wisata;
DROP ROLE IF EXISTS spk_user;

-- User khusus aplikasi. Jangan pakai superuser "postgres" untuk aplikasi.
CREATE ROLE spk_user LOGIN PASSWORD 'spk_wisata_2026';

-- Database milik user tersebut.
CREATE DATABASE spk_wisata OWNER spk_user ENCODING 'UTF8';

-- Beri hak penuh pada database tersebut.
GRANT ALL PRIVILEGES ON DATABASE spk_wisata TO spk_user;

-- PostgreSQL 15 ke atas mencabut hak CREATE pada schema public,
-- jadi perlu diberikan secara eksplisit agar migration bisa jalan.
\connect spk_wisata
GRANT ALL ON SCHEMA public TO spk_user;
ALTER SCHEMA public OWNER TO spk_user;

\echo ''
\echo 'SELESAI: database spk_wisata dan user spk_user sudah dibuat.'
\echo 'Langkah berikutnya: php artisan migrate --seed'
\echo ''
