# Sistem Slip Gaji ASN - SIPENA Integration

## Deskripsi
Sistem pencarian dan download slip gaji ASN/Pegawai dengan integrasi API SIPENA.

## Fitur Utama

### 1. Form Pencarian Slip Gaji
- Nomor Induk Pegawai (NIP)
- Bulan & Tahun
- Unit / Satuan Kerja
- Kepentingan / Tujuan Unduh

### 2. Integrasi API SIPENA
- Laravel Http Client dengan retry mechanism
- Service Repository Pattern
- Clean Architecture
- Error handling lengkap
- Logging request/response
- Timeout request

### 3. Fitur Tambahan
- Dark mode
- AJAX search tanpa reload
- Download progress
- Riwayat pencarian user
- Export Excel/PDF
- Dashboard statistik
- Rate limiting
- Security CSRF protection

## Struktur Project

```
app/
├── Exceptions/
│   └── SipenaException.php
├── Http/
│   ├── Controllers/
│   │   └── SlipGajiController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/
│   ├── ApiLog.php
│   └── LogPencarianSlip.php
├── Repositories/
│   └── SipenaRepository.php
└── Services/
    └── SipenaService.php

config/
└── sipena.php

database/
└── migrations/
    ├── 2024_01_01_000001_create_logs_pencarian_slip_table.php
    └── 2024_01_01_000002_create_api_logs_table.php

resources/
└── views/
    └── slip-gaji/
        ├── index.blade.php
        └── exports/
            └── pdf.blade.php

routes/
└── web.php
```

## Instalasi

### 1. Clone Repository
```bash
git clone <repository-url>
cd slipgajiweb
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Environment
Edit file `.env` dan tambahkan konfigurasi SIPENA:

```env
# SIPENA Configuration
SIPENA_BASE_URL=https://api.sipena.go.id
SIPENA_API_KEY=your_api_key_here
SIPENA_USERNAME=your_username
SIPENA_PASSWORD=your_password
SIPENA_TIMEOUT=30

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=slipgaji
DB_USERNAME=root
DB_PASSWORD=

# Cache
CACHE_DRIVER=redis

# Queue
QUEUE_CONNECTION=redis
```

### 5. Run Migrations
```bash
php artisan migrate
```

### 6. Start Development Server
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000/slip-gaji`

## Konfigurasi API SIPENA

### File: `config/sipena.php`

```php
return [
    'base_url' => env('SIPENA_BASE_URL', 'https://api.sipena.go.id'),
    'api_key' => env('SIPENA_API_KEY'),
    'username' => env('SIPENA_USERNAME'),
    'password' => env('SIPENA_PASSWORD'),
    'timeout' => env('SIPENA_TIMEOUT', 30),
    'retry' => [
        'times' => 3,
        'sleep' => 1000, // milliseconds
    ],
    'cache' => [
        'unit_kerja' => 3600, // 1 hour
        'tujuan_unduh' => 3600,
        'token' => 3600,
    ],
];
```

## Contoh Response API

### Search Slip Gaji
```json
{
    "success": true,
    "message": "Data slip gaji ditemukan",
    "data": [
        {
            "id": "SLIP-2024-001",
            "nip": "198501232010011001",
            "nama": "Dr. Ahmad Fauzi, M.Si",
            "unit_kerja": "Kementerian Keuangan",
            "periode": "Januari 2024",
            "bulan": 1,
            "tahun": 2024,
            "gaji_pokok": 5500000,
            "tunjangan": 2750000,
            "total": 8250000,
            "pdf_url": "https://api.sipena.go.id/slip/download/xxx"
        }
    ]
}
```

### Download Slip
```json
{
    "success": true,
    "message": "Slip gaji siap didownload",
    "data": {
        "pdf_base64": "JVBERi0xLjQK...",
        "filename": "slip_gaji_198501232010011001_2024_01.pdf"
    }
}
```

## Testing API

### Manual Testing dengan cURL

```bash
# Search slip gaji
curl -X POST http://localhost:8000/slip-gaji/search \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_token" \
  -d '{
    "nip": "198501232010011001",
    "bulan": 1,
    "tahun": 2024,
    "unit_kerja": "KEMENKEU",
    "tujuan_unduh": "perbankan"
  }'

# Download slip
curl -X POST http://localhost:8000/slip-gaji/download/SLIP-2024-001 \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your_token" \
  -d '{
    "tujuan_unduh": "perbankan"
  }'
```

### PHPUnit Testing
```bash
php artisan test
```

## API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/slip-gaji` | Halaman utama |
| POST | `/slip-gaji/search` | Pencarian slip gaji |
| POST | `/slip-gaji/download/{slipId}` | Download slip gaji |
| GET | `/slip-gaji/unit-kerja` | Get unit kerja options |
| GET | `/slip-gaji/tujuan-unduh` | Get tujuan unduh options |
| GET | `/slip-gaji/riwayat` | Get riwayat pencarian |
| GET | `/slip-gaji/statistik` | Dashboard statistik (admin) |
| GET | `/slip-gaji/api-logs` | API logs (admin) |

## Error Handling

| Error Code | Description |
|------------|-------------|
| `API_TIMEOUT` | Request timeout |
| `TOKEN_EXPIRED` | Token expired, perlu login ulang |
| `DATA_NOT_FOUND` | Data tidak ditemukan |
| `SERVER_ERROR` | Server SIPENA error |
| `INVALID_RESPONSE` | Response JSON invalid |

## Security Features

- CSRF Protection
- Rate Limiting (60 requests/minute)
- Input Sanitization
- Secure Token Storage
- XSS Protection
- SQL Injection Prevention

## Scheduler Commands

```bash
# Clear old logs (run daily)
php artisan schedule:run

# Or manually
php artisan sipena:clear-logs
```

## Queue Workers

```bash
# Start queue worker
php artisan queue:work redis --queue=sipena

# Process jobs
php artisan queue:process
```

## Docker Setup

### Build Image
```bash
docker build -t slipgajiweb:latest .
```

### Run Container
```bash
docker run -d \
  -p 8000:8000 \
  -v $(pwd):/var/www/html \
  --name slipgajiweb \
  slipgajiweb:latest
```

### Docker Compose
```bash
docker-compose up -d
```

## Production Deployment

### Requirements
- PHP 8.2+
- MySQL 8.0+ / PostgreSQL 14+
- Redis 6+
- Nginx / Apache
- SSL Certificate

### Nginx Configuration
```nginx
server {
    listen 80;
    server_name slipgaji.domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name slipgaji.domain.com;
    
    root /var/www/slipgajiweb/public;
    index index.php;
    
    ssl_certificate /etc/ssl/certs/slipgaji.crt;
    ssl_certificate_key /etc/ssl/private/slipgaji.key;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
}
```

### Deployment Script
```bash
#!/bin/bash
cd /var/www/slipgajiweb
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan queue:restart
```

## Troubleshooting

### Common Issues

1. **API Timeout**
   - Check network connectivity
   - Increase timeout in config
   - Check SIPENA server status

2. **Token Expired**
   - Implement auto-refresh token
   - Check token expiration time
   - Re-login if necessary

3. **Data Not Found**
   - Verify NIP format (9-18 digits)
   - Check if slip gaji exists for period
   - Confirm unit kerja is correct

4. **Download Failed**
   - Check disk space
   - Verify storage permissions
   - Check PDF generation library

## License

MIT License - Copyright 2024
