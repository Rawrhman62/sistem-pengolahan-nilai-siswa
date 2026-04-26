<?php

namespace App\Services;

use Illuminate\Support\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelExportService
{
    public function __construct(
        private ExcelTemplateService $templateService
    ) {}

    /**
     * Export data to Excel file using template configuration
     *
     * @param Collection $data The data to export
     * @param string $templateType The template type (users, siswa, guru, mapel, kelas)
     * @param string $filename The output filename
     * @return BinaryFileResponse
     */
    public function export(
        Collection $data,
        string $templateType,
        string $filename
    ): BinaryFileResponse {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Get template configuration
        $columns = $this->templateService->getColumns($templateType);
        $headerStyle = $this->templateService->getHeaderStyle($templateType);
        
        // Set headers
        $this->formatHeaders($sheet, $columns, $headerStyle);
        
        // Populate data rows
        $this->populateRows($sheet, $data, array_keys($columns));
        
        // Auto-size columns
        $this->autoSizeColumns($sheet, count($columns));
        
        // Write to file
        $writer = new Xlsx($spreadsheet);
        $tempFile = tempnam(sys_get_temp_dir(), 'excel_');
        $writer->save($tempFile);
        
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    /**
     * Format header row with styling
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param array $columns
     * @param array $headerStyle
     * @return void
     */
    private function formatHeaders($sheet, array $columns, array $headerStyle): void
    {
        $columnIndex = 1;
        
        foreach ($columns as $displayName) {
            $cellCoordinate = $this->getColumnLetter($columnIndex) . '1';
            $sheet->setCellValue($cellCoordinate, $displayName);
            
            // Apply header styling
            $style = $sheet->getStyle($cellCoordinate);
            
            // Font styling
            if ($headerStyle['font_bold'] ?? false) {
                $style->getFont()->setBold(true);
            }
            
            if (isset($headerStyle['font_color'])) {
                $style->getFont()->getColor()->setARGB($headerStyle['font_color']);
            }
            
            // Background color
            if (isset($headerStyle['background_color'])) {
                $style->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB($headerStyle['background_color']);
            }
            
            $columnIndex++;
        }
    }

    /**
     * Populate data rows
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param Collection $data
     * @param array $columnKeys
     * @return void
     */
    private function populateRows($sheet, Collection $data, array $columnKeys): void
    {
        $rowIndex = 2; // Start after header row
        
        foreach ($data as $item) {
            $columnIndex = 1;
            
            foreach ($columnKeys as $key) {
                $cellCoordinate = $this->getColumnLetter($columnIndex) . $rowIndex;
                
                // Get value from item (support both array and object)
                $value = is_array($item) ? ($item[$key] ?? '') : ($item->$key ?? '');
                
                $sheet->setCellValue($cellCoordinate, $value);
                $columnIndex++;
            }
            
            $rowIndex++;
        }
    }

    /**
     * Auto-size all columns
     *
     * @param \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet
     * @param int $columnCount
     * @return void
     */
    private function autoSizeColumns($sheet, int $columnCount): void
    {
        for ($i = 1; $i <= $columnCount; $i++) {
            $columnLetter = $this->getColumnLetter($i);
            $sheet->getColumnDimension($columnLetter)->setAutoSize(true);
        }
    }

    /**
     * Convert column index to letter (1 = A, 2 = B, etc.)
     *
     * @param int $index
     * @return string
     */
    private function getColumnLetter(int $index): string
    {
        $letter = '';
        
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = intdiv($index, 26);
        }
        
        return $letter;
    }

    /**
     * Export users data to Excel
     *
     * @param Collection $data
     * @return BinaryFileResponse
     */
    public function exportUsers(Collection $data): BinaryFileResponse
    {
        $filename = 'Users_' . date('Y-m-d') . '.xlsx';
        return $this->export($data, 'users', $filename);
    }

    /**
     * Export siswa data to Excel
     *
     * @param Collection $data
     * @return BinaryFileResponse
     */
    public function exportSiswa(Collection $data): BinaryFileResponse
    {
        $filename = 'Siswa_' . date('Y-m-d') . '.xlsx';
        return $this->export($data, 'siswa', $filename);
    }

    /**
     * Export guru data to Excel
     *
     * @param Collection $data
     * @return BinaryFileResponse
     */
    public function exportGuru(Collection $data): BinaryFileResponse
    {
        $filename = 'Guru_' . date('Y-m-d') . '.xlsx';
        return $this->export($data, 'guru', $filename);
    }

    /**
     * Export mapel data to Excel
     *
     * @param Collection $data
     * @return BinaryFileResponse
     */
    public function exportMapel(Collection $data): BinaryFileResponse
    {
        $filename = 'Mapel_' . date('Y-m-d') . '.xlsx';
        return $this->export($data, 'mapel', $filename);
    }

    /**
     * Export kelas data to Excel
     *
     * @param Collection $data
     * @return BinaryFileResponse
     */
    public function exportKelas(Collection $data): BinaryFileResponse
    {
        $filename = 'Kelas_' . date('Y-m-d') . '.xlsx';
        return $this->export($data, 'kelas', $filename);
    }
}
