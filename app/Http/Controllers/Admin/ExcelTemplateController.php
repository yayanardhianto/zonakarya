<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Illuminate\Http\Request;

class ExcelTemplateController extends Controller
{
    public function downloadTestQuestionTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set headers
        $headers = [
            'A1' => 'question_text',
            'B1' => 'question_type',
            'C1' => 'points',
            'D1' => 'option_1',
            'E1' => 'option_2',
            'F1' => 'option_3',
            'G1' => 'option_4',
            'H1' => 'correct_answer'
        ];
        
        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        
        // Add sample data
        $sampleData = [
            ['Apa yang dimaksud dengan komunikasi efektif?', 'essay', 5, '', '', '', '', ''],
            ['Manakah yang merupakan contoh komunikasi verbal?', 'multiple_choice', 3, 'Berbicara langsung', 'Menggunakan bahasa tubuh', 'Mengirim email', 'Semua benar', 1],
            ['Jelaskan pentingnya teamwork dalam lingkungan kerja', 'essay', 5, '', '', '', '', ''],
            ['Kemampuan manakah yang paling penting untuk seorang leader?', 'multiple_choice', 4, 'Komunikasi', 'Problem solving', 'Delegasi', 'Semua penting', 4]
        ];
        
        $row = 2;
        foreach ($sampleData as $data) {
            $col = 'A';
            foreach ($data as $value) {
                $sheet->setCellValue($col . $row, $value);
                $col++;
            }
            $row++;
        }
        
        // Style the header row
        $headerRange = 'A1:H1';
        $sheet->getStyle($headerRange)->getFont()->setBold(true);
        $sheet->getStyle($headerRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFE2E2E2');
        $sheet->getStyle($headerRange)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);
        $sheet->getStyle($headerRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
        
        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Add instructions
        $sheet->setCellValue('A6', 'INSTRUKSI:');
        $sheet->setCellValue('A7', '1. question_type: essay atau multiple_choice');
        $sheet->setCellValue('A8', '2. points: angka positif (contoh: 5)');
        $sheet->setCellValue('A9', '3. Untuk multiple_choice: isi option_1 sampai option_4');
        $sheet->setCellValue('A10', '4. correct_answer: 1-4 (menunjukkan option mana yang benar)');
        $sheet->setCellValue('A11', '5. Untuk essay: biarkan option_1 sampai option_4 kosong');
        
        // Style instructions
        $instructionRange = 'A6:A11';
        $sheet->getStyle($instructionRange)->getFont()->setBold(true);
        $sheet->getStyle($instructionRange)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('FFFFE6CC');
        
        $writer = new Xlsx($spreadsheet);
        
        $filename = 'test-questions-import-template.xlsx';
        $tempFile = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename)->deleteFileAfterSend(true);
    }
}
