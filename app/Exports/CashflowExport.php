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
        $cashflows = Cashflow::MosqueUser()
            ->select('date', 'category', 'description', 'type', 'amount')
            ->orderBy('date', 'desc')
            ->get();

        // Format the amount and translate the type and category
        $cashflows->transform(function ($item) {
            $item->amount = formatRM($item->amount); // Apply formatRM to the amount

            // Translate the type and capitalize the first letter
            $item->type = ucfirst(__('cashflow.' . strtolower($item->type)));

            // Translate the category and capitalize the first letter
            $item->category = ucfirst(__('cashflow.' . strtolower($item->category)));

            return $item;
        });

        return $cashflows;
    }

    /**
     * Define the headings for the Excel file.
     */
    public function headings(): array
    {
        return [
            __('cashflow.date'), // Translated string for "Date"
            __('cashflow.category'), // Translated string for "Category"
            __('cashflow.description'), // Translated string for "Description"
            __('cashflow.type'), // Translated string for "Type"
            __('cashflow.amount'), // Translated string for "Amount (RM)"
        ];
    }
}
