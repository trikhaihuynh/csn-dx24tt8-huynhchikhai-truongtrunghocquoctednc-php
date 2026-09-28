#!/usr/bin/env sh
set -e

cd "$(dirname "$0")/../docker"

if [ ! -f .env ]; then
    cp .env.example .env
    echo "Đã tạo docker/.env từ .env.example"
fi

docker compose up -d --build

echo ""
echo "Website:      http://localhost:8080"
echo "Quản trị:     http://localhost:8080/admin/login"
echo "phpMyAdmin:   http://localhost:8081"
echo "Nếu đã đổi cổng trong docker/.env, dùng cổng tương ứng."
