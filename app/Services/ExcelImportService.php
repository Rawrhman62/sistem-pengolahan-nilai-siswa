<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use InvalidArgumentException;

class ExcelImportService
{
    public function __construct(
        private ExcelTemplateService $templateService
    ) {}

    /**
     * Import data from an Excel file using a template configuration
     *
     * @param UploadedFile $file The uploaded Excel file
     * @param string $templateType The template type to use for validation
     * @return ImportResult The result of the import operation
     * @throws InvalidArgumentException If file type is invalid
     */
    public function import(UploadedFile $file, string $templateType): ImportResult
    {
        // Validate file type
        $this->validateFileType($file);

        // Load the Excel file
        $spreadsheet = IOFactory::load($file->getPathname());
        $worksheet = $spreadsheet->getActiveSheet();

        // Get template configuration
        $template = $this->templateService->getTemplate($templateType);
        $columns = $template['columns'];
        $validationRules = $template['validation'];

        // Validate headers
        $headers = $this->extractHeaders($worksheet);
        $headerValidation = $this->validateHeaders($headers, $columns);
        
        if (!$headerValidation['valid']) {
            return new ImportResult(
                successCount: 0,
                errorCount: 0,
                errors: ['headers' => $headerValidation['errors']],
                data: collect()
            );
        }

        // Parse and validate rows
        $data = collect();
        $errors = [];
        $successCount = 0;
        $errorCount = 0;

        $rows = $this->parseRows($worksheet, $columns);

        foreach ($rows as $rowNumber => $row) {
            $rowErrors = $this->validateRow($row, $rowNumber, $validationRules);
            
            if (empty($rowErrors)) {
                $data->push($row);
                $successCount++;
            } else {
                $errors["row_{$rowNumber}"] = $rowErrors;
                $errorCount++;
            }
        }

        return new ImportResult(
            successCount: $successCount,
            errorCount: $errorCount,
            errors: $errors,
            data: $data
        );
    }

    /**
     * Validate that the uploaded file is a valid Excel file
     *
     * @param UploadedFile $file
     * @throws InvalidArgumentException
     */
    private function validateFileType(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $allowedExtensions = ['xlsx', 'xls'];

        if (!in_array($extension, $allowedExtensions)) {
            throw new InvalidArgumentException(
                'Invalid file type. Only .xlsx and .xls files are allowed.'
            );
        }

        // Additional MIME type validation
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = [
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.ms-excel',
            'application/octet-stream'
        ];

        if (!in_array($mimeType, $allowedMimeTypes)) {
            throw new InvalidArgumentException(
                'Invalid file format. Please upload a valid Excel file.'
            );
        }
    }

    /**
     * Extract headers from the first row of the worksheet
     *
     * @param Worksheet $worksheet
     * @return array
     */
    private function extractHeaders(Worksheet $worksheet): array
    {
        $headers = [];
        $highestColumn = $worksheet->getHighestColumn();
        $columnIndex = 'A';

        while ($columnIndex <= $highestColumn) {
            $cellValue = $worksheet->getCell($columnIndex . '1')->getValue();
            if ($cellValue !== null && $cellValue !== '') {
                $headers[] = trim($cellValue);
            }
            $columnIndex++;
        }

        return $headers;
    }

    /**
     * Validate that the Excel headers match the expected template columns
     *
     * @param array $headers The headers from the Excel file
     * @param array $expectedColumns The expected column mappings from template
     * @return array Validation result with 'valid' boolean and 'errors' array
     */
    private function validateHeaders(array $headers, array $expectedColumns): array
    {
        $expectedHeaders = array_values($expectedColumns);
        $missingHeaders = [];
        $extraHeaders = [];

        // Check for missing required headers
        foreach ($expectedHeaders as $expectedHeader) {
            if (!in_array($expectedHeader, $headers)) {
                $missingHeaders[] = $expectedHeader;
            }
        }

        // Check for extra headers that don't match template
        foreach ($headers as $header) {
            if (!in_array($header, $expectedHeaders)) {
                $extraHeaders[] = $header;
            }
        }

        $errors = [];
        if (!empty($missingHeaders)) {
            $errors[] = 'Missing required columns: ' . implode(', ', $missingHeaders);
        }
        if (!empty($extraHeaders)) {
            $errors[] = 'Unexpected columns found: ' . implode(', ', $extraHeaders);
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Parse all data rows from the worksheet
     *
     * @param Worksheet $worksheet
     * @param array $columns Column mappings (field_name => display_name)
     * @return array Array of rows with row numbers as keys
     */
    private function parseRows(Worksheet $worksheet, array $columns): array
    {
        $rows = [];
        $highestRow = $worksheet->getHighestRow();
        $columnMapping = $this->createColumnMapping($worksheet, $columns);

        // Start from row 2 (skip header row)
        for ($rowIndex = 2; $rowIndex <= $highestRow; $rowIndex++) {
            $rowData = [];
            $hasData = false;

            foreach ($columnMapping as $fieldName => $columnLetter) {
                $cellValue = $worksheet->getCell($columnLetter . $rowIndex)->getValue();
                
                // Convert cell value to string and trim
                $cellValue = $cellValue !== null ? trim((string) $cellValue) : null;
                
                // Check if row has any data
                if ($cellValue !== null && $cellValue !== '') {
                    $hasData = true;
                }

                $rowData[$fieldName] = $cellValue;
            }

            // Only include rows that have at least some data
            if ($hasData) {
                $rows[$rowIndex] = $rowData;
            }
        }

        return $rows;
    }

    /**
     * Create a mapping of field names to column letters
     *
     * @param Worksheet $worksheet
     * @param array $columns Column mappings (field_name => display_name)
     * @return array Mapping of field_name => column_letter
     */
    private function createColumnMapping(Worksheet $worksheet, array $columns): array
    {
        $headers = $this->extractHeaders($worksheet);
        $mapping = [];

        $columnIndex = 'A';
        foreach ($headers as $header) {
            // Find which field this header corresponds to
            foreach ($columns as $fieldName => $displayName) {
                if ($header === $displayName) {
                    $mapping[$fieldName] = $columnIndex;
                    break;
                }
            }
            $columnIndex++;
        }

        return $mapping;
    }

    /**
     * Validate a single row of data using template validation rules
     *
     * @param array $row The row data
     * @param int $rowNumber The row number in the Excel file
     * @param array $rules Validation rules from template
     * @return array Array of validation error messages (empty if valid)
     */
    private function validateRow(array $row, int $rowNumber, array $rules): array
    {
        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }
            return $errors;
        }

        return [];
    }
}

/**
 * Class to represent the result of an Excel import operation
 */
class ImportResult
{
    public function __construct(
        public int $successCount,
        public int $errorCount,
        public array $errors,
        public Collection $data
    ) {}

    /**
     * Check if the import was completely successful
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->errorCount === 0 && empty($this->errors);
    }

    /**
     * Check if there were any errors
     *
     * @return bool
     */
    public function hasErrors(): bool
    {
        return $this->errorCount > 0 || !empty($this->errors);
    }

    /**
     * Get a formatted error message for display
     *
     * @return string
     */
    public function getErrorMessage(): string
    {
        if (!$this->hasErrors()) {
            return '';
        }

        $messages = [];

        // Add header errors if present
        if (isset($this->errors['headers'])) {
            $messages[] = 'Header validation failed:';
            foreach ($this->errors['headers'] as $headerError) {
                $messages[] = '  - ' . $headerError;
            }
        }

        // Add row errors
        foreach ($this->errors as $key => $rowErrors) {
            if ($key === 'headers') {
                continue;
            }

            if (is_array($rowErrors)) {
                $rowNumber = str_replace('row_', '', $key);
                $messages[] = "Row {$rowNumber}:";
                foreach ($rowErrors as $error) {
                    $messages[] = '  - ' . $error;
                }
            }
        }

        return implode("\n", $messages);
    }
}
