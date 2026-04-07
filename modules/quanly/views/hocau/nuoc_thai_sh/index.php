<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Chất lượng nước thải sinh hoạt';
?>
<style>
.nk-wrap { max-width:760px; margin:0 auto; padding:16px; }
.nk-card { background:var(--bs-body-bg,#fff); border:1px solid #e2e8f0;
           border-radius:12px; padding:20px; margin-bottom:16px; }
.nk-title { font-size:1rem; font-weight:600; color:#334155; margin-bottom:4px; }
.nk-subtitle { font-size:.78rem; color:#64748b; margin-bottom:16px; }
.nk-grid2 { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.nk-grid3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; }
.nk-field label { font-size:.78rem; color:#64748b; margin-bottom:3px; display:block; }
.nk-field .qc  { font-size:.7rem; color:#94a3b8; }
.nk-field input { width:100%; padding:9px 11px; border:1.5px solid #e2e8f0;
                  border-radius:8px; font-size:.95rem; outline:none;
                  -webkit-appearance:none; background:#fff; }
.nk-field input:focus { border-color:#3b82f6; }
.nk-field input.val-ok   { border-color:#22c55e; }
.nk-field input.val-warn { border-color:#f59e0b; background:#fffbeb; }
.nk-field input.val-bad  { border-color:#ef4444; background:#fef2f2; }
.nk-hint { font-size:.68rem; margin-top:2px; min-height:13px; color:#94a3b8; }
.nk-hint.bad  { color:#ef4444; }
.nk-hint.warn { color:#f59e0b; }
.nk-btn { width:100%; padding:13px; background:#0ea5e9; color:#fff; border:none;
          border-radius:10px; font-size:1rem; font-weight:600; cursor:pointer; margin-top:8px; }
/* Bảng lịch sử */
.nk-table { width:100%; border-collapse:collapse; font-size:.78rem; }
.nk-table th { background:#f8fafc; padding:7px 9px; text-align:center;
               border-bottom:2px solid #e2e8f0; white-space:nowrap; }
.nk-table td { padding:7px 9px; border-bottom:1px solid #f1f5f9; text-align:center; }
.nk-badge { display:inline-block; padding:1px 8px; border-radius:99px; font-size:.72rem; }
.nk-badge.ok   { background:#dcfce7; color:#166534; }
.nk-badge.warn { background:#fef9c3; color:#854d0e; }
.nk-badge.bad  { background:#fee2e2; color:#991b1b; }
.nk-badge.na   { background:#f1f5f9; color:#94a3b8; }
/* Chọn ngày */
.date-nav { display:flex; align-items:center; gap:10px; margin-bottom:16px; flex-wrap:wrap; }
.date-nav input[type=date] { padding:7px 11px; border:1.5px solid #e2e8f0;
                              border-radius:8px; font-size:.88rem; }
.date-nav button { padding:7px 16px; background:#3b82f6; color:#fff; border:none;
                   border-radius:8px; font-size:.88rem; cursor:pointer; }
@media(max-width:576px){
    .nk-grid2,.nk-grid3 { grid-template-columns:1fr; }
    .nk-field input { font-size:16px; }
}
</style>

<div class="nk-wrap">
    <!-- Điều hướng chọn ngày -->
    <form method="get" class="date-nav">
        <label style="font-size:.83rem;color:#475569;font-weight:600;">Chọn ngày nhập liệu:</label>
        <input type="date" name="ngay" value="<?= Html::encode($ngay) ?>" max="<?= date('Y-m-d') ?>">
        <button type="submit">Xem / Nhập</button>
    </form>

    <!-- Form nhập -->
    <div class="nk-card">
        <div class="nk-title">🧫 Kết quả CLNT sinh hoạt — <?= date('d/m/Y', strtotime($ngay)) ?></div>
        <div class="nk-subtitle">QCVN 14:2008/BTNMT — Cột B</div>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div style="background:#dcfce7;color:#166534;padding:10px 14px;border-radius:8px;margin-bottom:12px;font-size:.88rem;">
            ✓ <?= Yii::$app->session->getFlash('success') ?>
        </div>
        <?php endif; ?>

        <?php $form = ActiveForm::begin(['enableClientValidation'=>false]) ?>
        <?= Html::hiddenInput('NkNuocThaiSh[ngay]', $ngay) ?>

        <div class="nk-grid2" style="margin-bottom:12px;">
            <div class="nk-field">
                <label>pH <span class="qc">(QCVN: 5 – 9)</span></label>
                <input type="number" name="NkNuocThaiSh[ph]"
                       value="<?= Html::encode($model->ph) ?>"
                       step="0.01" inputmode="decimal"
                       onchange="checkNT(this,'ph')" class="val-<?= $model->getStatus('ph') ?>"/>
                <div class="nk-hint <?= $model->getStatus('ph') ?>" id="hint-ph">
                    <?= $model->getStatus('ph')=='bad'?'⚠ Vượt QCVN':'' ?>
                </div>
            </div>
            <div class="nk-field">
                <label>TSS <span class="qc">(≤ 50 mg/L)</span></label>
                <input type="number" name="NkNuocThaiSh[tss]"
                       value="<?= Html::encode($model->tss) ?>"
                       step="0.1" inputmode="decimal"
                       onchange="checkNT(this,'tss')" class="val-<?= $model->getStatus('tss') ?>"/>
                <div class="nk-hint <?= $model->getStatus('tss') ?>" id="hint-tss">
                    <?= $model->getStatus('tss')=='bad'?'⚠ Vượt QCVN':'' ?>
                </div>
            </div>
        </div>

        <div class="nk-grid3" style="margin-bottom:12px;">
            <div class="nk-field">
                <label>Amoni <span class="qc">(≤ 5 mg/L)</span></label>
                <input type="number" name="NkNuocThaiSh[amoni]"
                       value="<?= Html::encode($model->amoni) ?>"
                       step="0.01" inputmode="decimal"
                       onchange="checkNT(this,'amoni')" class="val-<?= $model->getStatus('amoni') ?>"/>
                <div class="nk-hint <?= $model->getStatus('amoni') ?>" id="hint-amoni">
                    <?= $model->getStatus('amoni')=='bad'?'⚠ Vượt QCVN':'' ?>
                </div>
            </div>
            <div class="nk-field">
                <label>Nitrat <span class="qc">(≤ 30 mg/L)</span></label>
                <input type="number" name="NkNuocThaiSh[nitrat]"
                       value="<?= Html::encode($model->nitrat) ?>"
                       step="0.01" inputmode="decimal"
                       onchange="checkNT(this,'nitrat')" class="val-<?= $model->getStatus('nitrat') ?>"/>
                <div class="nk-hint <?= $model->getStatus('nitrat') ?>" id="hint-nitrat">
                    <?= $model->getStatus('nitrat')=='bad'?'⚠ Vượt QCVN':'' ?>
                </div>
            </div>
            <div class="nk-field">
                <label>Coliform <span class="qc">(≤ 3.000 MPN/100mL)</span></label>
                <input type="number" name="NkNuocThaiSh[coliform]"
                       value="<?= Html::encode($model->coliform) ?>"
                       step="1" inputmode="numeric"
                       onchange="checkNT(this,'coliform')" class="val-<?= $model->getStatus('coliform') ?>"/>
                <div class="nk-hint <?= $model->getStatus('coliform') ?>" id="hint-coliform">
                    <?= $model->getStatus('coliform')=='bad'?'⚠ Vượt QCVN':'' ?>
                </div>
            </div>
        </div>

        <div class="nk-field" style="margin-bottom:12px;">
            <label>Ghi chú</label>
            <input type="text" name="NkNuocThaiSh[ghi_chu]"
                   value="<?= Html::encode($model->ghi_chu) ?>"
                   placeholder="Ghi chú, nhận xét..."/>
        </div>

        <button type="submit" class="nk-btn">💾 Lưu kết quả</button>
        <?php ActiveForm::end() ?>
    </div>

    <!-- Lịch sử 90 ngày -->
    <?php if ($lichSu): ?>
    <div class="nk-card">
        <div class="nk-title">📋 Lịch sử 90 ngày gần nhất</div>
        <div style="overflow-x:auto;">
        <table class="nk-table">
            <thead>
                <tr>
                    <th>Ngày</th>
                    <th>pH<br><small style="font-weight:400;color:#94a3b8">(5–9)</small></th>
                    <th>TSS (mg/L)<br><small style="font-weight:400;color:#94a3b8">(≤50)</small></th>
                    <th>Amoni (mg/L)<br><small style="font-weight:400;color:#94a3b8">(≤5)</small></th>
                    <th>Nitrat (mg/L)<br><small style="font-weight:400;color:#94a3b8">(≤30)</small></th>
                    <th>Coliform<br><small style="font-weight:400;color:#94a3b8">(≤3000)</small></th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lichSu as $r): ?>
                <tr>
                    <td style="white-space:nowrap;font-weight:600;">
                        <?= date('d/m/Y', strtotime($r->ngay)) ?>
                    </td>
                    <?php foreach (['ph','tss','amoni','nitrat','coliform'] as $f): ?>
                    <td>
                        <?php if ($r->$f !== null): ?>
                        <span class="nk-badge <?= $r->getStatus($f) ?>"><?= $r->$f ?></span>
                        <?php else: ?>
                        <span class="nk-badge na">—</span>
                        <?php endif; ?>
                    </td>
                    <?php endforeach; ?>
                    <td style="text-align:left;color:#475569;"><?= Html::encode($r->ghi_chu ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
const QCVN_NT = <?= json_encode($QCVN) ?>;
function checkNT(input, field) {
    if (!QCVN_NT[field]) return;
    const v = parseFloat(input.value);
    const q = QCVN_NT[field];
    const hint = document.getElementById('hint-' + field);
    input.className = '';
    if (hint) hint.className = 'nk-hint';
    if (isNaN(v)) return;
    if (v < q.min || v > q.max) {
        input.classList.add('val-bad');
        if (hint) { hint.classList.add('bad'); hint.textContent = '⚠ Vượt QCVN (max ' + q.max + ' ' + q.unit + ')'; }
    } else {
        const range = q.max - q.min;
        if (range > 0 && v > q.max - range * 0.1) {
            input.classList.add('val-warn');
            if (hint) { hint.classList.add('warn'); hint.textContent = 'Gần ngưỡng tối đa'; }
        } else {
            input.classList.add('val-ok');
            if (hint) hint.textContent = '';
        }
    }
}
</script>