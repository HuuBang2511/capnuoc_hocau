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
               margin-bottom:16px; }
.bc-date-row input[type=date] {
    padding:10px 14px; border:1.5px solid #e2e8f0; border-radius:10px;
    font-size:.95rem; outline:none; background:#fff; }
.bc-date-row input[type=date]:focus { border-color:#3b82f6; }

/* Nút */
.bc-btn { padding:9px 16px; border-radius:10px; font-size:.85rem; font-weight:600;
          cursor:pointer; text-decoration:none; display:inline-flex;
          align-items:center; gap:6px; border:none; white-space:nowrap; }
.bc-btn:hover { opacity:.88; }
.bc-btn-blue   { background:#3b82f6; color:#fff; }
.bc-btn-green  { background:#16a34a; color:#fff; }
.bc-btn-teal   { background:#0891b2; color:#fff; }
.bc-btn-violet { background:#7c3aed; color:#fff; }
.bc-btn-orange { background:#ea580c; color:#fff; }
.bc-btn-slate  { background:#fff; color:#475569; border:1.5px solid #e2e8f0 !important; }
.bc-btn-slate:hover { border-color:#3b82f6 !important; color:#3b82f6; }

/* Nhóm nút theo section */
.bc-btn-group { display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px; }
.bc-btn-group-label { font-size:.7rem; font-weight:700; color:#94a3b8; letter-spacing:.05em;
                      text-transform:uppercase; margin-bottom:6px; margin-top:4px; }

/* Divider */
.bc-divider { border:none; border-top:1px solid #f1f5f9; margin:16px 0; }

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

@media(max-width:576px) {
    .bc-status-grid { grid-template-columns:1fr; }
    .bc-date-row    { flex-direction:column; align-items:stretch; }
    .bc-btn-group   { flex-direction:column; }
    .bc-btn         { justify-content:center; }
}
</style>

<div class="bc-wrap">

    <!-- Hero -->
    <div class="bc-hero">
        <h1>📊 Báo cáo & Nhật ký vận hành</h1>
        <p>Sản xuất · Chất lượng nước · Giao ca · Sản lượng đồng hồ · Nước thải sinh hoạt</p>
    </div>

    <!-- Chọn ngày -->
    <div class="bc-date-row">
        <input type="date" id="input-ngay"
               value="<?= date('Y-m-d') ?>"
               max="<?= date('Y-m-d') ?>"
               onchange="loadPreview(this.value)" />
    </div>

    <!-- ── NHÓM NÚT ─────────────────────────────────────────── -->

    <!-- Xuất báo cáo -->
    <div class="bc-btn-group-label">📤 Xuất báo cáo</div>
    <div class="bc-btn-group">
        <button class="bc-btn bc-btn-blue" onclick="xuatExcel(this)">
            ⬇ Xuất Excel ngày
        </button>
        <a href="<?= Url::to(['nhat-ky/san-luong-dong-ho']) ?>" class="bc-btn bc-btn-green">
            📈 Sản lượng đồng hồ
        </a>
    </div>

    <hr class="bc-divider">

    <!-- Nhập liệu hàng ngày -->
    <div class="bc-btn-group-label">✏ Nhập liệu hàng ngày</div>
    <div class="bc-btn-group">
        <a id="btn-cln" href="<?= Url::to(['nhat-ky/chat-luong-gio']) ?>"
           class="bc-btn bc-btn-slate">
            🧪 Nhập CLN theo giờ
        </a>
        <a id="btn-giao-ca-ngay"
           href="<?= Url::to(['nhat-ky/giao-ca', 'ca'=>1]) ?>"
           class="bc-btn bc-btn-slate">
            📋 Giao ca ngày
        </a>
        <a id="btn-giao-ca-dem"
           href="<?= Url::to(['nhat-ky/giao-ca', 'ca'=>2]) ?>"
           class="bc-btn bc-btn-slate">
            🌙 Giao ca đêm
        </a>
        <a id="btn-nuoc-thai"
           href="<?= Url::to(['nhat-ky/nuoc-thai-sh']) ?>"
           class="bc-btn bc-btn-slate">
            🧫 Nước thải SH
        </a>
    </div>

    <hr class="bc-divider">

    <!-- Cấu hình -->
    <div class="bc-btn-group-label">⚙ Cấu hình</div>
    <div class="bc-btn-group">
        <a href="<?= Url::to(['nhat-ky/dong-ho-config']) ?>" class="bc-btn bc-btn-slate">
            ⚙️ Cấu hình đồng hồ KH
        </a>
    </div>

    <hr class="bc-divider">

    <!-- Shortcuts ngày -->
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

    <!-- Preview CLN -->
    <div class="bc-preview" id="preview-cln" style="display:none">
        <div class="bc-preview-head">
            <span>Chất lượng nước trong ngày</span>
            <a id="link-cln-full" href="<?= Url::to(['nhat-ky/chat-luong-gio']) ?>"
               style="font-size:.8rem;color:#3b82f6;font-weight:400;text-decoration:none">
               Xem / Nhập đầy đủ →
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

</div>

<script>
const SCADA_KEY  = 'SCADA_HOCAU_2024_SECRET_KEY';
const URL_CLN    = '<?= Url::to(['nhat-ky/chat-luong-gio']) ?>';
const URL_GC1    = '<?= Url::to(['nhat-ky/giao-ca', 'ca'=>1]) ?>';
const URL_GC2    = '<?= Url::to(['nhat-ky/giao-ca', 'ca'=>2]) ?>';
const URL_NT     = '<?= Url::to(['nhat-ky/nuoc-thai-sh']) ?>';
const URL_EXCEL  = '<?= Url::to(['nhat-ky/xuat-bao-cao-ngay']) ?>';

let currentNgay = document.getElementById('input-ngay').value;

function setNgay(ngay, el) {
    document.querySelectorAll('.bc-shortcut').forEach(e => e.classList.remove('active'));
    if (el) el.classList.add('active');
    document.getElementById('input-ngay').value = ngay;
    loadPreview(ngay);
}

function loadPreview(ngay) {
    currentNgay = ngay;
    document.getElementById('preview-ngay').textContent = formatNgayVN(ngay);

    // Cập nhật href các nút nhập liệu theo ngày đang chọn
    document.getElementById('btn-cln').href          = URL_CLN   + '?ngay=' + ngay;
    document.getElementById('btn-giao-ca-ngay').href = URL_GC1   + '&ngay=' + ngay;
    document.getElementById('btn-giao-ca-dem').href  = URL_GC2   + '&ngay=' + ngay;
    document.getElementById('btn-nuoc-thai').href    = URL_NT    + '?ngay='  + ngay;
    document.getElementById('link-cln-full').href    = URL_CLN   + '?ngay=' + ngay;

    loadScada(ngay);
    loadCLN(ngay);
}

function formatNgayVN(y) {
    let d;
    if (y && y.includes('-')) {
        d = new Date(y + 'T00:00:00');
    } else if (y && y.includes('/')) {
        const p = y.split('/');
        d = new Date(p[2]+'-'+p[1]+'-'+p[0]+'T00:00:00');
    } else { return y; }
    const thu = ['CN','T2','T3','T4','T5','T6','T7'][d.getDay()];
    return `${thu}, ${String(d.getDate()).padStart(2,'0')}/${String(d.getMonth()+1).padStart(2,'0')}/${d.getFullYear()}`;
}

function toIso(ngay) {
    if (!ngay) return '';
    if (/^\d{4}-\d{2}-\d{2}/.test(ngay)) return ngay.slice(0,10);
    if (/^\d{2}\/\d{2}\/\d{4}/.test(ngay)) {
        const p = ngay.split('/');
        return p[2] + '-' + p[1] + '-' + p[0];
    }
    return ngay;
}

function loadScada(ngay) {
    const url = '/iot_api.php?action=sanluong&loai=thatthoat&key=' + SCADA_KEY;
    const ngayIso = toIso(ngay);
    const stEl = document.getElementById('st-scada');
    stEl.textContent = 'Đang tải...';
    stEl.style.color = '#94a3b8';

    fetch(url)
        .then(r => r.json())
        .then(data => {
            const days = (data.days || []).filter(d => d && d.ngay);
            if (!days.length) {
                stEl.textContent = '— Không có dữ liệu';
                stEl.style.color = '#94a3b8';
                renderSanLuong(null);
                return;
            }
            const daysNorm = days.map(d => ({ ...d, _iso: toIso(d.ngay) }));
            let day = daysNorm.find(d => d._iso === ngayIso);
            if (day) {
                stEl.textContent = '✓ Có dữ liệu';
                stEl.style.color = '#16a34a';
            } else {
                day = daysNorm[daysNorm.length - 1];
                const lbl = formatNgayVN(day._iso);
                stEl.textContent = '↩ DailyReport: ' + lbl;
                stEl.style.color = '#f59e0b';
                const pv = document.getElementById('preview-ngay');
                if (pv) pv.textContent = lbl + ' (mới nhất có dữ liệu)';
            }
            renderSanLuong(day);
        })
        .catch(err => {
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
        ['Nước thô bơm vào (m³)', fmt(d.nuoc_tho),  badge(d.nuoc_tho, 'auto')],
        ['Nước sạch cấp ra (m³)', fmt(d.nuoc_cap),  badge(d.nuoc_cap, 'auto')],
        ['Sản lượng KH (m³)',     fmt(d.nuoc_kh),   badge(d.nuoc_kh, d.nuoc_kh > 0 ? 'auto' : 'miss')],
        ['Thất thoát (m³)',       fmt(d.that_thoat),badge(d.that_thoat, 'auto')],
        ['Tỷ lệ thất thoát', d.ti_le != null ? d.ti_le.toFixed(2) + '%' : '—',
            d.ti_le != null ? badge(d.ti_le, 'tl') : '<span class="bc-badge miss">Chưa đủ KH</span>'],
    ];
    tbody.innerHTML = rows.map(([a,b,c], i) =>
        `<tr style="${i%2?'':'background:#f8fafc'}"><td style="font-weight:500">${a}</td><td>${b}</td><td>${c}</td></tr>`
    ).join('');
}

function loadCLN(ngay) {
    fetch(`/quanly/nhat-ky/api-cln?ngay=${ngay}`)
        .then(r => r.json())
        .then(data => {
            const count = data.count || 0;
            const st   = document.getElementById('st-cln');
            const card = document.getElementById('card-cln');
            if (count > 0) {
                st.textContent = `✓ ${count} lần nhập`;
                st.style.color = '#16a34a';
                card.classList.add('has-data'); card.classList.remove('no-data');
                renderCLN(data.rows || []);
                document.getElementById('preview-cln').style.display = 'block';
            } else {
                st.textContent = '— Chưa nhập';
                st.style.color = '#f59e0b';
                card.classList.add('no-data'); card.classList.remove('has-data');
                document.getElementById('preview-cln').style.display = 'none';
            }

            ['ca_ngay','ca_dem'].forEach((k, i) => {
                const ca    = i === 0 ? data.ca_ngay : data.ca_dem;
                const id    = k.replace('_', '-');
                const stEl  = document.getElementById(`st-${id}`);
                const cardEl= document.getElementById(`card-${id}`);
                if (ca) {
                    stEl.textContent = '✓ Đã nhập';
                    stEl.style.color = '#16a34a';
                    cardEl.classList.add('has-data'); cardEl.classList.remove('no-data');
                } else {
                    stEl.textContent = '— Chưa nhập';
                    stEl.style.color = '#f59e0b';
                    cardEl.classList.add('no-data'); cardEl.classList.remove('has-data');
                }
            });
        })
        .catch(() => {});
}

function renderCLN(rows) {
    const qcvn = { ns_ph:[6.5,8.5], ns_ntu:[0,2.0], clo_du:[0.2,1.0] };
    function badge(v, field) {
        if (v == null) return '—';
        const q = qcvn[field];
        if (!q) return v;
        const cls = (v < q[0] || v > q[1]) ? 'bad' : ((v < q[0]+0.1 || v > q[1]-0.1) ? 'warn' : 'ok');
        return `<span class="bc-badge ${cls}">${v}</span>`;
    }
    document.getElementById('tbody-cln').innerHTML = rows.map((r, i) =>
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

function xuatExcel(btn) {
    btn.textContent = '⏳ Đang tạo file...';
    btn.disabled = true;
    window.location.href = URL_EXCEL + '?ngay=' + currentNgay;
    setTimeout(() => { btn.textContent = '⬇ Xuất Excel ngày'; btn.disabled = false; }, 3000);
}

// Load khi mở trang
loadPreview(currentNgay);

// Auto refresh moi 60 giay — chi khi dang xem ngay hom nay va tab active
setInterval(function() {
    if (!document.hidden) {
        const today = new Date().toISOString().slice(0,10);
        if (currentNgay === today) {
            loadScada(currentNgay);
            loadCLN(currentNgay);
        }
    }
}, 60000);
</script>