-- Script SQL untuk menambahkan kolom slug dan generate slug untuk data existing

-- Step 1: Tambah kolom slug (nullable dulu)
ALTER TABLE plants ADD COLUMN slug VARCHAR(255) NULL AFTER name;

-- Step 2: Generate slug untuk setiap tanaman
-- Ganti spasi dengan dash dan lowercase
UPDATE plants SET slug = LOWER(REPLACE(name, ' ', '-'));

-- Step 3: Buat kolom slug jadi unique dan not null
ALTER TABLE plants MODIFY COLUMN slug VARCHAR(255) NOT NULL UNIQUE;

-- Verifikasi
SELECT id, name, slug FROM plants;
