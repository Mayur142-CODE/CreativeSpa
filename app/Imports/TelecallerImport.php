<?php

namespace App\Imports;

use App\Models\Telecaller;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Str;

class TelecallerImport implements ToModel
{
    protected $branchId;

    public function __construct($branchId)
    {
        $this->branchId = $branchId;
    }

    public function model(array $row)
    {
        // Normalize and trim all values
        $row = array_map(fn($value) => trim((string) $value), $row);

        // Extract phone and name from the row
        $phone = $this->extractPhone($row);
        $name = $this->extractName($row);

        // Skip if phone is invalid or already exists
        if (!$phone || Telecaller::where('phone_number', $phone)->exists()) {
            return null;
        }

        return new Telecaller([
            'name' => $name ?? 'NA',
            'phone_number' => $phone,
            'branch_id' => $this->branchId,
        ]);
    }

    private function extractPhone(array $row): ?string
    {
        foreach ($row as $cell) {
            // Remove non-digit characters
            $cleaned = preg_replace('/\D/', '', $cell);

            // Strictly allow only 10-digit numbers starting with 7, 8, or 9
            if (preg_match('/^[789]\d{9}$/', $cleaned)) {
                return $cleaned;
            }
        }
        return null;
    }

    private function extractName(array $row): ?string
    {
        foreach ($row as $cell) {
            if (
                !is_numeric($cell) &&
                !preg_match('/^\d{4,}$/', $cell) && // Likely a serial or date
                !Str::contains($cell, ['@', '/', '-', '202', '19', '20']) &&
                strlen($cell) > 1
            ) {
                return $cell;
            }
        }
        return 'NA';
    }
}
