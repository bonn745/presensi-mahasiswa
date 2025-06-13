USE db_presensi;

-- Backup data yang ada
CREATE TEMPORARY TABLE temp_ketidakhadiran 
SELECT * FROM ketidakhadiran;

-- Hapus kolom lama dan tambah kolom baru
ALTER TABLE ketidakhadiran 
    DROP COLUMN tanggal,
    ADD COLUMN tanggal_mulai DATE NULL AFTER keterangan,
    ADD COLUMN tanggal_selesai DATE NULL AFTER tanggal_mulai;

-- Update data yang ada dengan mengisi tanggal_mulai dan tanggal_selesai
-- dengan nilai yang sama dari tanggal lama
UPDATE ketidakhadiran k
JOIN temp_ketidakhadiran t ON k.id = t.id
SET k.tanggal_mulai = t.tanggal,
    k.tanggal_selesai = t.tanggal;

-- Set kolom NOT NULL setelah data dimigrasi
ALTER TABLE ketidakhadiran 
    MODIFY COLUMN tanggal_mulai DATE NOT NULL,
    MODIFY COLUMN tanggal_selesai DATE NOT NULL; 