<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Customer::with('branch')->get(); // Only retrieve customers without the branch relationship
    }

    /**
     * Define the headings for the export file
     */
    public function headings(): array
    {
        return [
            'ID',
            'Name',
            'Phone',
            'Address',
            'Date',
            'Branch',
        ];
    }

    /**
     * Map the data for export
     */
    public function map($customer): array
    {
        return [
            $customer->id,               // ID
            $customer->name,              // Name
            $customer->phone,             // Phone
            $customer->address,           // Address
            $customer->date,              // Date
            $customer->branch ? $customer->branch->name : 'N/A',
        ];
    }



}
