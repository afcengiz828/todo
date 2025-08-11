## Todo App API (Laravel)

Tamamlanmış bir Todo uygulaması için RESTful JSON API. Laravel 12 ile geliştirilmiş backend API, CRUD işlemleri, filtreleme, arama, sıralama ve sayfalama özellikleri içerir. MySQL, PostgreSQL veya SQLite veritabanları desteklenir.

### Özellikler
- **CRUD**: Görev ekleme, listeleme, güncelleme, silme
- **Filtreleme**: Duruma ve önceliğe göre
- **Arama**: Başlık ve açıklama içinde metin arama
- **Sıralama**: ID, başlık veya son tarih; artan/azalan yön desteği
- **Sayfalama**: Sunucu tarafı sayfalama (10–50 kayıt/sayfa)
- **Doğrulama**: Laravel Validation + Enum doğrulama
- **Güvenlik**: Soft delete, input sanitization
- **API**: RESTful JSON API, CORS desteği
- **Veritabanı**: MySQL/PostgreSQL/SQLite desteği

---

## Teknoloji Stack'i
- **Backend**: PHP 8.2, Laravel 12
- **Veritabanı**: MySQL/PostgreSQL/SQLite
- **ORM**: Eloquent ORM
- **API**: Laravel API Resources, JSON responses
- **Güvenlik**: Laravel Validation, mews/purifier
- **Veri**: Seeder/Factory ile örnek veri (100 adet todo)

---

## Kurulum Adımları

### Laravel 12 API Kurulumu

Proje Laravel 12 ile geliştirilmiş bir RESTful JSON API içerir.  
API uç noktaları: `GET/POST/PUT/PATCH/DELETE http://localhost:8000/api/todos`

**API Özellikleri:**
- CRUD işlemleri (soft delete ile silme)
- Filtreleme: `status`, `priority`
- Arama: `q` parametresi ile başlık/açıklama
- Sıralama: `sort` ve `order`
- Sayfalama: `page` ve `limit` (10–50 arası)
- Enum doğrulama: `status`, `priority`
- Tarih formatı: `due_date` (YYYY-MM-DD)
- JSON Resource yanıtları
- CORS yapılandırması (yerel geliştirme için)

#### Teknoloji Stack'i (Back-end)
- PHP 8.2, Laravel 12
- Eloquent ORM, Laravel API Resources
- mews/purifier (girdi temizleme altyapısı)
- PHPUnit, Faker (test ve veri üretimi)
- Seeder/Factory ile örnek veri (100 adet todo)

#### Kurulum (Back-end)
1. Bağımlılıklar
   ```bash
   composer install
   ```
2. Ortam değişkenleri
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   - `APP_URL=http://localhost:8000`
   **Veritabanı ayarları (MySQL):**
   
   ```bash
   # .env dosyasında:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=todo
   DB_USERNAME=root
   DB_PASSWORD=şifreniz
   ```
   
   **MySQL veritabanını oluşturun:**
   ```bash
   # MySQL'e bağlanın
   mysql -u root -p
   
   # Veritabanını oluşturun
   CREATE DATABASE todo;
   USE todo;
   exit;
   ```
   
   **Alternatif: SQLite (hızlı test için):**
   ```bash
   # .env dosyasında:
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite
   
   # SQLite dosyasını oluşturun:
   # Windows
   type NUL > database\database.sqlite
   # Linux/Mac
   touch database/database.sqlite
   ```

3. Migration + Seed
   ```bash
   php artisan migrate:fresh
   php artisan db:seed
   ```
4. CORS
   `config/cors.php` dosyasında `allowed_origins` varsayılan olarak `[*]`. 

#### Çalıştırma
```bash
php artisan serve
# API: http://localhost:8000
# Endpoint: http://localhost:8000/api/todos
```

**Test için:**
```bash
# API'yi test edin
curl http://localhost:8000/api/todos

# Tarayıcıda açın
http://localhost:8000/api/todos
```


## API Dokümantasyonu

### Temel URL
- `http://localhost:8000/api`

### Kaynak: Todos
- Model:
  ```json
  {
    "id": 1,
    "title": "Metin",
    "description": "Metin",
    "status": "pending | in_progress | completed | cancelled",
    "priority": "low | medium | high",
    "due_date": "YYYY-MM-DD",
    "created_at": "ISO",
    "updated_at": "ISO"
  }
  ```

#### Listeleme ve Arama
- GET `/todos`
  - Açıklama: Tüm görevleri getirir; filtreleme, sıralama ve sayfalama destekler.
  - Sorgu parametreleri:
    - `status`: `pending | in_progress | completed | cancelled`
    - `priority`: `low | medium | high`
    - `sort`: sıralanacak sütun (örn. `id`, `title`, `due_date`)
    - `order`: `asc | desc` (varsayılan `asc`)
    - `page`: 1..n
    - `limit`: 10..50
  - Örnek:
    ```bash
    curl "http://localhost:8000/api/todos?status=pending&priority=medium&sort=title&order=asc&page=1&limit=10"
    ```

- GET `/todos/search?q=...`
  - Açıklama: Başlık ve açıklama içinde metin arama.
  - Örnek:
    ```bash
    curl "http://localhost:8000/api/todos/search?q=alisveris"
    ```

#### CRUD
- GET `/todos/{id}`
  - Açıklama: Tek kaydı getirir.
  - Örnek:
    ```bash
    curl http://localhost:8000/api/todos/1
    ```

- POST `/todos`
  - Açıklama: Yeni görev oluşturur.
  - Doğrulama:
    - `title`: required, min 3, max 100
    - `description`: nullable, max 500
    - `status`: optional enum
    - `priority`: optional enum
    - `due_date`: nullable, format `Y-m-d`, bugün sonrası
  - Örnek:
    ```bash
    curl -X POST http://localhost:8000/api/todos \
      -H "Content-Type: application/json" \
      -d '{"title":"Alışveriş yap","description":"Süt ve ekmek","status":"pending","priority":"low","due_date":"2025-12-31"}'
    ```

- PUT `/todos/{id}`
  - Açıklama: Görevi tamamen günceller.
  - Örnek:
    ```bash
    curl -X PUT http://localhost:8000/api/todos/1 \
      -H "Content-Type: application/json" \
      -d '{"title":"Güncellendi","description":"Detay","status":"in_progress","priority":"high","due_date":"2026-01-01"}'
    ```

- PATCH `/todos/{id}`
  - Açıklama: Kısmi güncelleme.
  - Örnek:
    ```bash
    curl -X PATCH http://localhost:8000/api/todos/1 \
      -H "Content-Type: application/json" \
      -d '{"status":"completed"}'
    ```

- DELETE `/todos/{id}`
  - Açıklama: Soft delete ile siler.
  - Örnek:
    ```bash
    curl -X DELETE http://localhost:8000/api/todos/1
    ```

> Yanıt yapısı genel olarak şu şekildedir:
> ```json
> {
>   "status": "success | error",
>   "message": "Açıklayıcı mesaj",
>   "data": { },
>   "count": 123
> }
> ```

---

## Örnek Kullanım Senaryoları

### Todo Ekleme
1. **Add** sayfasına gidin
2. Formu doldurun:
   - Title: "Rapor hazırla" (zorunlu, 3-100 karakter)
   - Description: "Q4 raporu" (opsiyonel, max 500 karakter)
   - Status: "pending" (varsayılan)
   - Priority: "medium" (varsayılan)
   - Due Date: "2025-12-31" (opsiyonel, bugünden sonra)
3. **Submit** butonuna tıklayın

### Todo Arama ve Filtreleme
1. **List** sayfasına gidin
2. **Arama çubuğuna** metin yazın (başlık/açıklama içinde arar)
3. **Status filtresi** seçin: pending, in_progress, completed, cancelled
4. **Priority filtresi** seçin: low, medium, high
5. **Sıralama** yapın: ID, title, due_date (asc/desc)

### Todo Güncelleme
1. **List** sayfasında **Edit** linkine tıklayın
2. Veya **Add** sayfasında "Id to update" alanına ID girin
3. Formu güncelleyin
4. **Submit** butonuna tıklayın

### Durum Değiştirme
1. **List** sayfasında todo'nun **Status** dropdown'ına tıklayın
2. Yeni durumu seçin (otomatik kaydedilir)

### Todo Silme
1. **List** sayfasında **Delete** butonuna tıklayın
2. Todo soft delete ile silinir

---



