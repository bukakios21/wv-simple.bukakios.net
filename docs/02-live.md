# BukaKios WebView Simple — Live / Production Guide

## Prerequisites

- Docker + Docker Compose installed
- Akses sudo
- Domain sudah pointing ke server
- SSL certificate (via Nginx reverse proxy atau Cloudflare)

---

## Deploy / Update

Setiap ada perubahan file PHP, config, atau apapun, wajib rebuild + redeploy.

```bash
# 1. Pull perubahan dari git (jika ada)
git pull

# 2. Build image + jalankan
sudo docker compose up -d --build

# 3. Cek container running
sudo docker compose ps
```

## Restart

```bash
sudo docker compose restart
```

## Stop

```bash
sudo docker compose down
```

## Lihat Logs

```bash
# Semua logs
sudo docker compose logs -f

# Hanya app
sudo docker compose logs -f app
```

## Update .env (tanpa restart container)

Edit `.env`, lalu:

```bash
sudo docker compose up -d
```

Tanpa `--build`, cukup restart service-nya aja.

## Hapus & Clean Up

```bash
# Stop + hapus container
sudo docker compose down

# Hapus container + image
sudo docker compose down --rmi local
```

---

## Struktur Build

Production pakai `Dockerfile` untuk bake seluruh source code ke dalam image Docker.

- `docker-compose.yml` → definisi service + build context
- `Dockerfile` → install Nginx + PHP + copy source code
- Image: `wv-simple:latest` (latest tag)

File tidak di-mount dari host. Seluruh source code baked ke image layer → immutable, lebih secure.

---

## HTTPS / SSL

Docker container ini hanya expose port **80** (HTTP).

Untuk HTTPS, gunakan salah satu cara:

### Opsi 1: Cloudflare (Recommended)
Pasang Cloudflare di depan, arahkan `wv-simple.bukakios.net` ke IP server. SSL terminate di Cloudflare.

### Opsi 2: Nginx Reverse Proxy
Pasang Nginx di host (di luar Docker) sebagai reverse proxy untuk `127.0.0.1:1001`, terminate SSL di Nginx host.

---

## Environment Variables

Edit file `.env` di server:

```env
APP_URL=https://wv-simple.bukakios.net
API_KEY=your_api_key_here
API_URL=https://api.bukakios.net
API_V2_KEY=your_api_v2_key_here
API_V2_SECRET=your_api_v2_secret_here
API_V2_URL=https://api-v2.bukakios.net/v2
API_V2_WV_URL=https://api-v2.bukakios.net/wv-x7Up2p
WA_NUMBER=6282184284119
OPEN_URL=https://wv.bukakios.net/
PRIMARY=#E53935
```

Setelah edit, restart:

```bash
sudo docker compose up -d
```

---

## Monitoring

```bash
# Cek status container
sudo docker compose ps

# Cek resource usage
sudo docker stats

# Cek port yang di-listen
sudo ss -tlnp | grep 1001
```
