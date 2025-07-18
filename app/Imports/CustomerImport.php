<?php
namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    protected $branch_id,$date;

    public function __construct($branch_id = null, $date = null)
    {
        $this->branch_id = $branch_id ?? auth()->user()->branch_id;
        $this->date = $date ?? now()->format('Y-m-d');
    }

    public function model(array $row)
    {
        // Skip the row if phone is empty
        if (empty($row['phone']) || empty($row['name'])) {
            return null;  // Skip this row if the phone number is empty
        }

        // Check if the customer with the same phone number already exists
        $existingCustomer = Customer::where('phone', $row['phone'])->first();

        if (!$existingCustomer) {
            return new Customer([
                'name' => $row['name'],
                'phone' => $row['phone'], // Ensure phone is not null
                'branch_id' => $this->branch_id,
                'address' => $row['address'] ?? null,
                'date' => $this->date, // Use the provided date or the current date
            ]);
        }

        // Return null to skip duplicate entries based on phone
        return null;
    }


}
