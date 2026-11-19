#!/usr/bin/env bash
set -e

echo "=== 🚀 Starting Library Management System Backend Automated Deployment ==="

# 1. Pull latest changes from GitHub
echo "📥 1. Pulling latest code from GitHub..."
git pull origin main

# 2. Rebuild and start Docker containers
echo "🔨 2. Rebuilding and restarting Docker containers..."
docker compose up -d --build

# 3. Clean up dangling/unused images
echo "🧹 3. Cleaning up unused Docker images..."
docker image prune -f

# 4. Run database migrations
echo "🗄️ 4. Running Database Migrations..."
docker compose exec -T app php artisan migrate --force

# 5. Optimize configuration and routes
echo "⚡ 5. Ensuring storage symlink, optimizing configuration & route caching..."
docker compose exec -T app php artisan storage:link --force || true
docker compose exec -T app php artisan config:cache
docker compose exec -T app php artisan route:cache

# 6. Display container status
echo "📊 6. Checking container status..."
docker compose ps

echo "=== ✅ Library Management System Backend Deployment Finished Successfully! ==="
