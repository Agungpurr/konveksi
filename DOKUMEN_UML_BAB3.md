# DOKUMEN PERANCANGAN SISTEM (BAB III SKRIPSI)

## SISTEM PENDUKUNG KEPUTUSAN PENENTUAN BAHAN BAJU PADA KONVEKSI MAS ARI KOTA BEKASI MENGGUNAKAN METODE SIMPLE ADDITIVE WEIGHTING (SAW)

Dokumen ini disusun untuk melengkapi **Bab III (Perancangan Sistem)** pada Buku Laporan Tugas Akhir / Skripsi di Program Studi Teknik Informatika, Fakultas Teknik dan Ilmu Komputer, Universitas Indraprasta PGRI (UNINDRA).

---

### Informasi Tugas Akhir
- **Nama Penyusun**: Agung Adhyaksa
- **NPM**: 202243500428
- **Program Studi**: Teknik Informatika (UNINDRA, 2026)
- **Studi Kasus**: Konveksi Mas Ari, Jl. Gondangdia Baru No.26, Pondokgede, Kota Bekasi

---

## 1. Use Case Diagram

*Use Case Diagram* mendeskripsikan interaksi antara aktor (pengguna) dengan fungsionalitas yang disediakan oleh sistem pendukung keputusan penentuan bahan baju.

### 1.1 Diagram Use Case (Mermaid)

```mermaid
flowchart LR
    Admin([Administrator / Pemilik Konveksi])

    subgraph SPK_Bahan_Baju ["Sistem Pendukung Keputusan SAW Konveksi Mas Ari"]
        UC1([1. Melakukan Login])
        UC2([2. Mengelola Data 8 Kriteria & Bobot])
        UC3([3. Mengelola 100 Data Alternatif Bahan Baju])
        UC4([4. Menginput / Mengubah Penilaian Matriks X])
        UC5([5. Menjalankan Proses Perhitungan SAW])
        UC6([6. Melihat Hasil Perangkingan & Kuota Kelayakan])
        UC7([7. Melakukan Simulasi Konsultasi Pelanggan])
        UC8([8. Mengunduh Rekapitulasi Excel / CSV])
        UC9([9. Mencetak Laporan Hasil Rekomendasi])
        UC10([10. Melakukan Logout])
    end

    Admin --> UC1
    Admin --> UC2
    Admin --> UC3
    Admin --> UC4
    Admin --> UC5
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC9
    Admin --> UC10
```

### 1.2 Deskripsi Aktor & Use Case
1. **Aktor**: Administrator / Pemilik Usaha (Mas Ari & Pengelola Konveksi).
2. **Deskripsi Kasus Penggunaan**:
   - **Login**: Memvalidasi kredensial pengguna menggunakan enkripsi kata sandi BCRYPT.
   - **Kelola Kriteria**: Mengatur 8 kriteria ($C_1 - C_8$), sifat atribut (*Benefit/Cost*), dan nilai bobot yang dinormalisasi otomatis menjadi $\sum w'_j = 1$.
   - **Kelola Bahan Baju**: Melakukan penambahan, perubahan, pencarian, dan penghapusan terhadap 100 alternatif bahan baku kain.
   - **Penilaian Matriks**: Menginput nilai kinerja setiap bahan kain terhadap 8 kriteria menggunakan skala kripsin 1 s.d 5.
   - **Proses Perhitungan SAW**: Menampilkan tahapan matematis secara transparan (Matriks $X$, Nilai Max/Min, Matriks $R$, dan Nilai $V$).
   - **Hasil & Kuota**: Menampilkan urutan ranking 1 s.d 100 serta memfilter kuota kebutuhan produksi (Top 10 berstatus *Terpilih/Layak*, sisanya *Tidak Terpilih*).
   - **Simulasi Konsultasi**: Menyesuaikan bobot kriteria secara fleksibel untuk konsultasi pesanan khusus konsumen.
   - **Cetak Laporan**: Mencetak lembar rekomendasi resmi ber-Kop Surat Konveksi Mas Ari lengkap dengan tanda tangan pengesahan.

---

## 2. Activity Diagram (Alur Perhitungan SAW)

*Activity Diagram* menggambarkan aliran aktivitas sistem dari awal input data hingga menghasilkan rekomendasi bahan baju terbaik.

```mermaid
stateDiagram-v2
    [*] --> Login
    Login --> TampilDashboard: Login Berhasil
    TampilDashboard --> PilihMenuSPK
    
    state "Proses Algoritma SAW" as SAW {
        PilihMenuSPK --> AmbilData: Akses Menu Perhitungan
        AmbilData --> BentukMatriksX: Muat 100 Bahan & 8 Kriteria
        BentukMatriksX --> CariEkstremum: Hitung Max & Min Kolom
        CariEkstremum --> NormalisasiR: Benefit (x/Max) & Cost (Min/x)
        NormalisasiR --> HitungV: V_i = ∑ (w'_j * r_ij)
        HitungV --> UrutkanRanking: Sort Descending V_i
        UrutkanRanking --> TerapkanKuota: Filter Kuota (Top 10)
    }

    TerapkanKuota --> TampilHasil
    TampilHasil --> OpsiLaporan
    
    state "Pilihan Output" as Output {
        OpsiLaporan --> CetakKopSurat: Cetak Laporan
        OpsiLaporan --> ExportCSV: Unduh File Excel
        OpsiLaporan --> SimpanRiwayat: Simpan ke Database
    }

    CetakKopSurat --> [*]
    ExportCSV --> [*]
    SimpanRiwayat --> [*]
```

---

## 3. Sequence Diagram (Proses Perhitungan & Perangkingan SAW)

*Sequence Diagram* menggambarkan interaksi antar objek dalam sistem berdasarkan urutan waktu.

```mermaid
sequenceDiagram
    autonumber
    actor Admin as Administrator
    participant View as SpkView (Browser)
    participant Ctrl as SpkController
    participant Engine as SAWEngine
    participant DB as MySQL Database

    Admin->>View: Buka Menu Hasil & Kuota (?page=spk&action=hasil)
    View->>Ctrl: Request hasil()
    Ctrl->>Engine: new SAWEngine(kuota = 10)
    Ctrl->>Engine: calculate()
    
    Engine->>DB: Query SELECT kriteria (8 Kriteria)
    DB-->>Engine: Data Kriteria & Bobot
    Engine->>Engine: Normalisasi Bobot (w'_j = w_j / ∑w_j)

    Engine->>DB: Query SELECT bahan (100 Bahan Aktif)
    DB-->>Engine: Data 100 Bahan Baju

    Engine->>DB: Query SELECT nilai_alternatif (800 Data)
    DB-->>Engine: Nilai Matriks X

    Engine->>Engine: Hitung Extremes (Max & Min Kolom)
    Engine->>Engine: Hitung Matriks Normalisasi R (Benefit/Cost)
    Engine->>Engine: Hitung Nilai Preferensi V_i = ∑ (w'_j * r_ij)
    Engine->>Engine: Urutkan Nilai V_i dari Terbesar ke Terkecil
    Engine->>Engine: Filter Kuota (Rank ≤ 10: Terpilih, Rank > 10: Tidak Terpilih)

    Engine-->>Ctrl: Return Hasil Lengkap (X, Extremes, R, V, Ranking, Kuota)
    Ctrl-->>View: Render views/spk/hasil.php
    View-->>Admin: Tampilkan Peringkat 1 s.d 100, Badge Top 1, & Grafik Chart.js
```

---

## 4. Class Diagram

*Class Diagram* menggambarkan struktur berorientasi objek sistem, atribut, metode, serta keterhubungannya.

```mermaid
classDiagram
    class Database {
        -PDO instance$
        -string errorMessage$
        +getConnection() PDO$
        +getErrorMessage() string$
    }

    class Auth {
        +init() void$
        +check() bool$
        +user() array$
        +login(array user) void$
        +logout() void$
        +requireLogin() void$
        +setFlash(string type, string msg) void$
        +getFlash(string type) string$
    }

    class SAWEngine {
        -PDO db
        -int quota
        +__construct(int quota)
        +calculate() array
    }

    class SpkController {
        +proses() void
        +hasil() void
        +simpan() void
        +simulasi() void
    }

    class BahanController {
        +index() void
        +create() void
        +edit() void
        +delete() void
    }

    class KriteriaController {
        +index() void
        +updateBobot() void
    }

    class LaporanController {
        +cetak() void
        +riwayat() void
        +exportCsv() void
        +hapusRiwayat() void
    }

    SpkController ..> SAWEngine : Menggunakan
    SpkController ..> Database : Query
    BahanController ..> Database : Query
    KriteriaController ..> Database : Query
    LaporanController ..> SAWEngine : Menggunakan
    LaporanController ..> Database : Query
    SAWEngine ..> Database : Query
```

---

## 5. Entity Relationship Diagram (ERD Basis Data)

Struktur relasi tabel dalam basis data `db_spk_konveksi_masari`:

```mermaid
erDiagram
    USERS ||--o{ RIWAYAT_HASIL : "menyimpan"
    KRITERIA ||--o{ SUB_KRITERIA : "memiliki skala"
    KRITERIA ||--o{ NILAI_ALTERNATIF : "dievaluasi pada"
    BAHAN ||--o{ NILAI_ALTERNATIF : "memiliki nilai"
    BAHAN ||--o{ RIWAYAT_HASIL : "rekomendasi teratas"

    USERS {
        int id_user PK
        string nama_lengkap
        string username
        string password
        enum role
        string telepon
    }

    KRITERIA {
        int id_kriteria PK
        string kode_kriteria "C1 s.d C8"
        string nama_kriteria
        enum jenis "benefit, cost"
        decimal bobot
        decimal bobot_normalisasi
    }

    SUB_KRITERIA {
        int id_sub PK
        int id_kriteria FK
        string nama_sub "Skala Kripsin"
        decimal nilai_skala "1 s.d 5"
    }

    BAHAN {
        int id_bahan PK
        string kode_bahan "A1 s.d A100"
        string nama_bahan
        decimal harga_referensi
        enum status "Aktif, Nonaktif"
    }

    NILAI_ALTERNATIF {
        int id_nilai PK
        int id_bahan FK
        int id_kriteria FK
        decimal nilai "Nilai x_ij"
    }

    RIWAYAT_HASIL {
        int id_riwayat PK
        int id_user FK
        int id_bahan_terbaik FK
        int kuota_terpilih "Default: 10"
        decimal skor_terbaik
        datetime tanggal_hitung
        longtext data_json
    }
```

---

## 6. Ringkasan Bab III untuk Naskah Skripsi
Diagram-diagram UML di atas dapat disalin atau diekspor ke format gambar (.png) dan disisipkan langsung ke dalam **Bab III (Metodologi Penelitian dan Perancangan Sistem)** skripsi Anda untuk memenuhi standar penyusunan Tugas Akhir di Program Studi Teknik Informatika Universitas Indraprasta PGRI (UNINDRA).

