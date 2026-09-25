<?php
/**
 * Front controller tạm thời - kiểm tra stack PHP + MySQL chạy được trong Docker.
 * Sẽ được thay bằng front controller thật khi phát triển.
 */
declare(strict_types=1);

$config = [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'port' => getenv('DB_PORT') ?: '3306',
    'name' => getenv('DB_NAME') ?: 'dnc_school',
    'user' => getenv('DB_USER') ?: 'root',
    'pass' => getenv('DB_PASS') ?: '',
];

$status = 'Chưa kết nối';
$tables = [];
$error  = null;
try {
    $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $config['host'], $config['port'], $config['name']);
    $pdo = new PDO($dsn, $config['user'], $config['pass'], [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $status = 'Kết nối MySQL thành công';
    foreach ($pdo->query('SHOW TABLES') as $row) {
        $table = array_values($row)[0];
        $count = (int) $pdo->query("SELECT COUNT(*) FROM `$table`")->fetchColumn();
        $tables[$table] = $count;
    }
} catch (Throwable $ex) {
    $status = 'Lỗi kết nối MySQL';
    $error  = $ex->getMessage();
}
$e = static fn (string $s): string => htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Trường Trung học Quốc tế DNC</title>
<style>
  :root { --navy:#0f2c59; --gold:#e5b53a; --bg:#f4f6f9; }
  body { margin:0; font-family:"Segoe UI", Roboto, Arial, sans-serif; background:var(--bg); color:#1f2937; }
  header { background:var(--navy); color:#fff; padding:20px 32px; border-bottom:4px solid var(--gold); }
  header h1 { margin:0; font-size:22px; letter-spacing:.5px; text-transform:uppercase; }
  header p { margin:4px 0 0; opacity:.85; font-size:14px; }
  main { max-width:860px; margin:32px auto; padding:0 16px; }
  .card { background:#fff; border-radius:10px; padding:24px; box-shadow:0 2px 10px rgba(15,44,89,.08); margin-bottom:20px; }
  .ok { color:#15803d; font-weight:600; } .err { color:#b91c1c; font-weight:600; }
  table { width:100%; border-collapse:collapse; } th,td { text-align:left; padding:8px 10px; border-bottom:1px solid #e5e7eb; }
  th { background:var(--navy); color:#fff; } code { background:#eef2f7; padding:2px 6px; border-radius:4px; }
</style>
</head>
<body>
<header>
  <h1>Trường Trung học Quốc tế DNC</h1>
  <p>DNC International High School · Môi trường phát triển (PHP <?= $e(PHP_VERSION) ?>)</p>
</header>
<main>
  <div class="card">
    <h2>Trạng thái hệ thống</h2>
    <p class="<?= $error ? 'err' : 'ok' ?>"><?= $e($status) ?></p>
    <?php if ($error): ?><pre><?= $e($error) ?></pre><?php endif; ?>
    <p>Máy chủ CSDL: <code><?= $e($config['host']) ?>:<?= $e($config['port']) ?></code> · Database: <code><?= $e($config['name']) ?></code></p>
  </div>
  <?php if ($tables): ?>
  <div class="card">
    <h2>Bảng dữ liệu</h2>
    <table>
      <thead><tr><th>Bảng</th><th>Số bản ghi</th></tr></thead>
      <tbody>
      <?php foreach ($tables as $name => $count): ?>
        <tr><td><code><?= $e($name) ?></code></td><td><?= $count ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
  <div class="card">
    <p>Trang này là placeholder kiểm tra môi trường. Mã nguồn website đang được phát triển.</p>
  </div>
</main>
</body>
</html>
