<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$tenCa = $ca==1 ? 'Ca Ngày (07h–18h)' : 'Ca Đêm (19h–06h)';
$this->title = 'Sổ giao ca — ' . $tenCa;
?>
<style>
.gc-wrap { max-width:960px; margin:0 auto; padding:16px; }
.gc-card { border:1px solid #e2e8f0; border-radius:12px; padding:20px; margin-bottom:16px; }
.gc-section { font-size:.8rem; font-weight:700; color:#3b82f6; letter-spacing:.05em;
              text-transform:uppercase; margin:16px 0 10px; padding-bottom:6px;
              border-bottom:2px solid #eff6ff; }
.gc-grid  { display:grid; grid-template-columns:1fr 1fr; gap:12px; }
.gc-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:12px; }
.gc-field label { font-size:.8rem; color:#64748b; margin-bottom:4px; display:block; }
.gc-field input, .gc-field textarea, .gc-field select {
    width:100%; padding:10px 12px; border:1.5px solid #e2e8f0;
    border-radius:8px; font-size:.95rem; outline:none; box-sizing:border-box; }
.gc-field input:focus, .gc-field textarea:focus { border-color:#3b82f6; }
.gc-calc { font-size:.85rem; color:#10b981; font-weight:600; margin-top:4px; }
.gc-btn  { width:100%; padding:14px; background:#3b82f6; color:#fff;
           border:none; border-radius:10px; font-size:1rem; font-weight:600;
           cursor:pointer; margin-top:8px; }
.ca-switch { display:flex; gap:8px; margin-bottom:16px; }
.ca-switch a { flex:1; text-align:center; padding:10px; border-radius:10px;
               text-decoration:none; font-weight:600; font-size:.9rem;
               background:#f1f5f9; color:#475569; }
.ca-switch a.active { background:#3b82f6; color:#fff; }
@media(max-width:576px) {
    .gc-grid { grid-template-columns:1fr; }
    .gc-grid3 { grid-template-columns:1fr; }
    .gc-field input { font-size:16px; }
}
</style>

<div class="gc-wrap">
    <div class="ca-switch">
        <a href="<?= Url::to(['nhat-ky/giao-ca','ngay'=>$ngay,'ca'=>1]) ?>"
           class="<?= $ca==1?'active':'' ?>">☀️ Ca Ngày</a>
        <a href="<?= Url::to(['nhat-ky/giao-ca','ngay'=>$ngay,'ca'=>2]) ?>"
           class="<?= $ca==2?'active':'' ?>">🌙 Ca Đêm</a>
    </div>

    <div class="gc-card">
        <div style="font-size:1rem;font-weight:700;margin-bottom:16px;">
            📋 Sổ giao ca — <?= date('d/m/Y', strtotime($ngay)) ?> — <?= $tenCa ?>
        </div>

        <?php $form = ActiveForm::begin(['enableClientValidation'=>false]) ?>
        <?= Html::hiddenInput('NkGiaoCa[ngay]', $ngay) ?>
        <?= Html::hiddenInput('NkGiaoCa[ca]', $ca) ?>

        <!-- Khối lượng nước -->
        <div class="gc-section">1. Khối lượng nước (m³)</div>
        <div class="gc-grid" style="margin-bottom:8px;">
            <?php foreach ([
                ['nuoc_cap_dau', 'Nước cấp — Đầu ca'],
                ['nuoc_cap_cuoi','Nước cấp — Cuối ca'],
                ['nuoc_tho_dau', 'Nước thô — Đầu ca'],
                ['nuoc_tho_cuoi','Nước thô — Cuối ca'],
            ] as [$f,$lb]): ?>
            <div class="gc-field">
                <label><?= $lb ?></label>
                <input type="number" name="NkGiaoCa[<?= $f ?>]"
                       value="<?= Html::encode($model->$f) ?>"
                       step="0.1" inputmode="decimal"
                       onchange="calcTong()" id="<?= $f ?>" />
            </div>
            <?php endforeach; ?>
        </div>
        <div class="gc-calc" id="calc-nuoc"></div>

        <!-- Thiết bị hoạt động -->
        <div class="gc-section">2. Thiết bị hoạt động</div>
        <div class="gc-grid">
            <?php foreach ([
                ['bom_nt_chay', 'Bơm NT (A/B/C/D)', 'VD: A,C'],
                ['bom_th_chay', 'Bơm TH (A/B)',      'VD: A'],
                ['bom_khi_chay','Bơm khí (A/B)',     'VD: A,B'],
            ] as [$f,$lb,$ph]): ?>
            <div class="gc-field">
                <label><?= $lb ?></label>
                <input type="text" name="NkGiaoCa[<?= $f ?>]"
                       value="<?= Html::encode($model->$f) ?>"
                       placeholder="<?= $ph ?>" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Điện -->
        <div class="gc-section">3. Điện (KWh)</div>
        <div class="gc-grid">
            <?php foreach ([
                ['dien_nha_may_dau',   'Nhà máy — Đầu ca'],
                ['dien_nha_may_cuoi',  'Nhà máy — Cuối ca'],
                ['dien_tram_bom_dau',  'Trạm bơm — Đầu ca'],
                ['dien_tram_bom_cuoi', 'Trạm bơm — Cuối ca'],
            ] as [$f,$lb]): ?>
            <div class="gc-field">
                <label><?= $lb ?></label>
                <input type="number" name="NkGiaoCa[<?= $f ?>]"
                       value="<?= Html::encode($model->$f) ?>"
                       step="1" inputmode="numeric"
                       onchange="calcDien()" id="<?= $f ?>" />
            </div>
            <?php endforeach; ?>
        </div>
        <div class="gc-calc" id="calc-dien"></div>

        <!-- Kiểm tra CLN đầu/cuối ca -->
        <div class="gc-section">4. Kiểm tra chất lượng nước</div>
        <div class="gc-grid">
            <?php
            $qfields = [
                ['ns_ph_dau','pH NS đầu ca','0.01'],['ns_ntu_dau','NTU NS đầu ca','0.001'],
                ['clo_du_dau','Clo dư đầu ca','0.01'],
                ['ns_ph_cuoi','pH NS cuối ca','0.01'],['ns_ntu_cuoi','NTU NS cuối ca','0.001'],
                ['clo_du_cuoi','Clo dư cuối ca','0.01'],
            ];
            foreach ($qfields as [$f,$lb,$st]):
            ?>
            <div class="gc-field">
                <label><?= $lb ?></label>
                <input type="number" name="NkGiaoCa[<?= $f ?>]"
                       value="<?= Html::encode($model->$f) ?>"
                       step="<?= $st ?>" inputmode="decimal" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Hóa chất -->
        <div class="gc-section">5. Hóa chất sử dụng</div>
        <div class="gc-grid3">
            <?php foreach ([
                ['pac_kg','PAC (kg)','0.01'],
                ['chlorine_kg','Chlorine (kg)','0.01'],
                ['polymer_kg','Polymer (kg)','0.001'],
            ] as [$f,$lb,$st]): ?>
            <div class="gc-field">
                <label><?= $lb ?></label>
                <input type="number" name="NkGiaoCa[<?= $f ?>]"
                       value="<?= Html::encode($model->$f) ?>"
                       step="<?= $st ?>" inputmode="decimal" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Sự cố -->
        <div class="gc-section">6. Sự cố &amp; Giao ca</div>
        <?php foreach ([
            ['su_co','Sự cố trong ca (để trống nếu không có)'],
            ['bien_phap','Biện pháp xử lý'],
            ['ghi_chu','Ghi chú bàn giao'],
        ] as [$f,$lb]): ?>
        <div class="gc-field" style="margin-bottom:10px;">
            <label><?= $lb ?></label>
            <textarea name="NkGiaoCa[<?= $f ?>]" rows="2"
                      style="resize:vertical"><?= Html::encode($model->$f) ?></textarea>
        </div>
        <?php endforeach; ?>

        <div class="gc-grid">
            <div class="gc-field">
                <label>Nhân viên giao ca</label>
                <input type="text" name="NkGiaoCa[nhan_vien_giao]"
                       value="<?= Html::encode($model->nhan_vien_giao) ?>" />
            </div>
            <div class="gc-field">
                <label>Nhân viên nhận ca</label>
                <input type="text" name="NkGiaoCa[nhan_vien_nhan]"
                       value="<?= Html::encode($model->nhan_vien_nhan) ?>" />
            </div>
        </div>

        <button type="submit" class="gc-btn">💾 Lưu sổ giao ca</button>
        <?php ActiveForm::end() ?>
    </div>
</div>

<script>
function calcTong() {
    const dau  = parseFloat(document.getElementById('nuoc_cap_dau')?.value)  || 0;
    const cuoi = parseFloat(document.getElementById('nuoc_cap_cuoi')?.value) || 0;
    const el   = document.getElementById('calc-nuoc');
    if (cuoi > dau && cuoi > 0) {
        el.textContent = '→ Sản lượng nước cấp trong ca: ' + (cuoi - dau).toFixed(1) + ' m³';
    } else el.textContent = '';
}
function calcDien() {
    const nm_d = parseFloat(document.getElementById('dien_nha_may_dau')?.value)   || 0;
    const nm_c = parseFloat(document.getElementById('dien_nha_may_cuoi')?.value)  || 0;
    const tb_d = parseFloat(document.getElementById('dien_tram_bom_dau')?.value)  || 0;
    const tb_c = parseFloat(document.getElementById('dien_tram_bom_cuoi')?.value) || 0;
    const el   = document.getElementById('calc-dien');
    const nm = nm_c - nm_d, tb = tb_c - tb_d;
    if (nm > 0 || tb > 0) {
        el.textContent = '→ Điện nhà máy: ' + nm + ' KWh | Trạm bơm: ' + tb + ' KWh | Tổng: ' + (nm+tb) + ' KWh';
    } else el.textContent = '';
}
// Tính khi load trang (nếu đã có data)
calcTong(); calcDien();
</script>
