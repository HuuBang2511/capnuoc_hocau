<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Báo cáo nội bộ hàng ngày';
?>
<style>
.bc-wrap { max-width:960px; margin:0 auto; padding:20px 16px; }
.bc-hero  { background:linear-gradient(135deg,#1e3a5f,#2d6099);
            border-radius:16px; padding:28px 24px; margin-bottom:24px; color:#fff; }
.bc-hero h1 { font-size:1.2rem; font-weight:700; margin:0 0 4px; }
.bc-hero p  { font-size:.85rem; opacity:.75; margin:0; }

/* Chọn ngày */
.bc-date-row { display:flex; gap:10px; align-items:center; flex-wrap:wrap;
               margin-bottom:24px; }
.bc-date-row input[type=date] {
    padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px;
    font-size:.95rem; outline:none; background:#fff; }
.bc-date-row input[type=date]:focus { border-color:#3b82f6; }
.bc-btn-primary { padding:10px 20px; background:#3b82f6; color:#fff; border:none;
                  border-radius:10px; font-size:.9rem; font-weight:600;
                  cursor:pointer; text-decoration:none; display:inline-flex;
                  align-items:center; gap:6px; }
.bc-btn-primary:hover { background:#2563eb; color:#fff; }
.bc-btn-outline { padding:10px 20px; border:1.5px solid #e2e8f0; background:#fff;
                  color:#475569; border-radius:10px; font-size:.9rem; font-weight:600;
                  cursor:pointer; text-decoration:none; display:inline-flex;
                  align-items:center; gap:6px; }
.bc-btn-outline:hover { border-color:#3b82f6; color:#3b82f6; }

/* Shortcuts ngày */
.bc-shortcuts { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:24px; }
.bc-shortcut  { padding:6px 14px; border-radius:99px; font-size:.82rem; font-weight:500;
                background:#f1f5f9; color:#475569; cursor:pointer; border:none;
                text-decoration:none; }
.bc-shortcut:hover, .bc-shortcut.active { background:#3b82f6; color:#fff; }

/* Cards tình trạng dữ liệu */
.bc-status-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:12px;
                  margin-bottom:24px; }
.bc-status-card { border:1px solid #e2e8f0; border-radius:12px; padding:16px; }
.bc-status-card .label { font-size:.75rem; color:#94a3b8; margin-bottom:4px; }
.bc-status-card .value { font-size:1rem; font-weight:700; color:#1e3a5f; }
.bc-status-card .sub   { font-size:.78rem; color:#64748b; margin-top:2px; }
.bc-status-card.has-data  { border-left:4px solid #22c55e; }
.bc-status-card.no-data   { border-left:4px solid #f59e0b; }
.bc-status-card.auto-data { border-left:4px solid #3b82f6; }

/* Preview nhanh */
.bc-preview { border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;
              margin-bottom:24px; }
.bc-preview-head { background:#f8fafc; padding:12px 16px; font-weight:600;
                   font-size:.9rem; color:#334155; border-bottom:1px solid #e2e8f0;
                   display:flex; justify-content:space-between; align-items:center; }
.bc-table { width:100%; border-collapse:collapse; font-size:.85rem; }
.bc-table th { background:#f8fafc; padding:8px 12px; text-align:left;
               border-bottom:2px solid #e2e8f0; white-space:nowrap;
               font-weight:600; color:#475569; }
.bc-table td { padding:9px 12px; border-bottom:1px solid #f1f5f9; }
.bc-table tr:last-child td { border-bottom:none; }
.bc-table tr:hover td { background:#f8fafc; }
.bc-badge { display:inline-block; padding:2px 9px; border-radius:99px; font-size:.75rem; }
.bc-badge.ok   { background:#dcfce7; color:#166534; }
.bc-badge.warn { background:#fef9c3; color:#854d0e; }
.bc-badge.bad  { background:#fee2e2; color:#991b1b; }
.bc-badge.auto { background:#dbeafe; color:#1e40af; }
.bc-badge.miss { background:#f1f5f9; color:#94a3b8; }

/* Action buttons */
.bc-actions { display:flex; gap:10px; flex-wrap:wrap; }

/* Lịch sử xuất file */
.bc-history { border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; }
.bc-history-head { background:#f8fafc; padding:12px 16px; font-weight:600;
                   font-size:.85rem; color:#334155; border-bottom:1px solid #e2e8f0; }
.bc-history-row { padding:10px 16px; display:flex; justify-content:space-between;
                  align-items:center; border-bottom:1px solid #f1f5f9; font-size:.85rem; }
.bc-history-row:last-child { border-bottom:none; }

@media(max-width:576px) {
    .bc-status-grid { grid-template-columns:1fr; }
    .bc-date-row    { flex-direction:column; align-items:stretch; }
    .bc-actions     { flex-direction:column; }
    .bc-btn-primary, .bc-btn-outline { justify-content:center; }
}
</style>

<div class="bc-wrap">

    <!-- Hero -->
    <div class="bc-hero">
        <h1>📊 Báo cáo nội bộ hàng ngày</h1>
        <p>Tổng hợp sản xuất • Chất lượng nước • Giao ca — Xuất file Excel</p>
    </div>

    <!-- Chọn ngày -->
    <div class="bc-date-row">
        <input type="date" id="input-ngay"
               value="<?= date('Y-m-d') ?>"
               max="<?= date('Y-m-d') ?>"
               onchange="loadPreview(this.value)" />
        <button class="bc-btn-primary" onclick="xuatExcel()">
            ⬇ Xuất Excel
        </button>
        <a href="<?= Url::to(['nhat-ky/chat-luong-gio']) ?>" class="bc-btn-outline">
            ✏ Nhập CLN
        </a>
        <a href="<?= Url::to(['nhat-ky/giao-ca']) ?>" class="bc-btn-outline">
            📋 Sổ giao ca
        </a>
    </div>

    <!-- Shortcuts -->
    <div class="bc-shortcuts">
        <?php for ($i = 0; $i < 7; $i++):
            $d = date('Y-m-d', strtotime("-$i days"));
            $lbl = $i==0?'Hôm nay':($i==1?'Hôm qua':date('d/m', strtotime("-$i days")));
        ?>
        <a class="bc-shortcut <?= $i==0?'active':'' ?>"
           href="javascript:void(0)"
           onclick="setNgay('<?= $d ?>',this)"><?= $lbl ?></a>
        <?php endfor; ?>
    </div>

    <!-- Tình trạng dữ liệu -->
    <div id="status-grid" class="bc-status-grid">
        <div class="bc-status-card auto-data">
            <div class="label">Sản lượng SCADA</div>
            <div class="value" id="st-scada">Đang tải...</div>
            <div class="sub">Tự động từ SCADA</div>
        </div>
        <div class="bc-status-card" id="card-cln">
            <div class="label">Chất lượng nước</div>
            <div class="value" id="st-cln">—</div>
            <div class="sub">Nhập tay</div>
        </div>
        <div class="bc-status-card" id="card-ca-ngay">
            <div class="label">Sổ giao ca ngày</div>
            <div class="value" id="st-ca-ngay">—</div>
            <div class="sub">Nhập tay</div>
        </div>
        <div class="bc-status-card" id="card-ca-dem">
            <div class="label">Sổ giao ca đêm</div>
            <div class="value" id="st-ca-dem">—</div>
            <div class="sub">Nhập tay</div>
        </div>
    </div>

    <!-- Preview sản lượng -->
    <div class="bc-preview">
        <div class="bc-preview-head">
            <span>Sơ lược sản lượng</span>
            <span id="preview-ngay" style="color:#94a3b8;font-size:.8rem;font-weight:400"></span>
        </div>
        <div style="overflow-x:auto;">
        <table class="bc-table" id="tbl-sanluong">
            <thead>
                <tr>
                    <th>Chỉ tiêu</th>
                    <th>Giá trị</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody id="tbody-sanluong">
                <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:20px">Đang tải...</td></tr>
            </tbody>
        </table>
        </div>
    </div>

    <!-- Preview CLN hôm nay -->
    <div class="bc-preview" id="preview-cln" style="display:none">
        <div class="bc-preview-head">
            <span>Chất lượng nước trong ngày</span>
            <a href="<?= Url::to(['nhat-ky/chat-luong-gio']) ?>"
               style="font-size:.8rem;color:#3b82f6;font-weight:400;text-decoration:none">
               Xem đầy đủ →
            </a>
        </div>
        <div style="overflow-x:auto;">
        <table class="bc-table">
            <thead>
                <tr>
                    <th>Giờ</th><th>Ca</th>
                    <th>NS pH</th><th>NS NTU</th>
                    <th>Clo dư</th><th>NT pH</th><th>NT NTU</th>
                </tr>
            </thead>
            <tbody id="tbody-cln"></tbody>
        </table>
        </div>
    </div>

    <!-- Action xuất -->
    <div class="bc-actions">
        <button class="bc-btn-primary" onclick="xuatExcel()" style="font-size:1rem;padding:13px 28px;">
            ⬇ Xuất báo cáo Excel
        </button>
        <span style="font-size:.82rem;color:#94a3b8;align-self:center;">
            File Excel gồm 3 sheet: Sản xuất • Chất lượng nước • Giao ca
        </span>
    </div>

</div>

<script>
const API_URL  = '<?= Url::to(['/site/iot-api']) ?>';
const SCADA_KEY = 'SCADA_HOCAU_2024_SECRET_KEY';

let currentNgay = '<?= date('Y-m-d') ?>';

function setNgay(ngay, el) {
    currentNgay = ngay;
    document.getElementById('input-ngay').value = ngay;
    document.querySelectorAll('.bc-shortcut').forEach(a => a.classList.remove('active'));
    if (el) el.classList.add('active');
    loadPreview(ngay);
}

function loadPreview(ngay) {
    currentNgay = ngay;
    document.getElementById('preview-ngay').textContent = formatNgayVN(ngay);
    loadScada(ngay);
    loadCLN(ngay);
}

function formatNgayVN(y) {
    // y co the la yyyy-MM-dd hoac dd/MM/yyyy
    let d;
    if (y && y.includes('-')) {
        d = new Date(y + 'T00:00:00'); // tranh timezone shift
    } else if (y && y.includes('/')) {
        const p = y.split('/');
        d = new Date(p[2]+'-'+p[1]+'-'+p[0]+'T00:00:00');
    } else {
        return y;
    }
    const thu = ['CN','T2','T3','T4','T5','T6','T7'][d.getDay()];
    return `${thu}, ${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
}

// Chuyen bat ky format ngay nao -> yyyy-MM-dd
function toIso(ngay) {
    if (!ngay) return '';
    if (/^\d{4}-\d{2}-\d{2}/.test(ngay)) return ngay.slice(0,10); // ISO
    if (/^\d{2}\/\d{2}\/\d{4}/.test(ngay)) {                      // dd/MM/yyyy
        const p = ngay.split('/');
        return p[2] + '-' + p[1] + '-' + p[0];
    }
    return ngay;
}

function loadScada(ngay) {
    const url = '/iot_api.php?action=sanluong&loai=thatthoat&key=' + SCADA_KEY;
    const ngayIso = toIso(ngay); // yyyy-MM-dd
    const stEl = document.getElementById('st-scada');
    stEl.textContent = 'Đang tải...';
    stEl.style.color = '#94a3b8';

    fetch(url)
        .then(r => r.json())
        .then(data => {
            const days = (data.days || []).filter(d => d && d.ngay);
            if (days.length === 0) {
                stEl.textContent = '— Không có dữ liệu';
                stEl.style.color = '#94a3b8';
                renderSanLuong(null);
                return;
            }

            // Normalize tat ca ngay trong days sang ISO de so sanh
            const daysNorm = days.map(d => ({ ...d, _iso: toIso(d.ngay) }));

            // Tim chinh xac ngay yeu cau
            let day = daysNorm.find(d => d._iso === ngayIso);

            if (day) {
                stEl.textContent = '✓ Có dữ liệu';
                stEl.style.color = '#16a34a';
            } else {
                // Khong co ngay hom nay -> fallback ngay moi nhat
                day = daysNorm[daysNorm.length - 1];
                const lbl = formatNgayVN(day._iso);
                stEl.textContent = '↩ DailyReport: ' + lbl;
                stEl.style.color = '#f59e0b';
                // Cap nhat tieu de preview
                const pv = document.getElementById('preview-ngay');
                if (pv) pv.textContent = lbl + ' (mới nhất có dữ liệu)';
            }
            renderSanLuong(day);
        })
        .catch(err => {
            console.error('SCADA fetch error:', err);
            stEl.textContent = '✗ Lỗi kết nối SCADA';
            stEl.style.color = '#ef4444';
            renderSanLuong(null);
        });
}

function renderSanLuong(d) {
    const tbody = document.getElementById('tbody-sanluong');
    if (!d) {
        tbody.innerHTML = '<tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:16px">Không có dữ liệu SCADA cho ngày này</td></tr>';
        return;
    }
    const fmt = v => v != null ? Number(v).toLocaleString('vi-VN') : '—';
    const badge = (v, type) => {
        if (v == null || v === 0) return '<span class="bc-badge miss">—</span>';
        if (type === 'auto') return `<span class="bc-badge auto">Tự động</span>`;
        if (type === 'tl') {
            const cls = v < 10 ? 'ok' : v < 20 ? 'warn' : 'bad';
            return `<span class="bc-badge ${cls}">${v.toFixed(2)}%</span>`;
        }
        return `<span class="bc-badge ok">✓</span>`;
    };
    const rows = [
        ['Nước thô bơm vào (m³)',    fmt(d.nuoc_tho),   badge(d.nuoc_tho,'auto')],
        ['Nước sạch cấp ra (m³)',    fmt(d.nuoc_cap),   badge(d.nuoc_cap,'auto')],
        ['Sản lượng KH (m³)',        fmt(d.nuoc_kh),    badge(d.nuoc_kh, d.nuoc_kh>0?'auto':'miss')],
        ['Thất thoát (m³)',          fmt(d.that_thoat), badge(d.that_thoat,'auto')],
        ['Tỷ lệ thất thoát',         d.ti_le!=null?d.ti_le.toFixed(2)+'%':'—', d.ti_le!=null?badge(d.ti_le,'tl'):'<span class="bc-badge miss">Chưa đủ KH</span>'],
    ];
    tbody.innerHTML = rows.map(([a,b,c],i) =>
        `<tr style="${i%2?'':'background:#f8fafc'}"><td style="font-weight:500">${a}</td><td>${b}</td><td>${c}</td></tr>`
    ).join('');
}

function loadCLN(ngay) {
    fetch(`/quanly/nhat-ky/api-cln?ngay=${ngay}`)
        .then(r => r.json())
        .then(data => {
            const count = data.count || 0;
            const st = document.getElementById('st-cln');
            const card = document.getElementById('card-cln');
            if (count > 0) {
                st.textContent = `✓ ${count} lần nhập`;
                st.style.color = '#16a34a';
                card.classList.add('has-data');
                card.classList.remove('no-data');
                renderCLN(data.rows || []);
                document.getElementById('preview-cln').style.display = 'block';
            } else {
                st.textContent = '— Chưa nhập';
                st.style.color = '#f59e0b';
                card.classList.add('no-data');
                card.classList.remove('has-data');
                document.getElementById('preview-cln').style.display = 'none';
            }

            // Giao ca
            const ca1 = data.ca_ngay;
            const ca2 = data.ca_dem;
            ['ca_ngay','ca_dem'].forEach((k,i) => {
                const ca = i==0?ca1:ca2;
                const stEl = document.getElementById(`st-${k.replace('_','-')}`);
                const cardEl = document.getElementById(`card-${k.replace('_','-')}`);
                if (ca) {
                    stEl.textContent = '✓ Đã nhập';
                    stEl.style.color = '#16a34a';
                    cardEl.classList.add('has-data');
                    cardEl.classList.remove('no-data');
                } else {
                    stEl.textContent = '— Chưa nhập';
                    stEl.style.color = '#f59e0b';
                    cardEl.classList.add('no-data');
                    cardEl.classList.remove('has-data');
                }
            });
        })
        .catch(() => {});
}

function renderCLN(rows) {
    const qcvn = {ns_ph:[6.5,8.5], ns_ntu:[0,2.0], clo_du:[0.2,1.0]};
    function badge(v, field) {
        if (v==null) return '—';
        const q = qcvn[field];
        if (!q) return v;
        const cls = (v<q[0]||v>q[1]) ? 'bad' : ((v<q[0]+0.1||v>q[1]-0.1)?'warn':'ok');
        return `<span class="bc-badge ${cls}">${v}</span>`;
    }
    document.getElementById('tbody-cln').innerHTML = rows.map((r,i) =>
        `<tr style="${i%2?'':'background:#f8fafc'}">
            <td>${r.gio}</td>
            <td>${r.ca==1?'Ngày':'Đêm'}</td>
            <td>${badge(r.ns_ph,'ns_ph')}</td>
            <td>${badge(r.ns_ntu,'ns_ntu')}</td>
            <td>${badge(r.clo_du,'clo_du')}</td>
            <td>${r.nt_ph??'—'}</td>
            <td>${r.nt_ntu??'—'}</td>
        </tr>`
    ).join('');
}

function xuatExcel() {
    const ngay = currentNgay;
    const btn = event.target;
    btn.textContent = '⏳ Đang tạo file...';
    btn.disabled = true;
    window.location.href = `/quanly/nhat-ky/xuat-bao-cao-ngay?ngay=${ngay}`;
    setTimeout(() => {
        btn.textContent = '⬇ Xuất báo cáo Excel';
        btn.disabled = false;
    }, 3000);
}

// Load ngay khi mở trang
loadPreview(currentNgay);
</script>