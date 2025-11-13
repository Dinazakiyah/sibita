# 📋 Dokumentasi Sistem Bimbingan TA UNEJ

## 🎯 Fitur Utama berdasarkan Role

Sistem ini mendukung 3 role dengan kemampuan masing-masing:

---

## 👨‍💼 1. Admin Prodi (Admin)

### Kemampuan:

#### 1.1 Mengelola Data Mahasiswa
- **Route**: `/admin/mahasiswa`
- **Controller**: `AdminController@indexMahasiswa()`
- **Views**: `admin/mahasiswa/index.blade.php`
- **Fitur**:
  - Melihat daftar semua mahasiswa
  - Tambah mahasiswa baru
  - Edit data mahasiswa
  - Hapus mahasiswa
  - Assign dosen pembimbing (pembimbing 1 & 2)
  - Lihat status mahasiswa (layak sempro, layak sidang)

#### 1.2 Mengelola Data Dosen
- **Route**: `/admin/dosen`
- **Controller**: `AdminController@indexDosen()`
- **Views**: `admin/dosen/index.blade.php`
- **Fitur**:
  - Melihat daftar semua dosen
  - Tambah dosen baru
  - Edit data dosen
  - Hapus dosen
  - Lihat jumlah mahasiswa bimbingan

#### 1.3 Mengelola Jadwal & Periode Bimbingan
- **Route**: `/admin/periods`
- **Controller**: `AdminController@periods()` dan `AdminController@storePeriod()`
- **Views**: `admin/periods/index.blade.php`, `admin/periods/create.blade.php`
- **Fitur**:
  - Buat periode bimbingan baru (Ganjil, Genap, Semester)
  - Set tanggal mulai dan berakhir
  - Set deadline registrasi
  - Set jadwal seminar
  - Aktivasi/deaktifkan periode
  - Lihat riwayat periode

**Database Fields - schedule_periods table:**
```php
- id (Primary Key)
- period_name (string) - Ganjil 2024/2025, Genap 2024/2025, dll
- start_date (date)
- end_date (date)
- registration_deadline (date)
- seminar_start_date (nullable date)
- seminar_end_date (nullable date)
- is_active (boolean) - Hanya satu periode yang aktif
- description (text nullable)
- timestamps
```

#### 1.4 Melihat Laporan Aktivitas Bimbingan
- **Route**: `/admin/reports`
- **Controller**: `AdminController@reports()` dan `AdminController@laporan()`
- **Views**: `admin/reports.blade.php`, `admin/laporan.blade.php`
- **Fitur**:
  - Dashboard dengan statistik keseluruhan:
    - Total mahasiswa
    - Total dosen
    - Jumlah mahasiswa layak sempro
    - Jumlah mahasiswa layak sidang
  - Laporan submission files:
    - Pending review (status: submitted)
    - Sedang direview (status: reviewed)
    - Approved submissions
    - Rejected submissions
  - Laporan bimbingan per dosen:
    - Jumlah mahasiswa bimbingan
    - Jumlah sesi bimbingan
    - Progress bimbingan
  - Export laporan (PDF/Excel - opsional)

---

## 👨‍🏫 2. Dosen Pembimbing (Dosen)

### Kemampuan:

#### 2.1 Melihat Daftar Mahasiswa Bimbingan
- **Route**: `/dosen/mahasiswa`
- **Controller**: `DosenController@mahasiswa()`
- **Views**: `dosen/mahasiswa/index.blade.php`
- **Fitur**:
  - Lihat daftar mahasiswa yang dibimbing
  - Filter berdasarkan status (pending, revisi, approved)
  - Klik untuk melihat detail dan riwayat bimbingan
  - Total mahasiswa yang dibimbing: `auth()->user()->mahasiswaBimbingan()->count()`

#### 2.2 Memberikan Komentar dan Status Revisi
- **Route**: `/dosen/submissions/{submission}/review`
- **Controller**: `DosenController@reviewSubmission()` dan `DosenController@addComment()`
- **Views**: `dosen/submissions/review.blade.php`
- **Fitur**:
  - Lihat file submission yang diupload mahasiswa
  - Download file untuk review
  - Tambah komentar dengan opsi:
    - Komentar text (max 5000 karakter)
    - Status: `pending`, `approved`, `revision_needed`
    - Priority: `0` (Normal), `1` (Medium), `2` (Urgent)
    - Pin komentar (tampil di atas)
  - Lihat semua komentar sebelumnya

**Database Fields - comments table:**
```php
- id (Primary Key)
- submission_id (Foreign Key)
- dosen_id (Foreign Key)
- comment (text)
- status (string) - pending, approved, revision_needed
- priority (integer) - 0, 1, 2
- is_pinned (boolean) - Untuk komentar penting
- timestamps
```

#### 2.3 Menyetujui atau Menolak Hasil Revisi
- **Route**: `/dosen/submissions/{submission}/approve`
- **Route**: `/dosen/submissions/{submission}/reject`
- **Controller**: `DosenController@approveSubmission()` dan `DosenController@rejectSubmission()`
- **Fitur**:
  - Approve submission:
    - Status berubah menjadi `approved`
    - Set `approved_at` timestamp
    - Update `bimbingan.status` = `approved` jika semua submission approved
  - Reject submission:
    - Status berubah menjadi `rejected`
    - Simpan alasan penolakan di `dosen_notes`
    - Mahasiswa bisa upload ulang

**Submission Status Flow:**
```
submitted → reviewed → (approved / rejected)
                    ↓
                revision_needed → resubmitted → approved
```

#### 2.4 Melihat Riwayat Bimbingan Setiap Mahasiswa
- **Route**: `/dosen/mahasiswa/{mahasiswa}`
- **Controller**: `DosenController@showMahasiswa()`
- **Views**: `dosen/mahasiswa/show.blade.php`
- **Fitur**:
  - Timeline bimbingan dengan semua submission
  - Riwayat komentar dan feedback
  - Progress perubahan status
  - Export riwayat (opsional)

---

## 👨‍🎓 3. Mahasiswa (Mahasiswa)

### Kemampuan:

#### 3.1 Mengunggah File Revisi atau Draft Laporan
- **Route**: `/mahasiswa/bimbingan/{bimbingan}/upload`
- **Controller**: `MahasiswaController@storeUpload()`
- **Views**: `mahasiswa/uploads/create.blade.php`
- **Fitur**:
  - Upload file format: PDF, DOC, DOCX
  - Maksimal size: 10 MB
  - Jenis file: Draft, Revision, Final
  - Optional: Deskripsi file
  - Validasi: File tidak boleh kosong, format harus sesuai
  - Automatic timestamp: `submitted_at` = now()

**Upload Form Validation:**
```php
- file: required|file|mimes:pdf,doc,docx|max:10240
- file_type: required|in:draft,revision,final
- description: nullable|string|max:1000
```

**Database Fields - submission_files table:**
```php
- id (Primary Key)
- bimbingan_id (Foreign Key)
- mahasiswa_id (Foreign Key)
- dosen_id (Foreign Key nullable) - Dosen yang review
- file_name (string)
- file_path (string) - Path di storage
- file_type (string) - draft, revision, final
- file_size (integer)
- description (text nullable)
- status (string) - submitted, reviewed, approved, rejected
- dosen_notes (text nullable) - Catatan penolakan
- submitted_at (datetime)
- reviewed_at (datetime)
- approved_at (datetime)
- timestamps
```

#### 3.2 Melihat Komentar dan Status dari Dosen
- **Route**: `/mahasiswa/submissions/{submission}`
- **Controller**: `MahasiswaController@showSubmission()`
- **Views**: `mahasiswa/submissions/show.blade.php`
- **Fitur**:
  - Lihat semua komentar dari dosen dengan urutan:
    - Pinned comments di atas
    - Terurut dari terbaru
  - Lihat priority level komentar
  - Lihat timestamp kapan komentar ditambahkan
  - Lihat dosen yang memberikan komentar
  - Status submission:
    - 🔵 `submitted` - Menunggu review
    - 🟡 `reviewed` - Sedang direview
    - ✅ `approved` - Disetujui
    - ❌ `rejected` - Ditolak, bisa upload ulang

#### 3.3 Melihat Progres Keseluruhan Bimbingan
- **Route**: `/mahasiswa/progress`
- **Controller**: `MahasiswaController@progress()`
- **Views**: `mahasiswa/progress.blade.php`
- **Fitur**:
  - Progress bar untuk setiap bimbingan:
    - Jumlah submission total
    - Jumlah submission yang approved
    - Persentase progress: (approved / total) × 100%
  - Timeline lengkap:
    - Tanggal upload
    - Tanggal direview
    - Tanggal disetujui
  - Status keseluruhan:
    - Pending (ada submission yang belum diapprove)
    - Approved (semua submission disetujui)
    - Revision (ada yang ditolak, perlu upload ulang)

#### 3.4 Mengunduh Riwayat Bimbingan sebagai Arsip
- **Route**: `/mahasiswa/bimbingan/{bimbingan}/archive/download`
- **Controller**: `MahasiswaController@downloadArchive()`
- **Fitur**:
  - Buat ZIP file berisi:
    - Semua file submission (original name preserved)
    - README.txt dengan metadata:
      - Judul bimbingan
      - Nama dosen
      - Status akhir
      - Tanggal setiap submission
  - Automatic download ke browser
  - Nama file: `bimbingan_{id}_{timestamp}.zip`

**Contoh struktur ZIP:**
```
bimbingan_1_2025-11-13-143522.zip
├── proposal-draft.pdf
├── revisi-1.docx
├── final-laporan.pdf
└── README.txt
```

---

## 📊 Database Schema

### Relations:
```
User (1) ---< (many) SubmissionFile (many) >--- (1) Bimbingan
   |                      |
   |                      +--< (many) Comment
   |
   +---< (many) Comment

User (Mahasiswa) ---< (many) Bimbingan >--- (1) User (Dosen)

User (1) ---< (many) SchedulePeriod (optional relation through Bimbingan)
```

### Eloquent Relations:
```php
// User Model
$user->submissionFiles()      // Files uploaded by mahasiswa
$user->comments()             // Comments made by dosen
$user->reviewedSubmissions()  // Submissions reviewed by dosen
$user->bimbinganAsMahasiswa() // Existing relation
$user->bimbinganAsDosen()     // Existing relation

// SubmissionFile Model
$submission->bimbingan()  // The bimbingan it belongs to
$submission->mahasiswa()  // Student who submitted
$submission->dosen()      // Dosen who reviewed
$submission->comments()   // All comments on this

// Comment Model
$comment->submission()  // The submission it's attached to
$comment->dosen()       // Dosen who made the comment

// SchedulePeriod Model
$period->bimbingans()   // All bimbingan in this period
```

---

## 🔐 Authorization & Middleware

Setiap controller menggunakan authorization checks:

```php
// Dosen only
$this->authorize('view-mahasiswa-bimbingan', $mahasiswa);
$this->authorize('review-submission', $submission);
$this->authorize('update-bimbingan', $bimbingan);

// Mahasiswa only
$this->authorize('view', $bimbingan);
$this->authorize('view', $submission);
```

---

## 📁 Direktori Struktur Views

```
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── mahasiswa/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── show.blade.php
│   ├── dosen/
│   │   ├── index.blade.php
│   │   ├── create.blade.php
│   │   └── show.blade.php
│   ├── periods/
│   │   ├── index.blade.php
│   │   └── create.blade.php
│   ├── reports.blade.php
│   └── laporan.blade.php
├── dosen/
│   ├── dashboard.blade.php
│   ├── mahasiswa/
│   │   ├── index.blade.php
│   │   └── show.blade.php
│   ├── submissions/
│   │   └── review.blade.php
│   ├── bimbingan/
│   │   └── show.blade.php
│   └── history.blade.php
└── mahasiswa/
    ├── dashboard.blade.php
    ├── bimbingan/
    │   ├── index.blade.php
    │   └── show.blade.php
    ├── submissions/
    │   └── show.blade.php
    ├── uploads/
    │   └── create.blade.php
    ├── progress.blade.php
    └── archive/
        └── download
```

---

## 🚀 Routes Summary

### Admin Routes
```
GET    /admin/dashboard              → admin.dashboard
GET    /admin/mahasiswa              → admin.mahasiswa.index
GET    /admin/mahasiswa/{id}         → admin.mahasiswa.show
GET    /admin/dosen                  → admin.dosen.index
GET    /admin/dosen/{id}             → admin.dosen.show
GET    /admin/periods                → admin.periods.index
GET    /admin/periods/create         → admin.periods.create
POST   /admin/periods                → admin.periods.store
GET    /admin/reports                → admin.reports
GET    /admin/laporan                → admin.laporan
```

### Dosen Routes
```
GET    /dosen/dashboard              → dosen.dashboard
GET    /dosen/mahasiswa              → dosen.mahasiswa.index
GET    /dosen/mahasiswa/{id}         → dosen.mahasiswa.show
GET    /dosen/bimbingan/{id}         → dosen.bimbingan.show
GET    /dosen/submissions/{id}/review → dosen.review-submission
POST   /dosen/submissions/{id}/comment → dosen.add-comment
PUT    /dosen/submissions/{id}/approve → dosen.approve-submission
PUT    /dosen/submissions/{id}/reject  → dosen.reject-submission
GET    /dosen/history                → dosen.history
```

### Mahasiswa Routes
```
GET    /mahasiswa/dashboard          → mahasiswa.dashboard
GET    /mahasiswa/bimbingan          → mahasiswa.bimbingan.index
GET    /mahasiswa/bimbingan/{id}     → mahasiswa.bimbingan.show
GET    /mahasiswa/bimbingan/{id}/upload → mahasiswa.upload-form
POST   /mahasiswa/bimbingan/{id}/upload → mahasiswa.store-upload
GET    /mahasiswa/submissions/{id}   → mahasiswa.show-submission
GET    /mahasiswa/progress           → mahasiswa.progress
GET    /mahasiswa/bimbingan/{id}/archive/download → mahasiswa.download-archive
```

---

## 💾 Storage & File Handling

**Upload Directory**: `storage/app/public/submissions/`
**File Types**: PDF, DOC, DOCX
**Max Size**: 10 MB
**Accessible via**: `/storage/submissions/...` (after running `php artisan storage:link`)

---

## 📝 Catatan Implementasi

1. **Authorization Policies** perlu dibuat untuk mengecek permission
2. **Email Notifications** opsional untuk notifikasi upload/comment
3. **File Virus Scanning** opsional untuk keamanan
4. **Backup Mechanism** untuk file submission yang penting
5. **Audit Trail** untuk tracking perubahan status

---

## ✅ Fitur yang Sudah Diimplementasikan

- ✅ Database Migrations (submission_files, comments, schedule_periods)
- ✅ Models (SubmissionFile, Comment, SchedulePeriod)
- ✅ Controllers (AdminController, DosenController, MahasiswaController)
- ✅ Model Relationships
- ⏳ Views & Routes (akan dibuat selanjutnya)
- ⏳ Authorization Policies
- ⏳ Email Notifications

