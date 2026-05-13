<?php

namespace App\Support;

use App\Models\Post;
use App\Models\Trash;
use Illuminate\Support\HtmlString;

class PostLocaleStatus
{
    public static function forPost(Post $post): array
    {
        $status = self::emptyStatus();

        if ($post->translation_group_id) {
            Post::query()
                ->where('translation_group_id', $post->translation_group_id)
                ->pluck('locale')
                ->each(function (?string $locale) use (&$status): void {
                    if (array_key_exists($locale, $status)) {
                        $status[$locale] = true;
                    }
                });
        }

        if (array_key_exists($post->locale, $status)) {
            $status[$post->locale] = true;
        }

        return $status;
    }

    public static function forTrash(Trash $trash): ?array
    {
        if ($trash->model_type !== Post::class && $trash->model_label !== 'Posts') {
            return null;
        }

        $data = $trash->model_data ?? [];
        $status = self::emptyStatus();
        $locale = $data['locale'] ?? null;

        if (array_key_exists($locale, $status)) {
            $status[$locale] = true;
        }

        return $status;
    }

    public static function indicator(array $status): HtmlString
    {
        $items = collect(self::labels())
            ->map(fn (string $label, string $locale): string => self::indicatorItem($label, $status[$locale] ?? false))
            ->implode('');

        return new HtmlString('<div style="display:flex;gap:10px;align-items:center;">' . $items . '</div>');
    }

    public static function labelWithIndicator(string $label, ?array $status): HtmlString
    {
        $html = '<div style="display:flex;align-items:center;gap:10px;">';
        $html .= self::badge($label, '#ffffff', '#374151', '#e5e7eb');

        if ($status !== null) {
            foreach ($status as $locale => $exists) {
                if (! $exists) {
                    continue;
                }

                $html .= self::badge(self::badgeLabel($locale), self::badgeBackground($locale), self::badgeColor($locale), self::badgeBorder($locale));
            }
        }

        $html .= '</div>';

        return new HtmlString($html);
    }

    private static function emptyStatus(): array
    {
        return collect(array_keys(Post::LOCALES))
            ->mapWithKeys(fn (string $locale): array => [$locale => false])
            ->all();
    }

    private static function labels(): array
    {
        return [
            'ru' => 'ru',
            'en' => 'eng',
            'sr' => 'sr',
        ];
    }

    private static function badgeLabel(string $locale): string
    {
        return [
            'ru' => 'RU',
            'en' => 'EN',
            'sr' => 'SR',
        ][$locale] ?? strtoupper($locale);
    }

    private static function badge(string $label, string $background, string $color, string $borderColor): string
    {
        return '<span style="display:inline-flex;align-items:center;border:1px solid ' . e($borderColor) . ';border-radius:6px;padding:2px 8px;font-size:12px;color:' . e($color) . ';background:' . e($background) . ';">' . e($label) . '</span>';
    }

    private static function badgeBackground(string $locale): string
    {
        return match ($locale) {
            'ru' => '#fee2e2',
            'en' => '#dbeafe',
            'sr' => '#dcfce7',
            default => '#f3f4f6',
        };
    }

    private static function badgeColor(string $locale): string
    {
        return match ($locale) {
            'ru' => '#dc2626',
            'en' => '#2563eb',
            'sr' => '#16a34a',
            default => '#374151',
        };
    }

    private static function badgeBorder(string $locale): string
    {
        return match ($locale) {
            'ru' => '#fecaca',
            'en' => '#bfdbfe',
            'sr' => '#bbf7d0',
            default => '#e5e7eb',
        };
    }

    private static function indicatorItem(string $label, bool $exists): string
    {
        $borderColor = $exists ? '#22c55e' : '#d1d5db';
        $background = $exists ? '#dcfce7' : '#ffffff';
        $color = $exists ? '#16a34a' : 'transparent';
        $mark = $exists ? '&#10003;' : '&nbsp;';

        return <<<HTML
<span style="display:inline-flex;flex-direction:column;align-items:center;gap:3px;line-height:1;">
    <span style="font-size:10px;text-transform:uppercase;color:#6b7280;">{$label}</span>
    <span style="display:inline-flex;width:18px;height:18px;align-items:center;justify-content:center;border:1px solid {$borderColor};border-radius:4px;background:{$background};color:{$color};font-size:12px;font-weight:700;">{$mark}</span>
</span>
HTML;
    }
}
