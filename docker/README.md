# Môi trường Docker — Website Trường Trung học Quốc tế DNC

Ba dịch vụ: `web` (PHP 8.2 + Apache), `db` (MySQL 8.0), `phpmyadmin`.

## Chạy lần đầu
```bash
cd docker
cp .env.example .env
docker compose up -d --build
```
- Website: http://localhost:8080
- phpMyAdmin: http://localhost:8081 (server `db`, user `dnc` / `dnc123`)
- Admin mẫu: `admin@dnc.edu.vn` / `Admin@123`

## Lệnh thường dùng
| Việc | Lệnh |
|---|---|
| Xem log web | `docker compose logs -f web` |
| Dừng | `docker compose down` |
| Dừng và xóa dữ liệu MySQL | `docker compose down -v` |
| Build lại sau khi đổi Dockerfile/php.ini | `docker compose up -d --build` |
| Vào MySQL | `docker compose exec db mysql -udnc -pdnc123 dnc_school` |
| Chạy PHP trong container | `docker compose exec web php -v` |

## Ghi chú
- Mã nguồn `../src` được mount vào `/var/www/html`, DocumentRoot là `src/public`. Sửa code không cần build lại.
- `mysql/init/*.sql` chỉ chạy **một lần** khi volume `db_data` được tạo. Muốn áp dụng lại schema: `docker compose down -v` rồi `up`.
- Ứng dụng đọc kết nối CSDL qua biến môi trường `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS` (khai báo trong `docker-compose.yml`, giá trị lấy từ `.env`).
