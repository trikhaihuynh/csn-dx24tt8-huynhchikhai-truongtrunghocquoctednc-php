@echo off
chcp 65001 >nul
cd /d "%~dp0..\docker"

if not exist .env (
    copy .env.example .env >nul
    echo Đã tạo docker\.env từ .env.example
)

docker compose up -d --build
if errorlevel 1 (
    echo Không khởi động được Docker Compose. Kiểm tra Docker Desktop đã chạy chưa.
    exit /b 1
)

echo.
echo Website:      http://localhost:8080
echo Quản trị:     http://localhost:8080/admin/login
echo phpMyAdmin:   http://localhost:8081
echo Nếu đã đổi cổng trong docker\.env, dùng cổng tương ứng.
