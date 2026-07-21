# Pedoman & Arsitektur Frontend MRM System

Dokumen ini menjelaskan standar arsitektur JavaScript, struktur folder `resources/js`, daftar utilitas, serta aturan pengembangannya untuk project Machine Report System (MRM).

---

## 1. Struktur Folder `resources/js`

```text
resources/js/
├── app.js                          <-- Entrypoint utama Vite & pendaftaran scope window minimal
├── utils/                          <-- Layer utilitas murni (reusable helpers)
│   ├── dom.js                      <-- Helper DOM (escapeHtml, setText, getCsrfToken)
│   ├── http.js                     <-- Helper AJAX/Fetch (fetchJson dengan CSRF & JSON handling)
│   └── ui.js                       <-- Helper UI (showToast, openModal, closeModal)
└── modules/                        <-- Domain-specific JavaScript modules
    └── machine-passport.js         <-- Logika interaktif halaman Machine Passport
```

---

## 2. Daftar Utilitas & Fungsi (`resources/js/utils/`)

| File Util | Nama Fungsi | Deskripsi |
| :--- | :--- | :--- |
| `dom.js` | `escapeHtml(str)` | Mengubah karakter khusus (`&`, `<`, `>`, `"`, `'`) menjadi HTML entity untuk mencegah serangan XSS pada dynamic HTML rendering. |
| `dom.js` | `setText(target, text)` | Mengatur properti `textContent` dari sebuah elemen atau ID elemen secara aman dari XSS. |
| `dom.js` | `getCsrfToken()` | Mengambil CSRF token dari `<meta name="csrf-token">` atau input `_token`. |
| `http.js` | `fetchJson(url, options)` | Wrapper `fetch` standar yang otomatis menyertakan header CSRF, `Accept: application/json`, dan penanganan error JSON secara terpusat. |
| `ui.js` | `showToast(msg, type)` | Menampilkan notifikasi Toast mengambang (*success*, *error*, atau *info*). |
| `ui.js` | `openModal(modalId)` | Membuka modal dialog berdasarkan ID elemen. |
| `ui.js` | `closeModal(modalId)` | Menutup modal dialog berdasarkan ID elemen. |

---

## 3. Kebijakan Exposure Scope `window` Global

Sebagai aturan utama, **ES Modules (`import`/`export`) adalah pola utama yang wajib digunakan**.

Fungsi/helper HANYA boleh diekspos ke `window` jika memenuhi kriteria berikut:
1. **Kompatibilitas Event Inline Blade**: Digunakan langsung di dalam atribut HTML Blade (misalnya `onclick="openSharedMachinesModal(...)"`).
2. **Reusability Lintas Modul**: Digunakan oleh modul independen lain tanpa ingin melakukan bundel ulang komponen terpisah.

Exposed globals saat ini terdaftar di `resources/js/app.js`:
- `window.escapeHtml`
- `window.setText`
- `window.showToast`
- `window.openModal`
- `window.closeModal`
- `window.fetchJson`

---

## 4. Aturan Pembuatan Modul Baru (`resources/js/modules/`)

1. **Modularitas Berdasarkan Kompleksitas Domain**:
   - Dilarang membuat file kecil yang terlalu spesifik untuk 1-2 baris skrip.
   - Buat file modul baru di `resources/js/modules/` jika kompleksitas suatu fitur/halaman sudah memuat > 50 baris skrip atau memiliki interaksi AJAX khusus (misalnya `planning-board.js`, `maintenance-report.js`).

2. **Inisialisasi Kondisional (DOM Guard)**:
   - Di dalam `app.js`, setiap modul diinisialisasi secara kondisional setelah memeriksa ketersediaan konteks DOM (misal `if (document.getElementById('target-id')) initModule();`).

3. **Gunakan Standard HTTP Helper**:
   - Seluruh panggilan AJAX wajib menggunakan `fetchJson(url, options)` dari `utils/http.js` untuk menjamin konsistensi CSRF & error handling.
