<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;
use PDO;

final class Admission extends Model
{
    public const STATUSES = [
        'moi' => 'Mới',
        'dang_xu_ly' => 'Đang xử lý',
        'da_lien_he' => 'Đã liên hệ',
        'tu_choi' => 'Từ chối',
    ];
    public const GENDERS = ['nam' => 'Nam', 'nu' => 'Nữ', 'khac' => 'Khác'];
    public const GRADES = ['6', '7', '8', '9', '10', '11', '12'];
    public const RELATIONSHIPS = ['Cha', 'Mẹ', 'Người giám hộ'];
    public const REFERRAL_SOURCES = [
        'Website của trường',
        'Facebook',
        'Bạn bè giới thiệu',
        'Báo chí',
        'Ngày hội tuyển sinh',
        'Khác',
    ];

    protected string $table = 'dang_ky_nhap_hoc';

    public function countByStatus(string $status): int
    {
        $statement = $this->db->prepare("SELECT COUNT(*) FROM `{$this->table}` WHERE trang_thai = ?");
        $statement->execute([$status]);

        return (int) $statement->fetchColumn();
    }

    public function countSince(int $days): int
    {
        $statement = $this->db->prepare(
            "SELECT COUNT(*) FROM `{$this->table}` WHERE ngay_tao >= NOW() - INTERVAL :days DAY"
        );
        $statement->bindValue(':days', max(0, $days), PDO::PARAM_INT);
        $statement->execute();

        return (int) $statement->fetchColumn();
    }

    public function latest(int $limit): array
    {
        $statement = $this->db->prepare(
            "SELECT registration.id, registration.ho_ten, registration.email, registration.dien_thoai,
                    registration.khoi_lop, registration.trang_thai, registration.ngay_tao,
                    program.ten AS ten_chuong_trinh
             FROM `{$this->table}` AS registration
             LEFT JOIN chuong_trinh AS program ON program.id = registration.chuong_trinh_id
             ORDER BY registration.ngay_tao DESC, registration.id DESC
             LIMIT :limit"
        );
        $statement->bindValue(':limit', max(1, $limit), PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
