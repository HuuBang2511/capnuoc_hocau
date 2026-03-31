<?php
namespace app\modules\quanly\components;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\{Fill, Border, Alignment, Font};
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Yii;

class BaoCaoNgayExcel
{
    private $ngay;
    private $scada;      // Dữ liệu từ SCADA
    private $chatLuong;  // Mảng NkChatLuongGio
    private $giaoCa;     // Mảng NkGiaoCa (2 phần tử: ca ngày + ca đêm)

    // Màu sắc
    const C_HEADER  = 'FF1E3A5F';  // Xanh đậm header
    const C_SUBHEAD = 'FF2D6099';  // Xanh nhạt sub-header
    const C_ODD     = 'FFF0F4F8';  // Nền dòng lẻ
    const C_OK      = 'FFD1FAE5';  // Xanh lá OK
    const C_WARN    = 'FFFEF3C7';  // Vàng cảnh báo
    const C_BAD     = 'FFFEE2E2';  // Đỏ vượt ngưỡng
    const C_WHITE   = 'FFFFFFFF';

    public function __construct($ngay, $scada, $chatLuong, $giaoCa)
    {
        $this->ngay      = $ngay;
        $this->scada     = $scada;
        $this->chatLuong = $chatLuong;
        $this->giaoCa    = $giaoCa;
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setTitle('Báo cáo ngày ' . $this->ngay)
            ->setCompany('Công ty CP Cấp Nước Hồ Cầu Mới');

        $this->buildSheet1($spreadsheet->getActiveSheet());
        $this->buildSheet2($spreadsheet->createSheet());
        $this->buildSheet3($spreadsheet->createSheet());

        $spreadsheet->setActiveSheetIndex(0);

        $filename = 'BaoCaoNgay_' . $this->ngay . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        Yii::$app->end();
    }

    // ── SHEET 1: SẢN XUẤT (từ SCADA) ────────────────────────
    private function buildSheet1($sheet)
    {
        $sheet->setTitle('Sản xuất');
        $ngay_vn  = date('d/m/Y', strtotime($this->ngay));
        $thu_vn   = ['CN','Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7'][date('w', strtotime($this->ngay))];
        $s = $this->scada;

        // Header công ty
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A1', 'CÔNG TY CỔ PHẦN CẤP NƯỚC HỒ CẦU MỚI');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A2', 'BÁO CÁO SẢN XUẤT HÀNG NGÀY');
        $sheet->mergeCells('A3:H3');
        $sheet->setCellValue('A3', "$thu_vn, ngày $ngay_vn");

        $this->styleHeader($sheet, 'A1:H1', 14, self::C_HEADER, 'FFFFFFFF');
        $this->styleHeader($sheet, 'A2:H2', 12, self::C_SUBHEAD, 'FFFFFFFF');
        $this->styleCenter($sheet, 'A3:H3');

        // Bảng sản lượng
        $row = 5;
        $this->writeRow($sheet, $row, ['STT','Chỉ tiêu','Đvt','Ca Ngày','Ca Đêm','Tổng ngày','Tháng lũy kế','Ghi chú'], true);
        $row++;

        $ca_ngay = $this->getGiaoCa(1);
        $ca_dem  = $this->getGiaoCa(2);

        $sl_cap_ngay = ($ca_ngay !== null ? $ca_ngay->getSanLuongCap() : null) ?? (($s['nuoc_cap'] ?? 0) / 2);
        $sl_tho_ngay = ($ca_ngay && $ca_ngay->nuoc_tho_cuoi) ?
                        $ca_ngay->nuoc_tho_cuoi - $ca_ngay->nuoc_tho_dau :
                        ($s['nuoc_tho'] ?? 0) / 2;

        $data = [
            [1, 'SL Nước thô bơm vào',  'm³', round($sl_tho_ngay), round($s['nuoc_tho']??0 - $sl_tho_ngay), $s['nuoc_tho']??'—', '—'],
            [2, 'SL Nước sạch cấp ra',  'm³', round($sl_cap_ngay), round($s['nuoc_cap']??0 - $sl_cap_ngay), $s['nuoc_cap']??'—', '—'],
            [3, 'SL KH đồng hồ lớn',    'm³', '—', '—', $s['nuoc_kh']??'—', '—'],
            [4, 'Nước thất thoát',       'm³', '—', '—', $s['that_thoat']??'—', '—'],
            [5, 'Tỷ lệ thất thoát',      '%',  '—', '—', isset($s['ti_le'])?number_format($s['ti_le'],2).'%':'—', '—'],
        ];

        foreach ($data as $i => $d) {
            $bgColor = $i % 2 == 0 ? self::C_ODD : self::C_WHITE;
            $this->writeRow($sheet, $row, array_merge($d, ['']), false, $bgColor);
            $row++;
        }

        // Hóa chất
        $row++;
        $this->writeRow($sheet, $row, ['','Hóa chất sử dụng','','Ca Ngày','Ca Đêm','Tổng','',''], true, self::C_SUBHEAD, 'FFFFFFFF');
        $row++;
        $hc = [
            ['1','PAC','kg', isset($ca_ngay) && isset($ca_ngay->pac_kg) ? $ca_ngay->pac_kg : '—', isset($ca_dem) && isset($ca_dem->pac_kg) ? $ca_dem->pac_kg : '—'],
            ['2','Chlorine','kg', isset($ca_ngay) && isset($ca_ngay->chlorine_kg) ? $ca_ngay->chlorine_kg : '—', isset($ca_dem) && isset($ca_dem->chlorine_kg) ? $ca_dem->chlorine_kg : '—'],
            ['3','Polymer','kg', isset($ca_ngay) && isset($ca_ngay->polymer_kg) ? $ca_ngay->polymer_kg : '—', isset($ca_dem) && isset($ca_dem->polymer_kg) ? $ca_dem->polymer_kg : '—'],
        ];
        foreach ($hc as $i => $d) {
            $tong = is_numeric($d[3]) && is_numeric($d[4]) ? $d[3]+$d[4] : '—';
            $this->writeRow($sheet, $row, [$d[0],$d[1],$d[2],$d[3],$d[4],$tong,'',''], false, $i%2==0?self::C_ODD:self::C_WHITE);
            $row++;
        }

        // Điện
        $row++;
        $this->writeRow($sheet, $row, ['','Điện tiêu thụ','KWh','Ca Ngày','Ca Đêm','Tổng','Định mức',''], true, self::C_SUBHEAD, 'FFFFFFFF');
        $row++;
        foreach (['Điện nhà máy','Điện trạm bơm'] as $i => $label) {
            $f_d = $i==0?'dien_nha_may':'dien_tram_bom';
            $d = ($ca_ngay&&$ca_ngay->{$f_d.'_cuoi'}) ? $ca_ngay->{$f_d.'_cuoi'}-$ca_ngay->{$f_d.'_dau'} : '—';
            $e = ($ca_dem &&$ca_dem->{$f_d.'_cuoi'})  ? $ca_dem->{$f_d.'_cuoi'} -$ca_dem->{$f_d.'_dau'}  : '—';
            $tong = is_numeric($d)&&is_numeric($e) ? $d+$e : '—';
            $this->writeRow($sheet, $row, ['',($i+1).'. '.$label,'KWh',$d,$e,$tong,'',''], false, $i%2==0?self::C_ODD:self::C_WHITE);
            $row++;
        }

        // Mực nước hồ
        $row++;
        $sheet->setCellValue("A$row", 'Mực nước hồ chứa: ');
        $sheet->setCellValue("C$row", ($s['level_lake']??'—') . ' m');

        // Column widths
        foreach (['A'=>8,'B'=>35,'C'=>8,'D'=>12,'E'=>12,'F'=>14,'G'=>14,'H'=>20] as $col=>$w)
            $sheet->getColumnDimension($col)->setWidth($w);
    }

    // ── SHEET 2: CHẤT LƯỢNG NƯỚC ─────────────────────────────
    private function buildSheet2($sheet)
    {
        $sheet->setTitle('Chất lượng nước');
        $ngay_vn = date('d/m/Y', strtotime($this->ngay));

        $sheet->mergeCells('A1:K1');
        $sheet->setCellValue('A1', 'NHẬT KÝ PHÂN TÍCH CHẤT LƯỢNG NƯỚC — ' . $ngay_vn);
        $this->styleHeader($sheet, 'A1:K1', 12, self::C_HEADER, 'FFFFFFFF');

        // Ngưỡng QCVN ghi chú
        $sheet->setCellValue('A2', 'QCVN 01-1:2018/BYT: pH 6.5–8.5 | Độ đục < 2 NTU | Clo dư 0.2–1.0 mg/L');
        $sheet->mergeCells('A2:K2');
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A2')->getFont()->getColor()->setARGB('FF64748B');

        $row = 4;
        $headers = ['Giờ','Ca','NS - pH','NS - NTU','NT - pH','NT - NTU',
                    'Lắng 1 - pH','Lắng 1 - NTU','Lắng 2 - pH','Lắng 2 - NTU','Clo dư (mg/L)'];
        $this->writeRow($sheet, $row, $headers, true);
        $row++;

        $qcvn = ['ns_ph'=>[6.5,8.5],'ns_ntu'=>[0,2.0],'clo_du'=>[0.2,1.0]];

        foreach ($this->chatLuong as $i => $r) {
            $gio = date('H:i', strtotime($r->thoi_gian));
            $ten_ca = $r->ca==1?'Ngày':'Đêm';
            $vals = [$gio,$ten_ca,$r->ns_ph,$r->ns_ntu,$r->nt_ph,$r->nt_ntu,
                     $r->nl1_ph,$r->nl1_ntu,$r->nl2_ph,$r->nl2_ntu,$r->clo_du];
            $bg = $i%2==0?self::C_ODD:self::C_WHITE;
            $this->writeRow($sheet, $row, $vals, false, $bg);

            // Tô màu ô vượt ngưỡng
            $check = ['ns_ph'=>'C','ns_ntu'=>'D','clo_du'=>'K'];
            foreach ($check as $field => $col) {
                $v = $r->$field;
                if ($v === null) continue;
                [$mn,$mx] = $qcvn[$field];
                if ((float)$v < $mn || (float)$v > $mx) {
                    $sheet->getStyle("{$col}{$row}")->getFill()
                          ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::C_BAD);
                } elseif ((float)$v < $mn+($mx-$mn)*0.05 || (float)$v > $mx-($mx-$mn)*0.05) {
                    $sheet->getStyle("{$col}{$row}")->getFill()
                          ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::C_WARN);
                }
            }
            $row++;
        }

        if (empty($this->chatLuong)) {
            $sheet->mergeCells("A{$row}:K{$row}");
            $sheet->setCellValue("A{$row}", '— Chưa có dữ liệu cho ngày này —');
            $sheet->getStyle("A{$row}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        }

        foreach (['A'=>8,'B'=>8,'C'=>8,'D'=>9,'E'=>8,'F'=>9,
                  'G'=>11,'H'=>12,'I'=>11,'J'=>12,'K'=>13] as $c=>$w)
            $sheet->getColumnDimension($c)->setWidth($w);
    }

    // ── SHEET 3: GIAO CA ─────────────────────────────────────
    private function buildSheet3($sheet)
    {
        $sheet->setTitle('Giao ca');
        $ngay_vn = date('d/m/Y', strtotime($this->ngay));

        $sheet->mergeCells('A1:E1');
        $sheet->setCellValue('A1', 'SỔ GIAO CA — ' . $ngay_vn);
        $this->styleHeader($sheet, 'A1:E1', 12, self::C_HEADER, 'FFFFFFFF');

        $row = 3;
        foreach ([1=>'CA NGÀY (07h–18h)', 2=>'CA ĐÊM (19h–06h)'] as $ca => $label) {
            $gc = $this->getGiaoCa($ca);

            $sheet->mergeCells("A{$row}:E{$row}");
            $sheet->setCellValue("A{$row}", $label);
            $this->styleHeader($sheet, "A{$row}:E{$row}", 10, self::C_SUBHEAD, 'FFFFFFFF');
            $row++;

            $items = [
                ['Nước cấp (m³)', isset($gc) && isset($gc->nuoc_cap_dau) ? $gc->nuoc_cap_dau : '—', isset($gc) && isset($gc->nuoc_cap_cuoi) ? $gc->nuoc_cap_cuoi : '—', isset($gc) && method_exists($gc, 'getSanLuongCap') && $gc->getSanLuongCap() !== null ? $gc->getSanLuongCap() : '—'],
                ['Nước thô (m³)', isset($gc) && isset($gc->nuoc_tho_dau) ? $gc->nuoc_tho_dau : '—', isset($gc) && isset($gc->nuoc_tho_cuoi) ? $gc->nuoc_tho_cuoi : '—', '—'],
                ['Điện NM (KWh)', isset($gc) && isset($gc->dien_nha_may_dau) ? $gc->dien_nha_may_dau : '—', isset($gc) && isset($gc->dien_nha_may_cuoi) ? $gc->dien_nha_may_cuoi : '—',
                 (isset($gc) && isset($gc->dien_nha_may_cuoi) && isset($gc->dien_nha_may_dau)) ? $gc->dien_nha_may_cuoi - $gc->dien_nha_may_dau : '—'],
            ];
            $sheet->setCellValue("A{$row}", 'Chỉ tiêu');
            $sheet->setCellValue("B{$row}", 'Đầu ca');
            $sheet->setCellValue("C{$row}", 'Cuối ca');
            $sheet->setCellValue("D{$row}", 'Tổng ca');
            $this->styleRow($sheet, "A{$row}:D{$row}", true);
            $row++;
            foreach ($items as $i=>$it) {
                $sheet->fromArray($it, null, "A{$row}");
                if ($i%2==0) $sheet->getStyle("A{$row}:D{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::C_ODD);
                $row++;
            }

            // Bơm + hóa chất
            $row++;
            $sheet->setCellValue("A{$row}", 'Bơm NT: ' . (isset($gc) && isset($gc->bom_nt_chay) ? $gc->bom_nt_chay : '—')
                . ' | Bơm TH: ' . (isset($gc) && isset($gc->bom_th_chay) ? $gc->bom_th_chay : '—'));
            $sheet->mergeCells("A{$row}:D{$row}"); $row++;
            $sheet->setCellValue("A{$row}", 'PAC: ' . (isset($gc) && isset($gc->pac_kg) ? $gc->pac_kg : '—') . ' kg'
                . ' | Chlorine: ' . (isset($gc) && isset($gc->chlorine_kg) ? $gc->chlorine_kg : '—') . ' kg'
                . ' | Polymer: ' . (isset($gc) && isset($gc->polymer_kg) ? $gc->polymer_kg : '—') . ' kg');
            $sheet->mergeCells("A{$row}:D{$row}"); $row++;

            if (isset($gc) && isset($gc->su_co) && $gc->su_co) {
                $sheet->setCellValue("A{$row}", '⚠ Sự cố: ' . $gc->su_co);
                $sheet->mergeCells("A{$row}:D{$row}");
                $sheet->getStyle("A{$row}")->getFont()->getColor()->setARGB('FFDC2626');
                $row++;
                if (isset($gc->bien_phap) && $gc->bien_phap) {
                    $sheet->setCellValue("A{$row}", '→ Biện pháp: ' . $gc->bien_phap);
                    $sheet->mergeCells("A{$row}:D{$row}"); $row++;
                }
            }

            $sheet->setCellValue("A{$row}", 'NV giao: ' . (isset($gc) && isset($gc->nhan_vien_giao) ? $gc->nhan_vien_giao : '—')
                . '   |   NV nhận: ' . (isset($gc) && isset($gc->nhan_vien_nhan) ? $gc->nhan_vien_nhan : '—'));
            $sheet->mergeCells("A{$row}:D{$row}"); $row += 2;
        }

        foreach (['A'=>25,'B'=>12,'C'=>12,'D'=>12,'E'=>15] as $c=>$w)
            $sheet->getColumnDimension($c)->setWidth($w);
    }

    // ── Helpers ───────────────────────────────────────────────
    private function getGiaoCa($ca)
    {
        foreach ($this->giaoCa as $gc) {
            if ($gc->ca == $ca) return $gc;
        }
        return null;
    }

    private function writeRow($sheet, $row, $data, $bold=false, $bg=null, $color=null)
    {
        $cols = range('A', chr(ord('A') + count($data) - 1));
        foreach ($data as $i => $val) {
            $cell = $cols[$i] . $row;
            $sheet->setCellValue($cell, $val ?? '');
        }
        $range = 'A'.$row.':'.$cols[count($data)-1].$row;
        $this->styleRow($sheet, $range, $bold, $bg, $color);
    }

    private function styleRow($sheet, $range, $bold=false, $bg=null, $color=null)
    {
        $style = $sheet->getStyle($range);
        $style->getAlignment()
              ->setHorizontal(Alignment::HORIZONTAL_CENTER)
              ->setVertical(Alignment::VERTICAL_CENTER);
        $style->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN)
              ->getColor()->setARGB('FFE2E8F0');
        if ($bold) $style->getFont()->setBold(true);
        if ($bg) $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bg);
        if ($color) $style->getFont()->getColor()->setARGB($color);
    }

    private function styleHeader($sheet, $range, $size=11, $bg=null, $color=null)
    {
        $style = $sheet->getStyle($range);
        $style->getFont()->setBold(true)->setSize($size);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
              ->setVertical(Alignment::VERTICAL_CENTER);
        if ($bg) $style->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB($bg);
        if ($color) $style->getFont()->getColor()->setARGB($color);
    }

    private function styleCenter($sheet, $range)
    {
        $sheet->getStyle($range)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }
}
