<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run()
    {
        EmailTemplate::create([
            'key' => 'low_stock',
            'subject' => 'Stok Menipis',
            'body' => "Halo {{ name }},\n\nStok produk {{ product_name }} tinggal {{ stock }}.\n\n- {{ store_name }}"
        ]);

        EmailTemplate::create([
            'key' => 'welcome_email',
            'subject' => 'Selamat Datang',
            'body' => "Halo {{ name }},\n\nSelamat datang di {{ store_name }}!"
        ]);
    }
}
