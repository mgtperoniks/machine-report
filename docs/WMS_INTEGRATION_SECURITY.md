# Dokumentasi Keamanan Integrasi MRM ↔ WMS

Dokumen ini mencakup model arsitektur, kebijakan hak akses database (Principle of Least Privilege), dan panduan disaster recovery untuk integrasi **Machine Report System (MRM)** dengan **Warehouse Management System (WMS)**.

---

## 1. Filosofi & Batasan Domain (Domain Boundary)

- **Warehouse Management System (WMS)**: Single Source of Truth untuk seluruh data inventory perusahaan (Master Sparepart, Live Stock, Barcode, Rak, Supplier).
- **Machine Report System (MRM)**: Pemilik domain Machine Maintenance & Reliability. MRM bertindak murni sebagai **Read-Only Client** terhadap WMS.
- **Strict Separation**: MRM tidak boleh mengubah, memperbarui, menambah, ataupun menghapus stok/master data WMS.

---

## 2. Model Hak Akses Database (Principle of Least Privilege)

Untuk menjamin keamanan dan mencegah modifikasi tidak disengaja pada database WMS, user MySQL yang digunakan MRM dikonfigurasi dengan prinsip hak akses minimal:

| Parameter | Nilai Konfigurasi Production |
| :--- | :--- |
| **Host** | `warehouse-db` (Docker Container Network) |
| **Database Target** | `warehouse_system_sparepart` |
| **User MySQL** | `machine_report` |
| **Granted Privileges** | **`SELECT` ONLY** |

### Perintah SQL Grant Production
```sql
-- Memberikan HANYA hak akses SELECT (Read-Only) pada database WMS
GRANT SELECT ON `warehouse_system_sparepart`.* TO 'machine_report'@'%';
FLUSH PRIVILEGES;
```

---

## 3. Mengapa Hanya Privilese `SELECT` yang Diizinkan?

1. **Proteksi Inventory**: Mengeliminasi risiko modifikasi stok, pembaruan barcode, atau penghapusan master data WMS dari layer aplikasi MRM.
2. **Read-Only Consumption**: Aplikasi MRM hanya mengeksekusi query pencarian (`SELECT ... FROM item_variants ...`) dan batch lookup stock.
3. **Isolasi Kegagalan**: Jika terjadi kebocoran kredensial atau kerentanan pada MRM, penyerang tidak dapat mengubah data apa pun pada WMS.

---

## 4. Diagram Arsitektur Integrasi MRM → WMS

```mermaid
graph TD
    subgraph Machine Report System (MRM)
        A[Blade View / AJAX Search] -->|fetchJson| B[MachineSparepartController]
        B --> C[MachineSparepartService]
        C --> D[DatabaseSparepartLookupRepository]
    end

    subgraph Warehouse Management System (WMS DB)
        E[(warehouse_system_sparepart)]
    end

    D -->|READ-ONLY: SELECT| E

    style E fill:#f9f,stroke:#333,stroke-width:2px
    style D fill:#bbf,stroke:#333,stroke-width:1px
```

---

## 5. Checklist Verifikasi Saat Deployment Server Baru (Disaster Recovery)

Saat melakukan setup server baru atau disaster recovery:
1. Pastikan container `app` (MRM) dan container `warehouse-db` berada dalam Docker network yang sama.
2. Jalankan script SQL `GRANT SELECT ON warehouse_system_sparepart.* TO 'machine_report'@'%';` pada container MySQL WMS.
3. Jalankan `php artisan config:cache` pada MRM.
4. Uji endpoint autocomplete pencarian sparepart: `GET /machines/{machine}/spareparts/search?q=6204`.
