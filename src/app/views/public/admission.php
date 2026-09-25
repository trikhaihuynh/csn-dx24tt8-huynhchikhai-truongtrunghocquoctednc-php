<?php
use App\Core\Csrf;
?>
<section class="page-banner">
    <div class="container">
        <h1 class="page-banner__title">Đăng ký nhập học</h1>
        <p class="page-banner__subtitle">Admission Application</p>
    </div>
</section>

<section class="section admission-section">
    <div class="container container--narrow">
        <div class="form-card admission-form-card">
            <p class="admission-intro">Chào mừng phụ huynh và học sinh đến với <?= e(setting('ten_truong', APP_NAME)) ?>. Vui lòng điền thông tin bên dưới để bắt đầu hồ sơ đăng ký nhập học cho năm học tới. Các trường có dấu <span class="required-mark">*</span> là bắt buộc.</p>

            <?php if ($errors): ?>
                <div class="alert alert-error admission-alert" role="alert">Phiếu đăng ký chưa hợp lệ. Vui lòng kiểm tra lại các trường được đánh dấu bên dưới.</div>
            <?php endif; ?>

            <form class="admission-form" method="post" action="<?= e(url('/dang-ky-nhap-hoc')) ?>" enctype="multipart/form-data" novalidate>
                <?= Csrf::field() ?>

                <fieldset class="admission-group">
                    <legend class="form-section-title">Thông tin học sinh</legend>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['ho_ten']) ? ' has-error' : '' ?>">
                            <label for="ho_ten">Họ tên học sinh <span class="required-mark">*</span></label>
                            <input type="text" id="ho_ten" name="ho_ten" value="<?= e(old('ho_ten')) ?>" maxlength="100" placeholder="Ví dụ: Nguyễn Văn An" required>
                            <?php if (isset($errors['ho_ten'])): ?>
                                <p class="form-error"><?= e($errors['ho_ten']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['ngay_sinh']) ? ' has-error' : '' ?>">
                            <label for="ngay_sinh">Ngày sinh</label>
                            <input type="date" id="ngay_sinh" name="ngay_sinh" value="<?= e(old('ngay_sinh')) ?>">
                            <?php if (isset($errors['ngay_sinh'])): ?>
                                <p class="form-error"><?= e($errors['ngay_sinh']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['gioi_tinh']) ? ' has-error' : '' ?>">
                            <span class="form-label">Giới tính</span>
                            <div class="choice-group">
                                <?php foreach ($genderOptions as $genderValue => $genderLabel): ?>
                                    <label class="choice">
                                        <input type="radio" name="gioi_tinh" value="<?= e($genderValue) ?>"<?= old('gioi_tinh') === $genderValue ? ' checked' : '' ?>>
                                        <span><?= e($genderLabel) ?></span>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <?php if (isset($errors['gioi_tinh'])): ?>
                                <p class="form-error"><?= e($errors['gioi_tinh']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['quoc_tich']) ? ' has-error' : '' ?>">
                            <label for="quoc_tich">Quốc tịch</label>
                            <input type="text" id="quoc_tich" name="quoc_tich" value="<?= e(old('quoc_tich')) ?>" maxlength="100" placeholder="Ví dụ: Việt Nam">
                            <?php if (isset($errors['quoc_tich'])): ?>
                                <p class="form-error"><?= e($errors['quoc_tich']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="admission-group">
                    <legend class="form-section-title">Thông tin phụ huynh</legend>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['ten_phu_huynh']) ? ' has-error' : '' ?>">
                            <label for="ten_phu_huynh">Họ tên phụ huynh</label>
                            <input type="text" id="ten_phu_huynh" name="ten_phu_huynh" value="<?= e(old('ten_phu_huynh')) ?>" maxlength="100">
                            <?php if (isset($errors['ten_phu_huynh'])): ?>
                                <p class="form-error"><?= e($errors['ten_phu_huynh']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['quan_he']) ? ' has-error' : '' ?>">
                            <label for="quan_he">Quan hệ với học sinh</label>
                            <select id="quan_he" name="quan_he">
                                <option value="">-- Chọn quan hệ --</option>
                                <?php foreach ($relationshipOptions as $relationship): ?>
                                    <option value="<?= e($relationship) ?>"<?= old('quan_he') === $relationship ? ' selected' : '' ?>><?= e($relationship) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['quan_he'])): ?>
                                <p class="form-error"><?= e($errors['quan_he']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['email']) ? ' has-error' : '' ?>">
                            <label for="email">Email <span class="required-mark">*</span></label>
                            <input type="email" id="email" name="email" value="<?= e(old('email')) ?>" maxlength="150" placeholder="phuhuynh@example.com" required>
                            <?php if (isset($errors['email'])): ?>
                                <p class="form-error"><?= e($errors['email']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['dien_thoai']) ? ' has-error' : '' ?>">
                            <label for="dien_thoai">Số điện thoại <span class="required-mark">*</span></label>
                            <input type="tel" id="dien_thoai" name="dien_thoai" value="<?= e(old('dien_thoai')) ?>" maxlength="20" placeholder="Ví dụ: 0901234567" required>
                            <?php if (isset($errors['dien_thoai'])): ?>
                                <p class="form-error"><?= e($errors['dien_thoai']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="admission-group">
                    <legend class="form-section-title">Học vấn</legend>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['truong_hien_tai']) ? ' has-error' : '' ?>">
                            <label for="truong_hien_tai">Trường đang học</label>
                            <input type="text" id="truong_hien_tai" name="truong_hien_tai" value="<?= e(old('truong_hien_tai')) ?>" maxlength="200">
                            <?php if (isset($errors['truong_hien_tai'])): ?>
                                <p class="form-error"><?= e($errors['truong_hien_tai']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['khoi_lop']) ? ' has-error' : '' ?>">
                            <label for="khoi_lop">Khối lớp đăng ký</label>
                            <select id="khoi_lop" name="khoi_lop">
                                <option value="">-- Chọn khối lớp --</option>
                                <?php foreach ($gradeOptions as $grade): ?>
                                    <option value="<?= e($grade) ?>"<?= old('khoi_lop') === $grade ? ' selected' : '' ?>>Lớp <?= e($grade) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['khoi_lop'])): ?>
                                <p class="form-error"><?= e($errors['khoi_lop']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['chuong_trinh_id']) ? ' has-error' : '' ?>">
                            <label for="chuong_trinh_id">Chương trình đăng ký</label>
                            <select id="chuong_trinh_id" name="chuong_trinh_id">
                                <option value="">-- Chọn chương trình --</option>
                                <?php foreach ($programOptions as $program): ?>
                                    <option value="<?= e($program['id']) ?>"<?= old('chuong_trinh_id') === (string) $program['id'] ? ' selected' : '' ?>><?= e($program['ten']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['chuong_trinh_id'])): ?>
                                <p class="form-error"><?= e($errors['chuong_trinh_id']) ?></p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group<?= isset($errors['tep_hoc_ba']) ? ' has-error' : '' ?>">
                            <label for="tep_hoc_ba">Tải học bạ</label>
                            <input type="file" id="tep_hoc_ba" name="tep_hoc_ba" accept=".pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png">
                            <p class="form-hint">Không bắt buộc. Tệp PDF, JPG hoặc PNG, tối đa 5MB.</p>
                            <?php if (isset($errors['tep_hoc_ba'])): ?>
                                <p class="form-error"><?= e($errors['tep_hoc_ba']) ?></p>
                            <?php elseif ($errors): ?>
                                <p class="form-hint">Nếu đã chọn tệp trước đó, vui lòng chọn lại.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </fieldset>

                <fieldset class="admission-group">
                    <legend class="form-section-title">Thông tin thêm</legend>
                    <div class="form-row">
                        <div class="form-group<?= isset($errors['nguon_biet_den']) ? ' has-error' : '' ?>">
                            <label for="nguon_biet_den">Biết đến trường qua</label>
                            <select id="nguon_biet_den" name="nguon_biet_den">
                                <option value="">-- Chọn nguồn thông tin --</option>
                                <?php foreach ($referralSourceOptions as $referralSource): ?>
                                    <option value="<?= e($referralSource) ?>"<?= old('nguon_biet_den') === $referralSource ? ' selected' : '' ?>><?= e($referralSource) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errors['nguon_biet_den'])): ?>
                                <p class="form-error"><?= e($errors['nguon_biet_den']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group<?= isset($errors['ghi_chu']) ? ' has-error' : '' ?>">
                        <label for="ghi_chu">Ghi chú hoặc câu hỏi thêm</label>
                        <textarea id="ghi_chu" name="ghi_chu" maxlength="2000" rows="4"><?= e(old('ghi_chu')) ?></textarea>
                        <?php if (isset($errors['ghi_chu'])): ?>
                            <p class="form-error"><?= e($errors['ghi_chu']) ?></p>
                        <?php endif; ?>
                    </div>
                </fieldset>

                <div class="form-actions admission-actions">
                    <button type="submit" class="btn btn-success btn-lg">Gửi đăng ký</button>
                    <a class="btn btn-link" href="<?= e(url('/')) ?>">Hủy</a>
                </div>
            </form>
        </div>
    </div>
</section>
