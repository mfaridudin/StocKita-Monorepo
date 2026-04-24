<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomerExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Customer::with(['user'])->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Email',
            'No Telepon',
            'Tipe',
            'Status',
            'Toko',
            'Status Aktif',
            'Tanggal Dibuat',
        ];
    }

    public function map($customer): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $customer->user->name,
            $customer->user->email,
            $customer->formatted_phone,
            $customer->type,
            $customer->status,
            $customer->store->name,
            $customer->is_active ? 'Aktif' : 'Non-Aktif',
            $customer->created_at->format('d-m-Y')
        ];
    }
}
