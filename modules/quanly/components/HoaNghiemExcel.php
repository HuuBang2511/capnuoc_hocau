<?php
/**
 * HoaNghiemExcel.php
 * Xuất báo cáo hoá nghiệm (chất lượng nước theo giờ + CLN hàng ngày)
 * Đặt tại: modules\quanly\components\HoaNghiemExcel.php
 */

namespace app\modules\quanly\components;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class HoaNghiemExcel
{
    private $ngay;
    private $chatLuong; // array of NkChatLuongGio
    private $clnNgay;   // NkClnHangNgay|null

    // QCVN 01-1:2018/BYT
    const QCVN_PH_MIN  = 6.5;
    const QCVN_PH_MAX  = 8.5;
    const QCVN_NTU_MAX = 2.0;
    const QCVN_CLO_MIN = 0.2;
    const QCVN_CLO_MAX = 1.0;

    // Màu header
    const COLOR_HEADER_DARK  = 'FF1E3A5F'; // xanh đậm
    const COLOR_HEADER_MID   = 'FF2E6DA4'; // xanh vừa
    const COLOR_HEADER_LIGHT = 'FFD9E8F5'; // xanh nhạt
    const COLOR_WARNING      = 'FFFFF0B3'; // vàng cảnh báo
    const COLOR_FAIL         = 'FFFFCCCC'; // đỏ nhạt — vượt QCVN

    public function __construct($ngay, $chatLuong, $clnNgay)
    {
        $this->ngay      = $ngay;
        $this->chatLuong = $chatLuong ? $chatLuong : array();
        $this->clnNgay   = $clnNgay;
    }

    public function download()
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setTitle('Hoá Nghiệm ' . $this->ngay)
            ->setCreator('WebGIS Cấp Nước Hồ Cầu Mới');

        $this->buildSheet1($spreadsheet);
        $this->buildSheet2($spreadsheet);

        // Xuất file
        $writer   = new Xlsx($spreadsheet);
        $filename = 'hoa_nghiem_' . str_replace('-', '', $this->ngay) . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer->save('php://output');
        exit;
    }

    /** Sheet 1 — Chất lượng nước theo giờ */
    private function buildSheet1($spreadsheet)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Theo Giờ');

        $ngayFmt = date('d/m/Y', strtotime($this->ngay));

        // ===== TIÊU ĐỀ =====
        $sheet->mergeCells('A1:S1');
        $sheet->setCellValue('A1', 'CÔNG TY CP CẤP NƯỚC HỒ CẦU MỚI');
        $this->styleTitle($sheet, 'A1:S1', 12, true);

        $sheet->mergeCells('A2:S2');
        $sheet->setCellValue('A2', 'NHẬT KÝ PHÂN TÍCH HOÁ NGHIỆM THEO GIỜ');
        $this->styleTitle($sheet, 'A2:S2', 13, true, self::COLOR_HEADER_DARK);

        $sheet->mergeCells('A3:S3');
        $sheet->setCellValue('A3', 'Ngày: ' . $ngayFmt);
        $this->styleTitle($sheet, 'A3:S3', 11, false);

        // ===== HEADER NHÓM CỘT =====
        $row = 5;
        // Cột STT + Thời gian + Ca
        $sheet->setCellValue('A' . $row, 'STT');
        $sheet->mergeCells('B' . $row . ':B' . ($row + 1));
        $sheet->setCellValue('B' . $row, 'Thời gian');
        $sheet->mergeCells('C' . $row . ':C' . ($row + 1));
        $sheet->setCellValue('C' . $row, 'Ca');

        // Nhóm Nước Sạch
        $sheet->mergeCells('D' . $row . ':F' . $row);
        $sheet->setCellValue('D' . $row, 'NƯỚC SẠCH (NS)');

        // Nhóm Nước Thô
        $sheet->mergeCells('G' . $row . ':I' . $row);
        $sheet->setCellValue('G' . $row, 'NƯỚC THÔ (NT)');

        // Nhóm Bể Lắng
        $sheet->mergeCells('J' . $row . ':M' . $row);
        $sheet->setCellValue('J' . $row, 'BỂ LẮNG');

        // Clor dư
        $sheet->mergeCells('N' . $row . ':N' . ($row + 1));
        $sheet->setCellValue('N' . $row, 'Clo dư (mg/L)');

        // Nhóm châm hoá chất
        $sheet->mergeCells('O' . $row . ':R' . $row);
        $sheet->setCellValue('O' . $row, 'CHÂM HOÁ CHẤT');

        // Ghi chú
        $sheet->mergeCells('S' . $row . ':S' . ($row + 1));
        $sheet->setCellValue('S' . $row, 'Ghi chú');

        // Header dòng 2
        $row2 = $row + 1;
        $sheet->setCellValue('A' . $row2, 'STT');
        $sheet->setCellValue('D' . $row2, 'pH');
        $sheet->setCellValue('E' . $row2, 'NTU');
        $sheet->setCellValue('F' . $row2, 'Độ màu');
        $sheet->setCellValue('G' . $row2, 'pH');
        $sheet->setCellValue('H' . $row2, 'NTU');
        $sheet->setCellValue('I' . $row2, 'Độ màu');
        $sheet->setCellValue('J' . $row2, 'BL1 pH');
        $sheet->setCellValue('K' . $row2, 'BL1 NTU');
        $sheet->setCellValue('L' . $row2, 'BL2 pH');
        $sheet->setCellValue('M' . $row2, 'BL2 NTU');
        $sheet->setCellValue('O' . $row2, 'NS Clo (mg/L)');
        $sheet->setCellValue('P' . $row2, 'NT Clo (mg/L)');
        $sheet->setCellValue('Q' . $row2, 'Clo châm (L/h)');
        $sheet->setCellValue('R' . $row2, 'PAC châm (L/h)');

        // Style header nhóm
        $this->styleHeaderGroup($sheet, 'D' . $row . ':F' . $row, self::COLOR_HEADER_MID);
        $this->styleHeaderGroup($sheet, 'G' . $row . ':I' . $row, self::COLOR_HEADER_MID);
        $this->styleHeaderGroup($sheet, 'J' . $row . ':M' . $row, self::COLOR_HEADER_MID);
        $this->styleHeaderGroup($sheet, 'O' . $row . ':R' . $row, self::COLOR_HEADER_MID);
        $this->styleHeaderGroup($sheet, 'A' . $row . ':S' . $row2, self::COLOR_HEADER_LIGHT);

        // Merge STT col dòng 5-6
        $sheet->mergeCells('A' . $row . ':A' . $row2);

        // ===== DATA =====
        $dataRow  = $row2 + 1;
        $stt      = 1;
        foreach ($this->chatLuong as $rec) {
            $thoiGian = date('H:i', strtotime($rec->thoi_gian));
            $caLabel  = ($rec->ca == 1) ? 'Ngày' : 'Đêm';

            $sheet->setCellValue('A' . $dataRow, $stt);
            $sheet->setCellValue('B' . $dataRow, $thoiGian);
            $sheet->setCellValue('C' . $dataRow, $caLabel);
            $sheet->setCellValue('D' . $dataRow, $this->val($rec->ns_ph));
            $sheet->setCellValue('E' . $dataRow, $this->val($rec->ns_ntu));
            $sheet->setCellValue('F' . $dataRow, $this->val($rec->ns_do_mau));
            $sheet->setCellValue('G' . $dataRow, $this->val($rec->nt_ph));
            $sheet->setCellValue('H' . $dataRow, $this->val($rec->nt_ntu));
            $sheet->setCellValue('I' . $dataRow, $this->val($rec->nt_do_mau));
            $sheet->setCellValue('J' . $dataRow, $this->val($rec->nl1_ph));
            $sheet->setCellValue('K' . $dataRow, $this->val($rec->nl1_ntu));
            $sheet->setCellValue('L' . $dataRow, $this->val($rec->nl2_ph));
            $sheet->setCellValue('M' . $dataRow, $this->val($rec->nl2_ntu));
            $sheet->setCellValue('N' . $dataRow, $this->val($rec->clo_du));
            $sheet->setCellValue('O' . $dataRow, $this->val($rec->ns_clo_nong_do));
            $sheet->setCellValue('P' . $dataRow, $this->val($rec->nt_clo_nong_do));
            $sheet->setCellValue('Q' . $dataRow, $this->val($rec->nc_clo_cham));
            $sheet->setCellValue('R' . $dataRow, $this->val($rec->pac_cham));
            $sheet->setCellValue('S' . $dataRow, isset($rec->ghi_chu) ? $rec->ghi_chu : '');

            // Highlight vượt QCVN (nước sạch)
            $this->highlightQcvn($sheet, $rec->ns_ph,  'D' . $dataRow, self::QCVN_PH_MIN,  self::QCVN_PH_MAX);
            $this->highlightQcvn($sheet, $rec->ns_ntu, 'E' . $dataRow, null,               self::QCVN_NTU_MAX);
            $this->highlightQcvn($sheet, $rec->clo_du, 'N' . $dataRow, self::QCVN_CLO_MIN, self::QCVN_CLO_MAX);

            $this->styleDataRow($sheet, 'A' . $dataRow . ':S' . $dataRow, ($stt % 2 == 0));
            $stt++;
            $dataRow++;
        }

        if ($stt == 1) {
            // Không có dữ liệu
            $sheet->mergeCells('A' . $dataRow . ':S' . $dataRow);
            $sheet->setCellValue('A' . $dataRow, 'Không có dữ liệu cho ngày ' . $ngayFmt);
            $sheet->getStyle('A' . $dataRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $dataRow++;
        }

        // ===== QCVN chú thích =====
        $noteRow = $dataRow + 1;
        $sheet->mergeCells('A' . $noteRow . ':S' . $noteRow);
        $sheet->setCellValue('A' . $noteRow,
            'QCVN 01-1:2018/BYT: pH nước sạch 6.5–8.5 | NTU < 2.0 | Clo dư 0.2–1.0 mg/L  |  Ô màu đỏ = vượt ngưỡng');
        $sheet->getStyle('A' . $noteRow)->getFont()->setItalic(true)->setSize(9);
        $sheet->getStyle('A' . $noteRow)->getFill()
            ->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB(self::COLOR_FAIL);

        // ===== Người ký =====
        $signRow = $noteRow + 2;
        $sheet->mergeCells('A' . $signRow . ':D' . $signRow);
        $sheet->setCellValue('A' . $signRow, 'Người kiểm tra');
        $sheet->mergeCells('P' . $signRow . ':S' . $signRow);
        $sheet->setCellValue('P' . $signRow, 'Người lập');
        foreach (array('A', 'P') as $col) {
            $sheet->getStyle($col . $signRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($col . $signRow)->getFont()->setBold(true);
        }

        // ===== Column widths =====
        $widths = array(
            'A' => 5, 'B' => 9, 'C' => 7,
            'D' => 7, 'E' => 7, 'F' => 9,
            'G' => 7, 'H' => 7, 'I' => 9,
            'J' => 8, 'K' => 8, 'L' => 8, 'M' => 8,
            'N' => 11, 'O' => 13, 'P' => 13, 'Q' => 12, 'R' => 12,
            'S' => 20,
        );
        foreach ($widths as $col => $w) {
            $sheet->getColumnDimension($col)->setWidth($w);
        }

        // Border toàn bảng
        if ($stt > 1) {
            $tableRange = 'A' . ($row) . ':S' . ($dataRow - 1);
            $this->applyBorderAll($sheet, $tableRange);
        }

        // Freeze header
        $sheet->freezePane('A' . ($row2 + 1));
    }

    /** Sheet 2 — Tổng hợp CLN hàng ngày (jar test) */
    private function buildSheet2($spreadsheet)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle('Tổng Hợp Ngày');

        $ngayFmt = date('d/m/Y', strtotime($this->ngay));

        $sheet->mergeCells('A1:L1');
        $sheet->setCellValue('A1', 'TỔNG HỢP CHẤT LƯỢNG NƯỚC — ' . $ngayFmt);
        $this->styleTitle($sheet, 'A1:L1', 13, true, self::COLOR_HEADER_DARK);

        if (!$this->clnNgay) {
            $sheet->mergeCells('A3:L3');
            $sheet->setCellValue('A3', 'Chưa có dữ liệu CLN hàng ngày cho ngày ' . $ngayFmt);
            $sheet->getStyle('A3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            return;
        }

        $cln = $this->clnNgay;

        // Jar test Ca Sáng
        $row = 3;
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValue('A' . $row, 'JAR TEST CA SÁNG — Giờ lấy mẫu: ' . (isset($cln->jar_gio_sang) ? $cln->jar_gio_sang : ''));
        $this->styleHeaderGroup($sheet, 'A' . $row . ':L' . $row, self::COLOR_HEADER_MID);

        $row++;
        $headers = array('Liều PAC (mg/L)', 'NTU sau', 'pH sau', '');
        $cols    = array('B', 'C', 'D', 'E', 'F', 'G');
        $sheet->setCellValue('A' . $row, 'Cốc số');
        for ($i = 1; $i <= 6; $i++) {
            $sheet->setCellValue($cols[$i - 1] . $row, 'Cốc ' . $i);
        }
        $this->styleHeaderGroup($sheet, 'A' . $row . ':G' . $row, self::COLOR_HEADER_LIGHT);

        $pacS = isset($cln->jar_s_pac) ? (is_array($cln->jar_s_pac) ? $cln->jar_s_pac : json_decode($cln->jar_s_pac, true)) : array();
        $ntuS = isset($cln->jar_s_ntu) ? (is_array($cln->jar_s_ntu) ? $cln->jar_s_ntu : json_decode($cln->jar_s_ntu, true)) : array();
        $phS  = isset($cln->jar_s_ph)  ? (is_array($cln->jar_s_ph)  ? $cln->jar_s_ph  : json_decode($cln->jar_s_ph, true))  : array();

        $jarRows = array(
            array('label' => 'PAC (mg/L)', 'data' => $pacS),
            array('label' => 'NTU',        'data' => $ntuS),
            array('label' => 'pH',         'data' => $phS),
        );
        foreach ($jarRows as $jr) {
            $row++;
            $sheet->setCellValue('A' . $row, $jr['label']);
            for ($i = 0; $i < 6; $i++) {
                $v = isset($jr['data'][$i]) ? $jr['data'][$i] : '';
                $sheet->setCellValue($cols[$i] . $row, $v);
            }
            $this->styleDataRow($sheet, 'A' . $row . ':G' . $row, false);
        }

        $row++;
        $sheet->setCellValue('A' . $row, 'Liều chọn:');
        $sheet->setCellValue('B' . $row, isset($cln->jar_s_chon) ? $cln->jar_s_chon : '');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        // Jar test Ca Chiều
        $row += 2;
        $sheet->mergeCells('A' . $row . ':L' . $row);
        $sheet->setCellValue('A' . $row, 'JAR TEST CA CHIỀU — Giờ lấy mẫu: ' . (isset($cln->jar_gio_chieu) ? $cln->jar_gio_chieu : ''));
        $this->styleHeaderGroup($sheet, 'A' . $row . ':L' . $row, self::COLOR_HEADER_MID);

        $row++;
        $sheet->setCellValue('A' . $row, 'Cốc số');
        for ($i = 1; $i <= 6; $i++) {
            $sheet->setCellValue($cols[$i - 1] . $row, 'Cốc ' . $i);
        }
        $this->styleHeaderGroup($sheet, 'A' . $row . ':G' . $row, self::COLOR_HEADER_LIGHT);

        $pacC = isset($cln->jar_c_pac) ? (is_array($cln->jar_c_pac) ? $cln->jar_c_pac : json_decode($cln->jar_c_pac, true)) : array();
        $ntuC = isset($cln->jar_c_ntu) ? (is_array($cln->jar_c_ntu) ? $cln->jar_c_ntu : json_decode($cln->jar_c_ntu, true)) : array();
        $phC  = isset($cln->jar_c_ph)  ? (is_array($cln->jar_c_ph)  ? $cln->jar_c_ph  : json_decode($cln->jar_c_ph, true))  : array();

        $jarRowsC = array(
            array('label' => 'PAC (mg/L)', 'data' => $pacC),
            array('label' => 'NTU',        'data' => $ntuC),
            array('label' => 'pH',         'data' => $phC),
        );
        foreach ($jarRowsC as $jr) {
            $row++;
            $sheet->setCellValue('A' . $row, $jr['label']);
            for ($i = 0; $i < 6; $i++) {
                $v = isset($jr['data'][$i]) ? $jr['data'][$i] : '';
                $sheet->setCellValue($cols[$i] . $row, $v);
            }
            $this->styleDataRow($sheet, 'A' . $row . ':G' . $row, false);
        }

        $row++;
        $sheet->setCellValue('A' . $row, 'Liều chọn:');
        $sheet->setCellValue('B' . $row, isset($cln->jar_c_chon) ? $cln->jar_c_chon : '');
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        // Người trực
        $row += 2;
        $sheet->setCellValue('A' . $row, 'Người trực ca sáng:');
        $sheet->setCellValue('C' . $row, isset($cln->nguoi_truc_sang) ? $cln->nguoi_truc_sang : '');
        $row++;
        $sheet->setCellValue('A' . $row, 'Người trực ca chiều:');
        $sheet->setCellValue('C' . $row, isset($cln->nguoi_truc_chieu) ? $cln->nguoi_truc_chieu : '');
        $row++;
        $sheet->setCellValue('A' . $row, 'Người kiểm tra:');
        $sheet->setCellValue('C' . $row, isset($cln->nguoi_kt) ? $cln->nguoi_kt : '');

        // Column widths sheet 2
        $sheet->getColumnDimension('A')->setWidth(16);
        foreach ($cols as $c) {
            $sheet->getColumnDimension($c)->setWidth(12);
        }
    }

    // ===== HELPERS =====

    private function val($v)
    {
        return ($v !== null && $v !== '') ? $v : '';
    }

    private function styleTitle($sheet, $range, $size, $bold, $bgColor = null)
    {
        $style = $sheet->getStyle($range);
        $style->getFont()->setSize($size)->setBold($bold)->setName('Arial');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        if ($bgColor) {
            $style->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB($bgColor);
            $style->getFont()->getColor()->setARGB('FFFFFFFF');
        }
        // Chiều cao dòng
        $firstRow = (int) preg_replace('/[^0-9]/', '', explode(':', $range)[0]);
        $sheet->getRowDimension($firstRow)->setRowHeight(22);
    }

    private function styleHeaderGroup($sheet, $range, $bgColor)
    {
        $style = $sheet->getStyle($range);
        $style->getFont()->setBold(true)->setSize(10)->setName('Arial');
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)->setWrapText(true);
        $style->getFill()->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB($bgColor);
        if ($bgColor !== self::COLOR_HEADER_LIGHT) {
            $style->getFont()->getColor()->setARGB('FFFFFFFF');
        }
    }

    private function styleDataRow($sheet, $range, $alt)
    {
        $style = $sheet->getStyle($range);
        $style->getFont()->setName('Arial')->setSize(10);
        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);
        if ($alt) {
            $style->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFF5F9FF');
        }
    }

    private function applyBorderAll($sheet, $range)
    {
        $sheet->getStyle($range)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($range)->getBorders()->getOutline()->setBorderStyle(Border::BORDER_MEDIUM);
    }

    private function highlightQcvn($sheet, $val, $cell, $min, $max)
    {
        if ($val === null || $val === '') return;
        $fail = false;
        if ($min !== null && $val < $min) $fail = true;
        if ($max !== null && $val > $max) $fail = true;
        if ($fail) {
            $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB(self::COLOR_FAIL);
            $sheet->getStyle($cell)->getFont()->getColor()->setARGB('FF990000');
        }
    }
}