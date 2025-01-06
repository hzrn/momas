<?php

namespace App\Exports;

use App\Models\Cashflow;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CashflowExport implements FromCollection, WithHeadings
{
    /**
     * Return the data to be exported.
     */
    public function collection()
    {
        return Cashflow::MosqueUser()
            ->select('date', 'category', 'description', 'type', 'amount')
            ->orderBy('date', 'desc')
            ->get();
    }

    /**
     * Define the headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            'Date',
            'Category',
            'Description',
            'Type',
            'Amount',
        ];
    }
}
