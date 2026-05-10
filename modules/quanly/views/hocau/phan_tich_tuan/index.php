<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'CL Nước Tuần — Tháng ' . $thang . '/' . $nam;

function getWeeksPT($m, $y) {
    $first = mktime(0,0,0,$m,1,$y);
    $last  = mktime(0,0,0,$m+1,0,$y);
    $weeks = []; $w = 1; $d = $first;
    while ($d <= $last && $w <= 5) {
        $end = min($d + 6*86400, $last);
        $weeks[$w] = ['start'=>date('Y-m-d',$d),'end'=>date('Y-m-d',$end)];
        $d = $end + 86400; $w++;
    }
    return $weeks;
}
$weeks = getWeeksPT($thang, $nam);

// Map tuan_so => array of records (nhiều bản ghi / tuần)
$tuanMap = [];
foreach ($lichTuan as $r) {
    $tuanMap[$r->tuan_so][] = $r;
}

// Cột bảng — khớp đúng DB
// [field_nt, field_ns, label, qcvn_ns_max, step]
$COLS = [
    ['nt_do_kiem',     'ns_do_kiem',     'Độ kiềm<br><small>Alkalinity<br>CaCO3 mg/L</small>', null,  '0.1'],
    ['nt_do_cung',     'ns_do_cung',     'Độ cứng<br><small>Hardness<br>CaCO3 mg/L</small>',   300,   '0.1'],
    ['nt_clorua',      'ns_clorua',      'Clorua<br><small>Chloride mg/L</small>',               250,   '0.1'],
    ['nt_tss',         'ns_tss',         'TSS<br><small>mg/L</small>',                           null,  '0.01'],
    ['nt_al',          'ns_al',          'Nhôm<br><small>Al mg/L</small>',                       0.2,   '0.001'],
    ['nt_fe',          'ns_fe',          'Sắt<br><small>Fe mg/L</small>',                        0.3,   '0.001'],
    ['nt_mn',          'ns_mn',          'Mangan<br><small>Mn mg/L</small>',                     0.1,   '0.001'],
    ['nt_amoni',       'ns_amoni',       'Amoni<br><small>NH4+ mg/L</small>',                    3.0,   '0.001'],
    ['nt_nitrat',      'ns_nitrat',      'Nitrat<br><small>NO3- mg/L</small>',                   50.0,  '0.001'],
    ['nt_nitrit',      'ns_nitrit',      'Nitrit<br><small>NO2- mg/L</small>',                   3.0,   '0.001'],
    ['nt_sulfat',      'ns_sulfat',      'Sulfat<br><small>SO4 mg/L</small>',                    250,   '0.1'],
    ['nt_permanganat', 'ns_permanganat', 'Pecmanganat',                                          2.0,   '0.001'],
    ['nt_cod',         'ns_cod',         'COD<br><small>mg/L</small>',                           null,  '0.1'],
    ['nt_coliform',    'ns_coliform',    'Coliform<br><small>VK/100ml</small>',                  0,     '1'],
    ['nt_florua',      'ns_florua',      'Florua<br><small>µg/L</small>',                        1.5,   '0.001'],
];
?>
<style>
.pt-wrap{max-width:100%;padding:12px 8px}
.pt-nav{display:flex;gap:8px;flex-wrap:wrap;margin-bottom:14px}
.pt-nav a{padding:6px 14px;border-radius:99px;text-decoration:none;font-size:.82rem;background:#f1f5f9;color:#475569}
.pt-nav a.active{background:#3b82f6;color:#fff}
.pt-card{border:1px solid #e2e8f0;border-radius:10px;padding:14px;margin-bottom:14px;background:#fff}
.pt-title{font-size:.95rem;font-weight:700;color:#1e3a5f;margin-bottom:12px}
.pt-tbl{width:100%;border-collapse:collapse;font-size:.7rem;table-layout:auto}
.pt-tbl th{padding:4px 3px;text-align:center;border:1px solid #cbd5e1;white-space:normal;font-weight:600;line-height:1.2}
.pt-tbl th.h-main{background:#1e3a5f;color:#fff}
.pt-tbl th.h-nt{background:#0369a1;color:#fff}
.pt-tbl th.h-ns{background:#166534;color:#fff}
.pt-tbl td{padding:2px 2px;border:1px solid #e2e8f0;text-align:center;vertical-align:middle}
.pt-tbl td.tuan-cell{background:#f8fafc;font-weight:700;font-size:.72rem;padding:4px 5px;white-space:nowrap;vertical-align:middle}
.pt-tbl td.ngay-cell{background:#f8fafc;padding:2px 3px}
.pt-tbl td.nt-col{background:#f0f9ff}
.pt-tbl td.ns-col{background:#f0fdf4}
.pt-tbl tr.bq-row td{background:#fffbeb;font-weight:700}
.pt-tbl tr.row-2 td.nt-col{background:#e0f2fe}
.pt-tbl tr.row-2 td.ns-col{background:#dcfce7}
.pt-tbl tr.row-3 td.nt-col{background:#bae6fd}
.pt-tbl tr.row-3 td.ns-col{background:#bbf7d0}
.pt-tbl input[type=number]{width:56px;padding:2px 2px;border:1px solid #d1d5db;border-radius:3px;font-size:.7rem;text-align:center;-webkit-appearance:none;background:transparent;box-sizing:border-box}
.pt-tbl input[type=date]{width:96px;padding:2px 2px;border:1px solid #d1d5db;border-radius:3px;font-size:.7rem;background:transparent}
.pt-tbl input:focus{border-color:#3b82f6;outline:none;background:#fff}
.pt-tbl input.bad{border-color:#ef4444!important;background:#fef2f2}
.tbl-wrap{width:100%;overflow-x:auto}
.nguoi-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
.nk-field label{font-size:.78rem;color:#64748b;margin-bottom:3px;display:block}
.nk-field input{width:100%;padding:8px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-size:.9rem;outline:none;box-sizing:border-box}
.nk-field input:focus{border-color:#3b82f6}
.btn-save{width:100%;padding:13px;background:#3b82f6;color:#fff;border:none;border-radius:10px;font-size:1rem;font-weight:600;cursor:pointer;margin-top:8px}
.flash-ok{background:#dcfce7;color:#166534;padding:9px 14px;border-radius:8px;margin-bottom:12px;font-size:.85rem}
</style>

<div class="pt-wrap">

    <div class="pt-nav">
        <?php for ($i=3;$i>=0;$i--):
            $m = (int)date('m') - $i; $y = (int)date('Y');
            if ($m <= 0) { $m += 12; $y--; }
        ?>
        <a href="<?= Url::to(['nhat-ky/phan-tich-tuan','thang'=>$m,'nam'=>$y]) ?>"
           class="<?= ($m==$thang&&$y==$nam)?'active':'' ?>">T<?= $m ?>/<?= $y ?></a>
        <?php endfor; ?>
    </div>

    <?php if (Yii::$app->session->hasFlash('success_tuan')): ?>
    <div class="flash-ok">✓ <?= Yii::$app->session->getFlash('success_tuan') ?></div>
    <?php endif; ?>

    <form method="post" id="form-pt">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <input type="hidden" name="thang" value="<?= $thang ?>">
        <input type="hidden" name="nam"   value="<?= $nam ?>">

        <div class="pt-card">
            <div class="pt-title">
                KẾT QUẢ CHẤT LƯỢNG NƯỚC HÀNG THÁNG (WEEKLY WATER TEST RESULT)
                — Tháng <?= $thang ?> Năm <?= $nam ?>
                <span style="float:right;font-size:.72rem;color:#94a3b8;font-weight:400">BM.01.02</span>
            </div>

            <div class="tbl-wrap">
            <table class="pt-tbl">
                <thead>
                    <tr>
                        <th class="h-main" rowspan="2" style="min-width:70px">Tuần</th>
                        <th class="h-main" rowspan="2" style="min-width:100px">Ngày PT</th>
                        <?php foreach ($COLS as $c): ?>
                        <th class="h-nt"><?= $c[2] ?><br><small>NT</small></th>
                        <th class="h-ns"><?= $c[2] ?><br><small>NS<?= $c[3]!==null?' ≤'.$c[3]:'' ?></small></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($weeks as $tuanSo => $weekInfo):
                    $recs = isset($tuanMap[$tuanSo]) ? $tuanMap[$tuanSo] : [];
                    // Đảm bảo đúng 3 hàng
                    while (count($recs) < 3) $recs[] = null;
                    $recs = array_slice($recs, 0, 3);
                    $s = date('d/m', strtotime($weekInfo['start']));
                    $e = date('d/m', strtotime($weekInfo['end']));
                    // Tính BQ 3 ngày của tuần này
                    $tuanBqSums = []; $tuanBqCnts = [];
                    foreach ($COLS as $ci => $c) {
                        $tuanBqSums[$ci] = [0, 0]; $tuanBqCnts[$ci] = [0, 0];
                    }
                    foreach ($recs as $rec) {
                        if (!$rec) continue;
                        foreach ($COLS as $ci => $c) {
                            list($fnt,$fns) = $c;
                            if ($rec->$fnt !== null) { $tuanBqSums[$ci][0] += $rec->$fnt; $tuanBqCnts[$ci][0]++; }
                            if ($rec->$fns !== null) { $tuanBqSums[$ci][1] += $rec->$fns; $tuanBqCnts[$ci][1]++; }
                        }
                    }
                ?>
                <?php foreach ($recs as $ri => $rec):
                    $rowClass = 'row-' . ($ri + 1);
                ?>
                <tr class="<?= $rowClass ?>">
                    <?php if ($ri === 0): ?>
                    <td class="tuan-cell" rowspan="3">
                        Tuần <?= $tuanSo ?><br>
                        <span style="font-size:.65rem;font-weight:400;color:#94a3b8"><?= $s ?>–<?= $e ?></span>
                    </td>
                    <?php endif; ?>
                    <td class="ngay-cell">
                        <input type="date"
                               name="rows[<?= $tuanSo ?>][<?= $ri ?>][ngay_pt]"
                               value="<?= Html::encode($rec !== null ? $rec->ngay_pt : '') ?>" />
                        <?php if ($rec !== null): ?>
                        <input type="hidden" name="rows[<?= $tuanSo ?>][<?= $ri ?>][id]"
                               value="<?= $rec->id ?>" />
                        <?php endif; ?>
                    </td>
                    <?php foreach ($COLS as $ci => $c):
                        list($fnt,$fns,$label,$qc,$step) = $c;
                        $vnt = ($rec !== null) ? $rec->$fnt : null;
                        $vns = ($rec !== null) ? $rec->$fns : null;
                        $badNs = ($qc !== null && $vns !== null && ($qc == 0 ? (float)$vns > 0 : (float)$vns > $qc));
                    ?>
                    <td class="nt-col">
                        <input type="number"
                               name="rows[<?= $tuanSo ?>][<?= $ri ?>][<?= $fnt ?>]"
                               value="<?= $vnt !== null ? Html::encode($vnt) : '' ?>"
                               step="<?= $step ?>" inputmode="decimal" />
                    </td>
                    <td class="ns-col">
                        <input type="number"
                               name="rows[<?= $tuanSo ?>][<?= $ri ?>][<?= $fns ?>]"
                               value="<?= $vns !== null ? Html::encode($vns) : '' ?>"
                               step="<?= $step ?>" inputmode="decimal"
                               class="<?= $badNs ? 'bad' : '' ?>"
                               onchange="chkQc(this,<?= json_encode($qc) ?>)" />
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <!-- BQ tuần -->
                <tr class="bq-row">
                    <td colspan="2" style="font-size:.68rem;background:#fffbeb">TB Tuần <?= $tuanSo ?></td>
                    <?php foreach ($COLS as $ci => $c):
                        $bqNt = $tuanBqCnts[$ci][0] > 0 ? round($tuanBqSums[$ci][0]/$tuanBqCnts[$ci][0],3) : null;
                        $bqNs = $tuanBqCnts[$ci][1] > 0 ? round($tuanBqSums[$ci][1]/$tuanBqCnts[$ci][1],3) : null;
                    ?>
                    <td class="nt-col"><?= $bqNt !== null ? $bqNt : '—' ?></td>
                    <td class="ns-col"><?= $bqNs !== null ? $bqNs : '—' ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
                <!-- BQ tháng -->
                <?php
                // Tính BQ tổng tháng
                $monthSums = []; $monthCnts = [];
                foreach ($COLS as $ci => $c) {
                    $monthSums[$ci] = [0,0]; $monthCnts[$ci] = [0,0];
                }
                foreach ($lichTuan as $r) {
                    foreach ($COLS as $ci => $c) {
                        list($fnt,$fns) = $c;
                        if ($r->$fnt !== null) { $monthSums[$ci][0] += $r->$fnt; $monthCnts[$ci][0]++; }
                        if ($r->$fns !== null) { $monthSums[$ci][1] += $r->$fns; $monthCnts[$ci][1]++; }
                    }
                }
                ?>
                <tr class="bq-row" style="border-top:2px solid #cbd5e1">
                    <td colspan="2" style="font-weight:700;font-size:.75rem;background:#fef9c3">
                        TB Tháng <?= $thang ?>
                    </td>
                    <?php foreach ($COLS as $ci => $c):
                        $bqNt = $monthCnts[$ci][0] > 0 ? round($monthSums[$ci][0]/$monthCnts[$ci][0],3) : null;
                        $bqNs = $monthCnts[$ci][1] > 0 ? round($monthSums[$ci][1]/$monthCnts[$ci][1],3) : null;
                    ?>
                    <td class="nt-col"><?= $bqNt !== null ? $bqNt : '—' ?></td>
                    <td class="ns-col"><?= $bqNs !== null ? $bqNs : '—' ?></td>
                    <?php endforeach; ?>
                </tr>
                </tbody>
            </table>
            </div>
        </div>

        <div class="pt-card">
            <div style="font-size:.88rem;font-weight:700;color:#334155;margin-bottom:10px;">👤 Người thực hiện</div>
            <div class="nguoi-grid">
                <div class="nk-field">
                    <label>Người thực hiện phân tích</label>
                    <input type="text" name="nguoi_pt"
                           value="<?= Html::encode($nguoi_pt) ?>" placeholder="Họ và tên..." />
                </div>
                <div class="nk-field">
                    <label>Người kiểm tra (Checked by)</label>
                    <input type="text" name="nguoi_kt"
                           value="<?= Html::encode($nguoi_kt) ?>" placeholder="Họ và tên..." />
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">💾 Lưu tháng <?= $thang ?>/<?= $nam ?></button>
    </form>
</div>

<script>
function chkQc(inp, qcMax) {
    inp.classList.remove('bad');
    if (qcMax === null || inp.value === '') return;
    var v = parseFloat(inp.value);
    if (isNaN(v)) return;
    if (qcMax === 0 ? v > 0 : v > qcMax) inp.classList.add('bad');
}
</script>