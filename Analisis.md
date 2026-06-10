# Analisis Perbaikan

## Permasalahan 1

### Gejala
Docker Compose tidak bisa dijalankan dan memunculkan pesan error:

```bash
yaml: line 3: could not find expected ':'
```

### Penyebab
Deklarasi `services` pada file `docker-compose.yml` tidak disertai tanda titik dua (`:`).

```yaml
services
```

### Solusi
Tambahkan tanda titik dua setelah kata kunci `services`:

```yaml
services:
```

---

## Permasalahan 2

### Gejala
Docker Compose menampilkan pesan error:

```bash
service "db" refers to undefined volume db-data
```

### Penyebab
Service database merujuk ke volume bernama `db-data`, namun pada bagian deklarasi `volumes` nama yang terdaftar berbeda:

```yaml
- db-data:/var/lib/mysql
```

```yaml
volumes:
  database-data:
```

### Solusi
Sesuaikan nama volume agar konsisten:

```yaml
volumes:
  db-data:
```

---

## Permasalahan 3

### Gejala
Proses build gagal dengan pesan:

```bash
unable to prepare context: path ".../web33" not found
```

### Penyebab
Service `web3` dikonfigurasi menggunakan path build context yang tidak sesuai dengan nama folder yang sebenarnya:

```yaml
context: ./web33
```

### Solusi
Perbaiki path agar mengarah ke folder yang benar:

```yaml
context: ./web3
```

---

## Permasalahan 4

### Gejala
Build image web1 gagal dengan pesan:

```bash
php:8.2-apach: not found
```

### Penyebab
Nama base image pada Dockerfile web1 mengandung typo:

```dockerfile
FROM php:8.2-apach
```

### Solusi
Perbaiki penulisan nama image:

```dockerfile
FROM php:8.2-apache
```

---

## Permasalahan 5

### Gejala
Build image web3 gagal dengan pesan:

```bash
php:8.2-apche: not found
```

### Penyebab
Nama base image pada Dockerfile web3 juga mengandung typo:

```dockerfile
FROM php:8.2-apche
```

### Solusi
Perbaiki penulisan nama image:

```dockerfile
FROM php:8.2-apache
```

---

## Permasalahan 6

### Gejala
Container MySQL berhenti saat startup disertai error syntax SQL.

### Penyebab
File `init.sql` masih menyertakan pembungkus markdown code block, sehingga tidak dapat diproses oleh MySQL:

```sql
```sql
CREATE TABLE ...
```
```

### Solusi
Hapus semua penanda markdown dari file tersebut sehingga hanya menyisakan perintah SQL murni.

---

## Permasalahan 7

### Gejala
Container Nginx gagal dijalankan dengan pesan error:

```bash
unknown directive "```nginx"
```

### Penyebab
File `nginx.conf` masih memuat pembungkus markdown code block:

```nginx
```nginx
...
```
```

### Solusi
Hapus semua penanda markdown dari file tersebut sehingga hanya berisi konfigurasi Nginx yang valid.

---

## Permasalahan 8

### Gejala
Container Nginx gagal dijalankan dengan pesan error:

```bash
host not found in upstream "web11"
```

### Penyebab
Nama host pada konfigurasi upstream Nginx tidak sesuai dengan nama service yang terdaftar:

```nginx
server web11:80;
```

### Solusi
Ganti nama host agar sesuai dengan nama service yang benar:

```nginx
server web1:80;
```

---

## Permasalahan 9

### Gejala
Nginx tidak dapat meneruskan request ke web3.

### Penyebab
Konfigurasi upstream mengarahkan ke port 8080, padahal Apache di dalam container web3 berjalan pada port 80:

```nginx
server web3:8080;
```

### Solusi
Sesuaikan port pada konfigurasi upstream:

```nginx
server web3:80;
```

---

## Permasalahan 10

### Gejala
Nginx tidak dapat mengakses service web3.

### Penyebab
Service `web3` hanya terhubung ke network `backend`, sedangkan Nginx berada di network `frontend`:

```yaml
networks:
  - backend
```

### Solusi
Tambahkan network `frontend` pada service `web3`:

```yaml
networks:
  - frontend
  - backend
```

---

## Permasalahan 11

### Gejala
Konfigurasi web1 tidak dapat menemukan host database yang benar.

### Penyebab
Environment variable `DB_HOST` pada web1 merujuk ke nama yang tidak sesuai dengan nama service database:

```yaml
DB_HOST: mysql
```

### Solusi
Sesuaikan nilai `DB_HOST` dengan nama service database yang terdaftar:

```yaml
DB_HOST: db
```

---

## Permasalahan 12

### Gejala
Koneksi database pada web2 gagal karena kredensial yang digunakan salah.

### Penyebab
Password yang dikonfigurasi tidak sesuai:

```yaml
DB_PASS: wrongpassword
```

### Solusi
Ganti password dengan nilai yang benar:

```yaml
DB_PASS: student123
```

---

## Permasalahan 13

### Gejala
Identitas praktikan belum tampil pada aplikasi.

### Penyebab
File PHP masih menggunakan nilai placeholder yang belum diganti:

```php
$nama = "ganti ke namamu";
$nim  = "ganti ke nimmu";
```

### Solusi
Ganti nilai placeholder tersebut dengan data identitas praktikan yang sesuai.

---

## Permasalahan 14

### Gejala
Informasi nama container pada web2 dan web3 tidak sesuai.

### Penyebab
Terdapat kesalahan penulisan label container:

```html
WEB-WEB
```

dan

```html
WEB-WOB
```

### Solusi
Perbaiki menjadi label yang benar:

```html
WEB-2
```

dan

```html
WEB-3
```

---

## Permasalahan 15

### Gejala
Data identitas praktikan ditampilkan secara statis (hardcoded) pada masing-masing web server dan tidak diambil dari database.

Contoh:

```php
$nama = "ganti ke namamu";
$nim  = "ganti ke nimmu";
```

### Penyebab
File `index.php` pada web1, web2, dan web3 hanya menampilkan data yang ditulis langsung di source code, tanpa memanfaatkan database yang telah tersedia.

### Solusi
Ubah file `index.php` pada seluruh web server agar terhubung ke database MySQL dan mengambil data dari tabel `students`.

Tambahkan kode koneksi berikut:

```php
$conn = new mysqli("db", "student", "student123", "responsi");

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM students LIMIT 1");
$data = $result->fetch_assoc();
```

Kemudian tampilkan data identitas dari hasil query:

```php
<strong><?= $data['nama'] ?></strong>

<strong><?= $data['nim'] ?></strong>
```

Dengan perubahan ini, seluruh web server mengambil data identitas dari sumber yang sama, sehingga integrasi database pada sistem berjalan sebagaimana mestinya.

---

## Permasalahan 16

### Gejala
Saat menggunakan mysqli muncul error:

```bash
Fatal error: Class "mysqli" not found
```

### Penyebab
Image PHP yang digunakan belum dilengkapi ekstensi mysqli.

### Solusi
Tambahkan perintah instalasi ekstensi mysqli pada Dockerfile:

```dockerfile
RUN docker-php-ext-install mysqli
```

Kemudian lakukan rebuild container:

```bash
docker compose build --no-cache
docker compose up -d
```

---

## Screenshot Hasil
