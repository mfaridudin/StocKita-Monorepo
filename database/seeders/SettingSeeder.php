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
