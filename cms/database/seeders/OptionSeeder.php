<?php

namespace Database\Seeders;

use App\Models\Option;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OptionSeeder extends Seeder
{
    public function run(): void
    {
        $options = [
            // Соцсети
            ['key' => 'social_github',   'label' => 'GitHub',   'group' => 'social', 'value' => 'https://github.com/yourusername'],
            ['key' => 'social_telegram', 'label' => 'Telegram', 'group' => 'social', 'value' => 'https://t.me/yourusername'],
            ['key' => 'social_email',    'label' => 'Email',    'group' => 'social', 'value' => 'mailto:you@example.com'],
            ['key' => 'social_vk',       'label' => 'ВКонтакте', 'group' => 'social', 'value' => ''],
            ['key' => 'social_habr',     'label' => 'Хабр',      'group' => 'social', 'value' => ''],
            ['key' => 'social_hh',       'label' => 'HH.ru',     'group' => 'social', 'value' => ''],

            // Главная страница
            ['key' => 'hero_resume_url',    'label' => 'Ссылка на резюме',    'group' => 'hero', 'value' => '/files/resume.pdf'],
            ['key' => 'hero_portfolio_url', 'label' => 'Ссылка на портфолио', 'group' => 'hero', 'value' => '/#portfolio'],
            ['key' => 'hero_hire_url',      'label' => 'Ссылка "Нанять"',     'group' => 'hero', 'value' => '/contacts'],

            // Контакты
            ['key' => 'contact_email', 'label' => 'Почта',    'group' => 'contacts', 'value' => 'you@example.com'],
            ['key' => 'contact_phone', 'label' => 'Телефон',  'group' => 'contacts', 'value' => '+7 (000) 000-00-00'],
            ['key' => 'contact_city',  'label' => 'Город',    'group' => 'contacts', 'value' => 'Пермь, Россия · Удалённая работа'],

            // Общее
            ['key' => 'site_copyright', 'label' => 'Копирайт футера', 'group' => 'general', 'value' => '© 2025 Михаил Божеслав'],
        ];

        foreach ($options as $option) {
            Option::updateOrCreate(['key' => $option['key']], $option);
        }
    }
}
