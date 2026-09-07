# BukaKios WebView Simple — Development Guide

## Prerequisites

- Docker + Docker Compose installed
- Akses sudo

---

## Start (Dev Mode)

File edit langsung生效, **tanpa rebuild**.

```bash
# Build image dulu (sekali saja saat pertama kali)
sudo docker compose build

# Jalankan dev mode
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

## Stop

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml down
```

## Restart (setelah edit nginx.conf atau docker-compose)

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml restart
```

## Lihat Logs

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml logs -f
```

## Hapus Container + Image

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml down --rmi all
```

---

## Catatan Penting

### Dev vs Live

| | Dev | Live |
|---|---|---|
| Command | `-f docker-compose.yml -f docker-compose.dev.yml up -d` | `up -d --build` |
| File mount | `:ro` (read-only dari host) | Baked ke image |
| Edit PHP | Langsung生效 | Harus `--build` ulang |
| Keamanan | Container baca file host | File terkunci di image |

### Kalau Nginx Config Diedit

Restart aja:

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml restart
```

Tidak perlu rebuild karena `nginx.conf` di-mount langsung.

### Kalau PHP Diedit

Langsung生效, tidak perlu restart.

### Kalau .env Diedit

Langsung生效, tidak perlu restart.

### Kalau docker-compose.yml Diedit

Perlu restart:

```bash
sudo docker compose -f docker-compose.yml -f docker-compose.dev.yml up -d
```

---

## Akses

- **HTTP:** http://localhost:1001
- **Bypass dev token:** `?token_dev=<jwt>&uid_dev=<uid>`

```bash
# Contoh
curl "http://localhost:1001/tentang?token_dev=abc123&uid_dev=1"
```
