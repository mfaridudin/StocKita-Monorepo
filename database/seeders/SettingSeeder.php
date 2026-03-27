<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'app.name' => 'StocKita',
            'app.description' => 'Sistem kasir modern',

            'store.name' => 'Stoc Kita',
            'store.email' => 'StocKita@email.com',
            'store.phone' => '08123456789',
            'store.address' => 'Jl. Pandanaran No. 123, Semarang, Jawa Tengah, 50241, Indonesia',

            'subscription.plan' => 'free',
            'subscription.product_limit' => '100',

            'email.welcome' => 'Halo {{name}},

            Selamat datang di {{store.name}} 👋

            Terima kasih sudah bergabung dengan kami. Kami senang bisa melayani Anda.

            Jika ada pertanyaan, silakan hubungi kami kapan saja.

            Salam,
            {{store.name}}',
        ];

        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
