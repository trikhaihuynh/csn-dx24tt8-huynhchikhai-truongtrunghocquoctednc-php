@echo off
chcp 65001 >nul
set "MYSQL_EXE=C:\xampp\mysql\bin\mysql.exe"
if not "%~1"=="" set "MYSQL_EXE=%~1"
set "INIT_DIR=%~dp0..\docker\mysql\init"

if not exist "%MYSQL_EXE%" (
    echo Không tìm thấy %MYSQL_EXE%
    echo Cách dùng: install-xampp.bat "đường\dẫn\tới\mysql.exe"
    exit /b 1
)

"%MYSQL_EXE%" -u root --default-character-set=utf8mb4 < "%INIT_DIR%\01-schema.sql"
if errorlevel 1 exit /b 1
"%MYSQL_EXE%" -u root --default-character-set=utf8mb4 < "%INIT_DIR%\02-seed.sql"
if errorlevel 1 exit /b 1

echo Đã tạo cơ sở dữ liệu dnc_school và dữ liệu mẫu.
echo Tiếp theo: cấu hình Apache theo setup\README.md rồi mở http://localhost:8080
