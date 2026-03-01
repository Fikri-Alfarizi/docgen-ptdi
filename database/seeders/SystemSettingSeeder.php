<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class SystemSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            'app_name' => 'DOCGEN PTDI',
            'company_name' => 'PT Dirgantara Indonesia',
            'app_logo' => null, // Default to CSS/Asset if null
            'app_favicon' => null,
            'footer_text' => 'Sistem Informasi Dokumen - PT Dirgantara Indonesia.',
        ];

        foreach ($settings as $key => $value) {
            SystemSetting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
