<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Upload;
use App\Core\Validator;
use App\Models\Admission;
use App\Models\Program;
use DateTimeImmutable;
use RuntimeException;
use Throwable;

class AdmissionController extends Controller
{
    private const FORM_PATH = '/dang-ky-nhap-hoc';
    private const SUCCESS_PATH = '/dang-ky-nhap-hoc/thanh-cong';
    private const TRANSCRIPT_GROUP = 'hoc-ba';
    private const MIN_BIRTH_DATE = '1990-01-01';

    private const LABELS = [
        'ho_ten' => 'Họ tên học sinh',
        'ngay_sinh' => 'Ngày sinh',
        'gioi_tinh' => 'Giới tính',
        'quoc_tich' => 'Quốc tịch',
        'ten_phu_huynh' => 'Họ tên phụ huynh',
        'quan_he' => 'Quan hệ với học sinh',
        'email' => 'Email',
        'dien_thoai' => 'Số điện thoại',
        'truong_hien_tai' => 'Trường đang học',
        'khoi_lop' => 'Khối lớp',
        'chuong_trinh_id' => 'Chương trình đăng ký',
        'tep_hoc_ba' => 'Tệp học bạ',
        'nguon_biet_den' => 'Nguồn biết đến trường',
        'ghi_chu' => 'Ghi chú',
    ];

    public function create(): void
    {
        $this->view('public/admission', [
            'title' => 'Đăng ký nhập học',
            'programOptions' => (new Program())->allActive(),
            'genderOptions' => Admission::GENDERS,
            'gradeOptions' => Admission::GRADES,
            'relationshipOptions' => Admission::RELATIONSHIPS,
            'referralSourceOptions' => Admission::REFERRAL_SOURCES,
        ]);
    }

    public function store(): void
    {
        Csrf::check();

        $input = $this->admissionInput();
        $errors = $this->validationErrors($input);
        if ($errors !== []) {
            $this->withErrors($errors, $input, self::FORM_PATH);
        }

        try {
            $transcriptPath = Upload::document($_FILES['tep_hoc_ba'] ?? null, self::TRANSCRIPT_GROUP);
        } catch (RuntimeException $exception) {
            $this->withErrors(['tep_hoc_ba' => $exception->getMessage()], $input, self::FORM_PATH);
        }

        $admissionData = array_map(
            fn (string $value): ?string => $value === '' ? null : $value,
            $input
        );
        $admissionData['chuong_trinh_id'] = $input['chuong_trinh_id'] === '' ? null : (int) $input['chuong_trinh_id'];
        $admissionData['tep_hoc_ba'] = $transcriptPath;
        $admissionData['trang_thai'] = 'moi';

        try {
            (new Admission())->create($admissionData);
        } catch (Throwable $exception) {
            Upload::delete($transcriptPath);
            throw $exception;
        }

        $this->flash('success', 'Đã gửi phiếu đăng ký nhập học cho học sinh ' . $input['ho_ten'] . '.');
        $this->redirect(self::SUCCESS_PATH);
    }

    public function success(): void
    {
        $this->view('public/admission-success', [
            'title' => 'Đăng ký thành công',
            'phoneNumber' => setting('dien_thoai'),
            'emailAddress' => setting('email'),
        ]);
    }

    private function admissionInput(): array
    {
        $input = [
            'ho_ten' => $this->post('ho_ten'),
            'ngay_sinh' => $this->post('ngay_sinh'),
            'gioi_tinh' => $this->post('gioi_tinh'),
            'quoc_tich' => $this->post('quoc_tich'),
            'ten_phu_huynh' => $this->post('ten_phu_huynh'),
            'quan_he' => $this->post('quan_he'),
            'email' => $this->post('email'),
            'dien_thoai' => $this->post('dien_thoai'),
            'truong_hien_tai' => $this->post('truong_hien_tai'),
            'khoi_lop' => $this->post('khoi_lop'),
            'chuong_trinh_id' => $this->post('chuong_trinh_id'),
            'nguon_biet_den' => $this->post('nguon_biet_den'),
            'ghi_chu' => $this->post('ghi_chu'),
        ];

        $input = array_map(
            fn (mixed $value): string => is_string($value) ? $value : '',
            $input
        );
        $input['dien_thoai'] = $this->normalizePhoneNumber($input['dien_thoai']);

        return $input;
    }

    private function normalizePhoneNumber(string $phoneNumber): string
    {
        return preg_replace('/[\s.\-]+/u', '', $phoneNumber) ?? $phoneNumber;
    }

    private function validationErrors(array $input): array
    {
        $rules = [
            'ho_ten' => 'required|max:100',
            'ngay_sinh' => 'date',
            'gioi_tinh' => 'in:' . implode(',', array_keys(Admission::GENDERS)),
            'quoc_tich' => 'max:100',
            'ten_phu_huynh' => 'max:100',
            'quan_he' => 'max:50|in:' . implode(',', Admission::RELATIONSHIPS),
            'email' => 'required|email|max:150',
            'dien_thoai' => 'required|max:20|phone',
            'truong_hien_tai' => 'max:200',
            'khoi_lop' => 'in:' . implode(',', Admission::GRADES),
            'chuong_trinh_id' => 'numeric',
            'nguon_biet_den' => 'max:100|in:' . implode(',', Admission::REFERRAL_SOURCES),
            'ghi_chu' => 'max:2000',
        ];
        $errors = Validator::make($input, $rules, self::LABELS)->errors();

        if (
            !isset($errors['ngay_sinh'])
            && $input['ngay_sinh'] !== ''
            && !$this->isPlausibleBirthDate($input['ngay_sinh'])
        ) {
            $errors['ngay_sinh'] = self::LABELS['ngay_sinh'] . ' không hợp lệ.';
        }
        if (
            !isset($errors['chuong_trinh_id'])
            && $input['chuong_trinh_id'] !== ''
            && !$this->isSelectableProgram($input['chuong_trinh_id'])
        ) {
            $errors['chuong_trinh_id'] = self::LABELS['chuong_trinh_id'] . ' không hợp lệ.';
        }

        return $errors;
    }

    private function isPlausibleBirthDate(string $birthDate): bool
    {
        $date = DateTimeImmutable::createFromFormat('!Y-m-d', $birthDate);
        if ($date === false || $date->format('Y-m-d') !== $birthDate) {
            return false;
        }

        return $date >= new DateTimeImmutable(self::MIN_BIRTH_DATE) && $date < new DateTimeImmutable('today');
    }

    private function isSelectableProgram(string $programId): bool
    {
        return ctype_digit($programId) && (new Program())->isActive((int) $programId);
    }
}
