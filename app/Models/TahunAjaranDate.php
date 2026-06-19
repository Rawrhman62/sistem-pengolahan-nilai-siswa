<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TahunAjaranDate extends Model
{
    use HasFactory;

    protected $table = 'tb_tanggal_tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'tanggal_mulai',
        'tanggal_selesai',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Get the current active academic year based on today's date
     */
    public static function getCurrentTahunAjaran(): ?self
    {
        $today = Carbon::today();
        
        return static::where('tanggal_mulai', '<=', $today)
            ->where('tanggal_selesai', '>=', $today)
            ->first();
    }

    /**
     * Get the current semester (1 = Ganjil / Odd, 2 = Genap / Even)
     * Semester Ganjil typically runs July-December
     * Semester Genap typically runs January-June
     */
    public static function getCurrentSemester(): int
    {
        $month = Carbon::now()->month;
        
        // July (7) to December (12) = Semester Ganjil (1)
        // January (1) to June (6) = Semester Genap (2)
        return ($month >= 7 && $month <= 12) ? 1 : 2;
    }

    /**
     * Get formatted semester string (e.g., "1/26" for Semester 1 of 2025/2026)
     */
    public static function getCurrentSemesterFormatted(): string
    {
        $tahunAjaran = static::getCurrentTahunAjaran();
        if (!$tahunAjaran) {
            return '1/' . substr(date('Y'), -2);
        }

        $semester = static::getCurrentSemester();
        $tahunParts = explode('/', $tahunAjaran->tahun_ajaran);
        $tahunShort = substr($tahunParts[0], -2); // Get last 2 digits of first year
        
        return $semester . '/' . $tahunShort;
    }

    /**
     * Check if a new academic year should start automatically
     * This should be run daily or on system access
     */
    public static function autoCreateNextYear(): void
    {
        $currentYear = static::getCurrentTahunAjaran();
        
        if (!$currentYear) {
            return;
        }

        $today = Carbon::today();
        
        // If current academic year has ended, create next year automatically
        if ($today->greaterThan($currentYear->tanggal_selesai)) {
            $currentYearParts = explode('/', $currentYear->tahun_ajaran);
            $nextYearStart = (int)$currentYearParts[0] + 1;
            $nextYearEnd = (int)$currentYearParts[1] + 1;
            $nextTahunAjaran = $nextYearStart . '/' . $nextYearEnd;

            // Check if next year already exists
            $exists = static::where('tahun_ajaran', $nextTahunAjaran)->exists();
            
            if (!$exists) {
                static::create([
                    'tahun_ajaran' => $nextTahunAjaran,
                    'tanggal_mulai' => Carbon::create($nextYearStart, 7, 1), // July 1st
                    'tanggal_selesai' => Carbon::create($nextYearEnd, 6, 30), // June 30th
                ]);
            }
        }
    }
}
