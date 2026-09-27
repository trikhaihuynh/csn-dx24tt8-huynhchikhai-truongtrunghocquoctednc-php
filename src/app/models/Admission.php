<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Model;

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
}
