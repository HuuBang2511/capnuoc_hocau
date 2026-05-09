<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Nhật ký phân tích hàng ngày — ' . date('d/m/Y', strtotime($ngay));
$tenCa    = $ca == 1 ? 'Ca 1: Từ 07h đến 18h' : 'Ca 2: Từ 19h đến 06h';
$gioList  = $ca == 1
    ? [7,8,9,10,11,12,13,14,15,16,17,18]
    : [19,20,21,22,23,0,1,2,3,4,5,6];
$gioLabel = array_map(fn($g) => ($g===0?'24':$g).'h', $gioList);

// Gom dữ liệu đã có theo giờ
$dataByGio = [];
foreach ($lichSu as $r) {
    $h = (int)date('H', strtotime($r->thoi_gian));
    $dataByGio[$h] = $r;
}
// Tính BQ
$bqFields = ['ns_ph','ns_ntu','nt_ph','nt_ntu','nl1_ph','nl1_ntu','nl2_ph','nl2_ntu',
             'clo_du','ns_clo_nong_do','nt_clo_nong_do','nc_clo_cham','pac_cham',
             'ns_do_mau','nt_do_mau'];
$bq = [];
foreach ($bqFields as $f) {
    $sum = 0; $cnt = 0;
    foreach ($gioList as $g) {
        $r = $dataByGio[$g] ?? null;
        if ($r && $r->$f !== null) { $sum += $r->$f; $cnt++; }
    }
    $bq[$f] = $cnt ? round($sum/$cnt, 2) : null;
}

// Jar test của ca này
$jPac  = $jarTest ? $jarTest->getPacLieuArr() : array_fill(0,6,null);
$jNtu  = $jarTest ? $jarTest->getPacNtuArr()  : array_fill(0,6,null);
$jPh   = $jarTest ? $jarTest->getPacPhArr()   : array_fill(0,6,null);
$jMin  = $jarTest ? $jarTest->getMinNtuIndex() : -1;
$jGio  = $jarTest ? date('H:i', strtotime($jarTest->gio_thu)) : ($ca==1?'08:00':'19:00');
$jChon = $jarTest?->lieu_chon;
?>
<style>
.hn-wrap { max-width:100%; padding:12px 8px; }
/* Nav */
.hn-nav  { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
.hn-nav a { padding:6px 14px; border-radius:99px; text-decoration:none; font-size:.82rem;
            background:#f1f5f9; color:#475569; }
.hn-nav a.active { background:#3b82f6; color:#fff; }
.hn-ca-sw { display:flex; gap:8px; margin-bottom:12px; }
.hn-ca-sw a { flex:1; text-align:center; padding:9px; border-radius:10px; font-size:.88rem;
              font-weight:600; text-decoration:none; background:#f1f5f9; color:#475569; }
.hn-ca-sw a.active { background:#1e3a5f; color:#fff; }
/* Header ca */
.hn-ca-header { background:#1e3a5f; color:#fff; border-radius:10px 10px 0 0;
                padding:10px 16px; font-weight:700; font-size:.9rem;
                display:flex; justify-content:space-between; align-items:center; }
.hn-nguoi-truc { font-size:.82rem; opacity:.85; }
/* Bảng chính */
.hn-card { border:1px solid #e2e8f0; border-radius:0 0 10px 10px; overflow-x:auto; margin-bottom:16px; }
.hn-tbl  { width:100%; border-collapse:collapse; font-size:.72rem; }
.hn-tbl th { padding:4px 4px; text-align:center; border:1px solid #cbd5e1;
             white-space:nowrap; font-weight:600; line-height:1.2; }
.hn-tbl th.g0 { background:#1e3a5f; color:#fff; }
.hn-tbl th.g1 { background:#2d6099; color:#fff; }  /* NS */
.hn-tbl th.g2 { background:#0369a1; color:#fff; }  /* NT */
.hn-tbl th.g3 { background:#047857; color:#fff; }  /* NL1 */
.hn-tbl th.g4 { background:#065f46; color:#fff; }  /* NL2 */
.hn-tbl th.g5 { background:#92400e; color:#fff; }  /* Clor dư */
.hn-tbl th.g6 { background:#6b21a8; color:#fff; }  /* Clo/PAC châm */
.hn-tbl th.g7 { background:#be185d; color:#fff; }  /* Độ màu */
.hn-tbl th.g8 { background:#155e75; color:#fff; }  /* Ngoài hồ */
.hn-tbl th.g9 { background:#166534; color:#fff; }  /* Mương/bể */
.hn-tbl td { padding:2px 3px; border:1px solid #e2e8f0; text-align:center; }
.hn-tbl tr.bq-row td { background:#fef9c3; font-weight:700; }
.hn-tbl input[type=number] { width:52px; padding:2px 3px; border:1px solid #d1d5db;
                               border-radius:3px; font-size:.72rem; text-align:center;
                               -webkit-appearance:none; background:transparent; }
.hn-tbl input:focus { border-color:#3b82f6; outline:none; background:#fff; }
.hn-tbl input.bad  { border-color:#ef4444 !important; background:#fef2f2; }
.hn-tbl input.warn { border-color:#f59e0b !important; background:#fffbeb; }
/* Jar test */
.jar-card { border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-bottom:16px; }
.jar-title { font-size:.85rem; font-weight:700; color:#1e3a5f; margin-bottom:10px; }
.jar-tbl { border-collapse:collapse; font-size:.82rem; }
.jar-tbl th { background:#f8fafc; padding:6px 10px; border:1px solid #e2e8f0;
              text-align:center; font-weight:600; }
.jar-tbl td { padding:5px 7px; border:1px solid #e2e8f0; text-align:center; }
.jar-tbl td.row-label { font-weight:600; color:#475569; text-align:left; background:#f8fafc; }
.jar-tbl input[type=number] { width:62px; padding:3px 5px; border:1px solid #d1d5db;
                                border-radius:4px; font-size:.82rem; text-align:center;
                                -webkit-appearance:none; }
.jar-tbl input:focus { border-color:#3b82f6; outline:none; }
.jar-col-active { background:#eff6ff !important; }
.jar-col-active input { border-color:#3b82f6; }
/* Người trực / KT */
.nguoi-card { border:1px solid #e2e8f0; border-radius:10px; padding:14px; margin-bottom:16px; }
.nguoi-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
.nk-field label { font-size:.78rem; color:#64748b; margin-bottom:3px; display:block; }
.nk-field input  { width:100%; padding:8px 10px; border:1.5px solid #e2e8f0; border-radius:7px;
                   font-size:.9rem; outline:none; box-sizing:border-box; }
.nk-field input:focus { border-color:#3b82f6; }
.btn-save { width:100%; padding:13px; background:#3b82f6; color:#fff; border:none;
            border-radius:10px; font-size:1rem; font-weight:600; cursor:pointer; }
.flash-ok { background:#dcfce7; color:#166534; padding:9px 14px; border-radius:8px;
            margin-bottom:12px; font-size:.85rem; }
</style>

<div class="hn-wrap">

    <!-- Điều hướng ngày -->
    <div class="hn-nav">
        <?php for ($i=2;$i>=0;$i--):
            $d = date('Y-m-d', strtotime("-$i days"));
            $lbl = $i==0?'Hôm nay':($i==1?'Hôm qua':date('d/m',strtotime("-$i days")));
        ?>
        <a href="<?= Url::to(['nhat-ky/chat-luong-gio','ngay'=>$d,'ca'=>$ca]) ?>"
           class="<?= $d==$ngay?'active':'' ?>"><?= $lbl ?></a>
        <?php endfor; ?>
    </div>

    <!-- Chọn ca -->
    <div class="hn-ca-sw">
        <a href="<?= Url::to(['nhat-ky/chat-luong-gio','ngay'=>$ngay,'ca'=>1]) ?>"
           class="<?= $ca==1?'active':'' ?>">☀️ Ca 1: 07h–18h</a>
        <a href="<?= Url::to(['nhat-ky/chat-luong-gio','ngay'=>$ngay,'ca'=>2]) ?>"
           class="<?= $ca==2?'active':'' ?>">🌙 Ca 2: 19h–06h</a>
    </div>

    <!-- Header ca -->
    <div class="hn-ca-header">
        <span>NHẬT KÝ PHÂN TÍCH HÀNG NGÀY — <?= $tenCa ?> — <?= date('d/m/Y', strtotime($ngay)) ?></span>
        <span class="hn-nguoi-truc">Người trực: <?= Html::encode($model->nguoi_truc ?? '—') ?></span>
    </div>

    <?php if (Yii::$app->session->hasFlash('success')): ?>
    <div class="flash-ok" style="border-radius:0;">✓ <?= Yii::$app->session->getFlash('success') ?></div>
    <?php endif; ?>

    <form method="post" id="form-hn">
        <?= \yii\helpers\Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <?= Html::hiddenInput('ca_submit', $ca) ?>
        <?= Html::hiddenInput('ngay_submit', $ngay) ?>

        <!-- ── BẢNG THEO GIỜ ── -->
        <div class="hn-card">
        <table class="hn-tbl">
            <thead>
                <tr>
                    <th class="g0" rowspan="3">Giờ</th>
                    <!-- Nước Sạch -->
                    <th class="g1" colspan="2">Nước Sạch<br><small>NS / TW</small></th>
                    <!-- Nước Thô -->
                    <th class="g2" colspan="2">Nước Thô<br><small>NT / RW</small></th>
                    <!-- NL1 -->
                    <th class="g3" colspan="2">Nước Lắng 1<br><small>NL1 Afr sed</small></th>
                    <!-- NL2 -->
                    <th class="g4" colspan="2">Nước Lắng 2<br><small>NL2 Afr sed</small></th>
                    <!-- Clor dư -->
                    <th class="g5" rowspan="3">Clor dư<br>TB/PS<br><small>0.2–1.0<br>mg/L</small></th>
                    <!-- Clo/PAC châm -->
                    <th class="g6" rowspan="2" colspan="4">Clo / PAC châm <small>(ppm / mg/L)</small></th>
                    <!-- Độ màu -->
                    <th class="g7" colspan="2">Độ màu<br><small>Color Pt-Co</small></th>
                    <!-- Ngoài hồ -->
                    <th class="g8" colspan="2">Ngoài hồ</th>
                    <!-- Mương / bể -->
                    <th class="g9" colspan="6">Mương / Bể — Clor dư Residual Cl</th>
                    <!-- PAC tỷ trọng -->
                    <th class="g0" rowspan="3">PAC<br>Pha<br>Tỷ<br>trọng</th>
                </tr>
                <tr>
                    <th class="g1" rowspan="2">pH<br><small>6.5–8.5</small></th>
                    <th class="g1" rowspan="2">NTU<br><small>&lt;0.4</small></th>
                    <th class="g2" rowspan="2">pH</th>
                    <th class="g2" rowspan="2">NTU</th>
                    <th class="g3" rowspan="2">pH</th>
                    <th class="g3" rowspan="2">NTU<br><small>&lt;0.5</small></th>
                    <th class="g4" rowspan="2">pH</th>
                    <th class="g4" rowspan="2">NTU<br><small>&lt;5</small></th>
                    <th class="g7" rowspan="2">NT<br><small>RW</small></th>
                    <th class="g7" rowspan="2">NS<br><small>&lt;15</small></th>
                    <th class="g8" rowspan="2">pH</th>
                    <th class="g8" rowspan="2">NTU</th>
                    <th class="g9" rowspan="2">Mương PƯ<br>(Thu hồi)</th>
                    <th class="g9" rowspan="2">Mương<br>lắng NL1</th>
                    <th class="g9" rowspan="2">Mương<br>PƯ NS</th>
                    <th class="g9" rowspan="2">Đầu<br>bể NS</th>
                    <th class="g9" rowspan="2">Hố xi<br>phông 1<br>NTU</th>
                    <th class="g9" rowspan="2">Hố xi<br>phông 2<br>NTU</th>
                </tr>
                <tr>
                    <!-- Clo/PAC châm — row 3 labels -->
                    <th class="g6">Nước cấp<br>nồng độ<br>clo (ppm)</th>
                    <th class="g6">Nước thô<br>nồng độ<br>clo (ppm)</th>
                    <th class="g6">Nồng độ<br>clo châm<br>NC (ppm)</th>
                    <th class="g6">Nồng độ<br>PAC châm<br>(mg/L)</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $allFields = [
                'ns_ph','ns_ntu','nt_ph','nt_ntu','nl1_ph','nl1_ntu','nl2_ph','nl2_ntu',
                'clo_du',
                'ns_clo_nong_do','nt_clo_nong_do','nc_clo_cham','pac_cham',
                'nt_do_mau','ns_do_mau',
                'ngoai_ho_ph','ngoai_ho_ntu',
                'muong_pu_thu_hoi','muong_lang_nl1','muong_pu_ns','dau_be_ns',
                'ho_xi_phong_1_ntu','ho_xi_phong_2_ntu',
                'pac_ty_trong',
            ];
            $steps = [
                'ns_ph'=>'0.01','ns_ntu'=>'0.001','nt_ph'=>'0.01','nt_ntu'=>'0.1',
                'nl1_ph'=>'0.01','nl1_ntu'=>'0.001','nl2_ph'=>'0.01','nl2_ntu'=>'0.01',
                'clo_du'=>'0.01',
                'ns_clo_nong_do'=>'0.01','nt_clo_nong_do'=>'0.01','nc_clo_cham'=>'0.01','pac_cham'=>'0.1',
                'nt_do_mau'=>'1','ns_do_mau'=>'1',
                'ngoai_ho_ph'=>'0.01','ngoai_ho_ntu'=>'0.01',
                'muong_pu_thu_hoi'=>'0.01','muong_lang_nl1'=>'0.01','muong_pu_ns'=>'0.01','dau_be_ns'=>'0.01',
                'ho_xi_phong_1_ntu'=>'0.001','ho_xi_phong_2_ntu'=>'0.001',
                'pac_ty_trong'=>'0.001',
            ];
            $qcvnFields = ['ns_ph','ns_ntu','clo_du','nl1_ntu','nl2_ntu','ns_do_mau'];

            foreach ($gioList as $idx => $gio):
                $rec = $dataByGio[$gio] ?? null;
                $gLbl = ($gio===0?'24':$gio).'h';
            ?>
            <tr id="row-<?= $gio ?>">
                <td style="font-weight:600;background:#f8fafc;padding:3px 6px;"><?= $gLbl ?></td>
                <?php foreach ($allFields as $f):
                    $v = $rec ? $rec->$f : null;
                    $cls = '';
                    if (in_array($f,$qcvnFields) && $v !== null && isset(NkChatLuongGio::QCVN[$f])) {
                        $q = NkChatLuongGio::QCVN[$f];
                        if ((float)$v < $q['min'] || (float)$v > $q['max']) $cls = 'bad';
                    }
                ?>
                <td>
                    <input type="number"
                           name="rows[<?= $gio ?>][<?= $f ?>]"
                           value="<?= Html::encode($v ?? '') ?>"
                           step="<?= $steps[$f] ?>"
                           inputmode="decimal"
                           class="<?= $cls ?>"
                           onchange="checkVal(this,'<?= $f ?>')" />
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>

            <!-- Hàng BQ -->
            <tr class="bq-row">
                <td style="font-weight:700;background:#fef9c3;padding:3px 6px;">BQ</td>
                <?php foreach ($allFields as $f): ?>
                <td id="bq-<?= $f ?>">
                    <?= $bq[$f] !== null ? $bq[$f] : '—' ?>
                </td>
                <?php endforeach; ?>
            </tr>
            </tbody>
        </table>
        </div>

        <!-- ── JAR TEST ── -->
        <div class="jar-card">
            <div class="jar-title">🧪 Jar Test PAC —
                Giờ: <input type="time" name="jar_gio"
                            value="<?= Html::encode($jGio) ?>"
                            style="border:1px solid #e2e8f0;border-radius:5px;padding:3px 7px;font-size:.82rem;" />
            </div>
            <div style="overflow-x:auto;">
            <table class="jar-tbl">
                <thead>
                    <tr>
                        <th class="row-label" style="width:120px;"></th>
                        <?php for ($i=1;$i<=6;$i++): ?>
                        <th><?= $i ?></th>
                        <?php endfor; ?>
                        <th style="background:#eff6ff;color:#1e40af;">Liều chọn</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $jarRows = [
                    ['pac','PAC (mg/L)','0.1'],
                    ['ntu','Độ đục (NTU)','0.001'],
                    ['ph','pH','0.01'],
                ];
                foreach ($jarRows as [$jkey,$jlabel,$jstep]):
                ?>
                <tr>
                    <td class="row-label"><?= $jlabel ?></td>
                    <?php for ($i=0;$i<6;$i++):
                        $arr = $jkey==='pac'?$jPac:($jkey==='ntu'?$jNtu:$jPh);
                        $isMin = ($jkey==='ntu' && $i===$jMin && $jMin>=0);
                    ?>
                    <td class="<?= $isMin?'jar-col-active':'' ?>" id="jar-cell-<?= $i ?>-<?= $jkey ?>">
                        <input type="number"
                               step="<?= $jstep ?>"
                               name="jar_<?= $jkey ?>[<?= $i ?>]"
                               value="<?= Html::encode($arr[$i] ?? '') ?>"
                               inputmode="decimal"
                               onchange="pickJar()" />
                    </td>
                    <?php endfor; ?>
                    <td style="background:#eff6ff;">
                        <?php if ($jkey==='pac'): ?>
                        <input type="number" step="0.1"
                               name="jar_lieu_chon"
                               id="jar-lieu-chon"
                               value="<?= Html::encode($jChon ?? '') ?>"
                               style="width:60px;padding:3px 5px;border:1.5px solid #3b82f6;border-radius:5px;font-weight:700;color:#3b82f6;"
                               inputmode="decimal" /> mg/L
                        <?php else: ?>
                        <span id="jar-preview-<?= $jkey ?>">—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>
        </div>

        <!-- ── NGƯỜI TRỰC / KIỂM TRA ── -->
        <div class="nguoi-card">
            <div style="font-size:.85rem;font-weight:700;color:#334155;margin-bottom:10px;">👤 Người thực hiện</div>
            <div class="nguoi-grid">
                <div class="nk-field">
                    <label>Người trực ca <?= $ca==1?'sáng':'đêm' ?></label>
                    <input type="text" name="nguoi_truc"
                           value="<?= Html::encode($model->nguoi_truc ?? '') ?>"
                           placeholder="Họ và tên..." />
                </div>
                <div class="nk-field">
                    <label>Người kiểm tra (Checked by)</label>
                    <input type="text" name="nguoi_kt"
                           value="<?= Html::encode($model->nguoi_kt ?? '') ?>"
                           placeholder="Họ và tên..." />
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">💾 Lưu nhật ký phân tích</button>
    </form>
</div>

<script>
const QCVN = {
    ns_ph:   {min:6.5, max:8.5},
    ns_ntu:  {min:0,   max:0.4},
    nl1_ntu: {min:0,   max:0.5},
    nl2_ntu: {min:0,   max:5.0},
    clo_du:  {min:0.2, max:1.0},
    ns_do_mau: {min:0, max:15.0},
};
const GIO_LIST = <?= json_encode($gioList) ?>;
const ALL_FIELDS = <?= json_encode($allFields) ?>;

function checkVal(inp, field) {
    if (!QCVN[field] || inp.value === '') { inp.classList.remove('bad','warn'); return; }
    const v = parseFloat(inp.value);
    const q = QCVN[field];
    inp.classList.remove('bad','warn');
    if (v < q.min || v > q.max) inp.classList.add('bad');
    else {
        const r = q.max - q.min;
        if (r > 0 && (v < q.min + r*0.05 || v > q.max - r*0.05)) inp.classList.add('warn');
    }
    calcBQ();
}

function calcBQ() {
    <?php $bqCalcFields = ['ns_ph','ns_ntu','nt_ph','nt_ntu','nl1_ph','nl1_ntu','nl2_ph','nl2_ntu',
                           'clo_du','ns_clo_nong_do','nt_clo_nong_do','nc_clo_cham','pac_cham',
                           'nt_do_mau','ns_do_mau']; ?>
    const fields = <?= json_encode($bqCalcFields) ?>;
    fields.forEach(f => {
        let sum = 0, cnt = 0;
        GIO_LIST.forEach(g => {
            const inp = document.querySelector(`input[name="rows[${g}][${f}]"]`);
            const v = parseFloat(inp?.value);
            if (!isNaN(v)) { sum += v; cnt++; }
        });
        const el = document.getElementById('bq-' + f);
        if (el) el.textContent = cnt ? (sum/cnt).toFixed(2) : '—';
    });
}

function pickJar() {
    let minNtu = Infinity, minIdx = -1;
    for (let i = 0; i < 6; i++) {
        const inp = document.querySelector(`input[name="jar_ntu[${i}]"]`);
        const v = parseFloat(inp?.value);
        if (!isNaN(v) && v < minNtu) { minNtu = v; minIdx = i; }
    }
    // Highlight
    for (let i = 0; i < 6; i++) {
        ['pac','ntu','ph'].forEach(k => {
            const cell = document.getElementById(`jar-cell-${i}-${k}`);
            if (cell) cell.classList.toggle('jar-col-active', i === minIdx);
        });
    }
    // Set liều chọn từ PAC col được chọn
    if (minIdx >= 0) {
        const pacInp = document.querySelector(`input[name="jar_pac[${minIdx}]"]`);
        const chon   = document.getElementById('jar-lieu-chon');
        if (pacInp?.value && chon) chon.value = pacInp.value;
    }
    // Preview NTU + pH của liều chọn
    if (minIdx >= 0) {
        const ntuInp = document.querySelector(`input[name="jar_ntu[${minIdx}]"]`);
        const phInp  = document.querySelector(`input[name="jar_ph[${minIdx}]"]`);
        const pvNtu  = document.getElementById('jar-preview-ntu');
        const pvPh   = document.getElementById('jar-preview-ph');
        if (pvNtu && ntuInp) pvNtu.textContent = ntuInp.value || '—';
        if (pvPh  && phInp)  pvPh.textContent  = phInp.value  || '—';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    pickJar();
    // Validate màu các ô đã có dữ liệu
    document.querySelectorAll('.hn-tbl input[type=number]').forEach(inp => {
        if (inp.value !== '') checkVal(inp, inp.closest('td')?.previousElementSibling?.querySelector('input')?.dataset?.field || '');
    });
    calcBQ();
});
</script>