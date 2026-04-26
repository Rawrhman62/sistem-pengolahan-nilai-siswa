<?php

namespace App\Services;

use InvalidArgumentException;

class ExcelTemplateService
{
    /**
     * Get the complete template configuration for a given type
     *
     * @param string $type The template type (users, siswa, guru, mapel, kelas)
     * @return array The complete template configuration
     * @throws InvalidArgumentException If template type doesn't exist
     */
    public function getTemplate(string $type): array
    {
        $templates = config('excel-templates');
        
        if (!isset($templates[$type])) {
            throw new InvalidArgumentException("Template type '{$type}' not found");
        }
        
        return $templates[$type];
    }

    /**
     * Get the column mappings for a given template type
     *
     * @param string $type The template type
     * @return array Column mappings (field_name => display_name)
     */
    public function getColumns(string $type): array
    {
        $template = $this->getTemplate($type);
        return $template['columns'] ?? [];
    }

    /**
     * Get the required fields for a given template type
     *
     * @param string $type The template type
     * @return array List of required field names
     */
    public function getRequiredFields(string $type): array
    {
        $template = $this->getTemplate($type);
        return $template['required'] ?? [];
    }

    /**
     * Get the validation rules for a given template type
     *
     * @param string $type The template type
     * @return array Validation rules (field_name => rules)
     */
    public function getValidationRules(string $type): array
    {
        $template = $this->getTemplate($type);
        return $template['validation'] ?? [];
    }

    /**
     * Get the header styling configuration for a given template type
     *
     * @param string $type The template type
     * @return array Header style configuration
     */
    public function getHeaderStyle(string $type): array
    {
        $template = $this->getTemplate($type);
        return $template['header_style'] ?? [
            'font_bold' => true,
            'background_color' => 'FFE2E8F0',
            'font_color' => 'FF0A2E5C',
        ];
    }
}

