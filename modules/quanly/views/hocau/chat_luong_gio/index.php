<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Nhật ký chất lượng nước';
$tenCa = $model->ca == 1 ? 'Ca Ngày (7h–18h)' : 'Ca Đêm (19h–6h)';
?>

<style>
.nk-wrap { max-width:960px; margin:0 auto; padding:16px; }
.nk-card { background:var(--bs-body-bg,#fff); border:1px solid #e2e8f0;
           border-radius:12px; padding:20px; margin-bottom:16px; }
.nk-title { font-size:1rem; font-weight:600; color:#334155; margin-bottom:16px;
            display:flex; align-items:center; gap:8px; }
.nk-section-label { font-size:.78rem; font-weight:600; color:#475569;
                    margin:12px 0 8px; padding:4px 8px; background:#f1f5f9;
                    border-radius:6px; border-left:3px solid #3b82f6; }
.nk-grid  { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.nk-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
.nk-field label { font-size:.78rem; color:#64748b; margin-bottom:3px; display:block; }
.nk-field input { width:100%; padding:9px 11px; border:1.5px solid #e2e8f0;
                  border-radius:8px; font-size:.95rem; outline:none;
                  -webkit-appearance:none; background:#fff; }
.nk-field input:focus { border-color:#3b82f6; }
.nk-field input.val-ok   { border-color:#22c55e; }
.nk-field input.val-warn { border-color:#f59e0b; background:#fffbeb; }
.nk-field input.val-bad  { border-color:#ef4444; background:#fef2f2; }
.nk-hint { font-size:.7rem; color:#94a3b8; margin-top:2px; min-height:14px; }
.nk-hint.bad  { color:#ef4444; }
.nk-hint.warn { color:#f59e0b; }
.nk-btn { width:100%; padding:13px; background:#3b82f6; color:#fff; border:none;
          border-radius:10px; font-size:1rem; font-weight:600; cursor:pointer; margin-top:8px; }
.nk-btn:active { background:#2563eb; }
/* Accordion mở rộng */
.nk-accordion summary { cursor:pointer; font-size:.82rem; color:#6366f1;
                         font-weight:600; padding:6px 0; user-select:none; }
.nk-accordion summary:hover { color:#4f46e5; }
.nk-accordion[open] summary { color:#4f46e5; }
/* Table lịch sử */
.nk-table { width:100%; border-collapse:collapse; font-size:.78rem; }
.nk-table th { background:#f8fafc; padding:7px 8px; text-align:center;
               border-bottom:2px solid #e2e8f0; white-space:nowrap; }
.nk-table td { padding:7px 8px; border-bottom:1px solid #f1f5f9; text-align:center; }
.nk-badge { display:inline-block; padding:1px 7px; border-radius:99px; font-size:.72rem; }
.nk-badge.ok   { background:#dcfce7; color:#166534; }
.nk-badge.warn { background:#fef9c3; color:#854d0e; }
.nk-badge.bad  { background:#fee2e2; color:#991b1b; }
.nav-tabs-day { display:flex; gap:8px; margin-bottom:16px; flex-wrap:wrap; }
.nav-tabs-day a { padding:6px 16px; border-radius:99px; text-decoration:none;
                  font-size:.83rem; background:#f1f5f9; color:#475569; }
.nav-tabs-day a.active { background:#3b82f6; color:#fff; }
@media(max-width:576px){
    .nk-grid,.nk-grid3 { grid-template-columns:1fr; }
    .nk-field input { font-size:16px; }
}
</style>

<div class="nk-wrap">

    <!-- Điều hướng ngày -->
    <div class="nav-tabs-day">
        <?php for ($i=2;$i>=0;$i--):
            $d = date('Y-m-d', strtotime("-$i days"));
            $label = $i==0?'Hôm nay':($i==1?'Hôm qua':date('d/m',strtotime("-$i days")));
        ?>
        <a href="<?= Url::to(['nhat-ky/chat-luong-gio','ngay'=>$d]) ?>"
           class="<?= $d==$ngay?'active':'' ?>"><?= $label ?></a>
        <?php endfor; ?>
    </div>

    <!-- Form nhập liệu -->
    <div class="nk-card">
        <div class="nk-title">
            📋 Nhập CLN — <?= date('d/m/Y',strtotime($ngay)) ?> — <?= $tenCa ?>
        </div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div style="background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:.88rem;">
            ✓ <?= Yii::$app->session->getFlash('success') ?>
        </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['id'=>'form-cln','enableClientValidation'=>false]) ?>
        <?= Html::hiddenInput('NkChatLuongGio[thoi_gian]', $model->thoi_gian) ?>
        <?= Html::hiddenInput('NkChatLuongGio[ca]', $model->ca) ?>

        <!-- ── NƯỚC SẠCH + CLO DƯ ── -->
        <div class="nk-section-label">💧 Nước sạch (NS) &amp; Clo dư</div>
        <div class="nk-grid3" style="margin-bottom:12px;">
            <?php
            $fields_ns = [
                ['ns_ph',  'pH nước sạch',   '6.5–8.5',  '0.01'],
                ['ns_ntu', 'Độ đục NS (NTU)', '< 2.0',    '0.001'],
                ['clo_du', 'Clo dư (mg/L)',   '0.2–1.0',  '0.01'],
            ];
            foreach ($fields_ns as $f):
                $status = $model->getStatus($f[0]);
            ?>
            <div class="nk-field">
                <label><?= $f[1] ?> <span style="color:#94a3b8;font-weight:400;"><?= $f[2] ?></span></label>
                <input type="number"
                       name="NkChatLuongGio[<?= $f[0] ?>]"
                       value="<?= Html::encode($model->{$f[0]}) ?>"
                       class="val-<?= $status ?>"
                       step="<?= $f[3] ?>"
                       inputmode="decimal"
                       onchange="checkVal(this,'<?= $f[0] ?>')" />
                <div class="nk-hint <?= $status ?>" id="hint-<?= $f[0] ?>">
                    <?= $status=='bad'?'⚠ Vượt ngưỡng QCVN':($status=='warn'?'Gần ngưỡng':'') ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── NƯỚC THÔ ── -->
        <div class="nk-section-label">🌊 Nước thô (NT)</div>
        <div class="nk-grid" style="margin-bottom:12px;">
            <?php
            // BUG FIX #4: đảm bảo field name + label khớp chính xác
            $fields_nt = [
                ['nt_ph',  'pH nước thô',      '',       '0.01'],
                ['nt_ntu', 'Độ đục NT (NTU)',   '',       '0.1'],
            ];
            foreach ($fields_nt as $f):
            ?>
            <div class="nk-field">
                <label><?= $f[1] ?><?= $f[2]?" <span style='color:#94a3b8'>$f[2]</span>":'' ?></label>
                <input type="number"
                       name="NkChatLuongGio[<?= $f[0] ?>]"
                       value="<?= Html::encode($model->{$f[0]}) ?>"
                       step="<?= $f[3] ?>"
                       inputmode="decimal" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── BỂ LẮNG ── -->
        <div class="nk-section-label">🔵 Bể lắng</div>
        <div class="nk-grid" style="margin-bottom:12px;">
            <?php
            $fields_lang = [
                ['nl1_ph',  'pH bể lắng 1',    '',      '0.01'],
                ['nl1_ntu', 'NTU bể lắng 1',   '< 0.5', '0.001'],
                ['nl2_ph',  'pH bể lắng 2',    '',      '0.01'],
                ['nl2_ntu', 'NTU bể lắng 2',   '< 5',   '0.01'],
            ];
            foreach ($fields_lang as $f):
            ?>
            <div class="nk-field">
                <label><?= $f[1] ?><?= $f[2]?" <span style='color:#94a3b8'>$f[2]</span>":'' ?></label>
                <input type="number"
                       name="NkChatLuongGio[<?= $f[0] ?>]"
                       value="<?= Html::encode($model->{$f[0]}) ?>"
                       step="<?= $f[3] ?>"
                       inputmode="decimal" />
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ── MỞ RỘNG (Accordion) ── -->
        <details class="nk-accordion" style="margin-bottom:12px;">
            <summary>▸ Dữ liệu mở rộng (Nước ngoài hồ, Mương phản ứng, Hố xi phông, PAC)</summary>
            <div style="margin-top:10px;">

                <div class="nk-section-label" style="margin-top:0;">🏞 Nước ngoài hồ</div>
                <div class="nk-grid" style="margin-bottom:10px;">
                    <div class="nk-field">
                        <label>pH nước ngoài hồ</label>
                        <input type="number" name="NkChatLuongGio[ngoai_ho_ph]"
                               value="<?= Html::encode($model->ngoai_ho_ph) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>Độ đục ngoài hồ (NTU)</label>
                        <input type="number" name="NkChatLuongGio[ngoai_ho_ntu]"
                               value="<?= Html::encode($model->ngoai_ho_ntu) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                </div>

                <div class="nk-section-label">🧪 Clor dư các vị trí (mg/L)</div>
                <div class="nk-grid" style="margin-bottom:10px;">
                    <div class="nk-field">
                        <label>Mương PƯ (thu hồi)</label>
                        <input type="number" name="NkChatLuongGio[muong_pu_thu_hoi]"
                               value="<?= Html::encode($model->muong_pu_thu_hoi) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>Mương lắng NL1</label>
                        <input type="number" name="NkChatLuongGio[muong_lang_nl1]"
                               value="<?= Html::encode($model->muong_lang_nl1) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>Mương PƯ NS</label>
                        <input type="number" name="NkChatLuongGio[muong_pu_ns]"
                               value="<?= Html::encode($model->muong_pu_ns) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>Đầu bể NS</label>
                        <input type="number" name="NkChatLuongGio[dau_be_ns]"
                               value="<?= Html::encode($model->dau_be_ns) ?>"
                               step="0.01" inputmode="decimal" />
                    </div>
                </div>

                <div class="nk-section-label">📍 Hố xi phông &amp; PAC</div>
                <div class="nk-grid3" style="margin-bottom:6px;">
                    <div class="nk-field">
                        <label>Hố xi phông 1 (NTU)</label>
                        <input type="number" name="NkChatLuongGio[ho_xi_phong_1_ntu]"
                               value="<?= Html::encode($model->ho_xi_phong_1_ntu) ?>"
                               step="0.001" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>Hố xi phông 2 (NTU)</label>
                        <input type="number" name="NkChatLuongGio[ho_xi_phong_2_ntu]"
                               value="<?= Html::encode($model->ho_xi_phong_2_ntu) ?>"
                               step="0.001" inputmode="decimal" />
                    </div>
                    <div class="nk-field">
                        <label>PAC pha tỷ trọng</label>
                        <input type="number" name="NkChatLuongGio[pac_ty_trong]"
                               value="<?= Html::encode($model->pac_ty_trong) ?>"
                               step="0.001" inputmode="decimal" />
                    </div>
                </div>
            </div>
        </details>

        <!-- Ghi chú -->
        <div class="nk-field" style="margin-bottom:12px;">
            <label>Ghi chú</label>
            <input type="text" name="NkChatLuongGio[ghi_chu]"
                   value="<?= Html::encode($model->ghi_chu) ?>"
                   placeholder="Ghi chú nếu có..." />
        </div>

        <button type="submit" class="nk-btn">💾 Lưu chất lượng nước</button>
        <?php ActiveForm::end() ?>
    </div>

    <!-- Lịch sử trong ngày -->
    <?php if ($lichSu): ?>
    <div class="nk-card">
        <div class="nk-title">📊 Kết quả ngày <?= date('d/m/Y',strtotime($ngay)) ?></div>
        <div style="overflow-x:auto;">
        <table class="nk-table">
            <thead>
                <tr>
                    <th>Giờ</th><th>Ca</th>
                    <th>NS pH</th><th>NS NTU</th>
                    <th>NT pH</th><th>NT NTU</th>
                    <th>Lắng 1 pH</th><th>Lắng 1 NTU</th>
                    <th>Lắng 2 pH</th><th>Lắng 2 NTU</th>
                    <th>Clo dư</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lichSu as $r):
                $st_ph  = $r->getStatus('ns_ph');
                $st_ntu = $r->getStatus('ns_ntu');
                $st_clo = $r->getStatus('clo_du');
            ?>
                <tr>
                    <td><?= date('H:i',strtotime($r->thoi_gian)) ?></td>
                    <td><?= $r->ca==1?'Ngày':'Đêm' ?></td>
                    <td><span class="nk-badge <?= $st_ph ?>"><?= $r->ns_ph ?? '—' ?></span></td>
                    <td><span class="nk-badge <?= $st_ntu ?>"><?= $r->ns_ntu ?? '—' ?></span></td>
                    <td><?= $r->nt_ph ?? '—' ?></td>
                    <td><?= $r->nt_ntu ?? '—' ?></td>
                    <td><?= $r->nl1_ph ?? '—' ?></td>
                    <td><?= $r->nl1_ntu ?? '—' ?></td>
                    <td><?= $r->nl2_ph ?? '—' ?></td>
                    <td><?= $r->nl2_ntu ?? '—' ?></td>
                    <td><span class="nk-badge <?= $st_clo ?>"><?= $r->clo_du ?? '—' ?></span></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const QCVN = <?= json_encode($QCVN) ?>;
function checkVal(input, field) {
    if (!QCVN[field]) return;
    const v = parseFloat(input.value);
    const q = QCVN[field];
    const hint = document.getElementById('hint-' + field);
    input.className = '';
    if (hint) hint.className = 'nk-hint';
    if (isNaN(v)) return;
    if (v < q.min || v > q.max) {
        input.classList.add('val-bad');
        if (hint) { hint.classList.add('bad'); hint.textContent = '⚠ Vượt ngưỡng QCVN (' + q.min + '–' + q.max + ')'; }
    } else {
        const range = q.max - q.min;
        if (range > 0 && (v < q.min + range*0.05 || v > q.max - range*0.05)) {
            input.classList.add('val-warn');
            if (hint) { hint.classList.add('warn'); hint.textContent = 'Gần ngưỡng — kiểm tra lại'; }
        } else {
            input.classList.add('val-ok');
            if (hint) hint.textContent = '';
        }
    }
}
</script>