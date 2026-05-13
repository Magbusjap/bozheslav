<?php

use Illuminate\Support\Facades\Process;

if (!function_exists('option')) {
    function option(string $key, ?string $default = null): ?string
    {
        return \App\Models\Option::get($key, $default);
    }
}


if (!function_exists('transliterate')) {
    function transliterate(string $text): string
    {
        $rus = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я','А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П','Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'];
        $lat = ['a','b','v','g','d','e','yo','zh','z','i','y','k','l','m','n','o','p','r','s','t','u','f','kh','ts','ch','sh','shch','','y','','e','yu','ya','A','B','V','G','D','E','Yo','Zh','Z','I','Y','K','L','M','N','O','P','R','S','T','U','F','Kh','Ts','Ch','Sh','Shch','','Y','','E','Yu','Ya'];
        return str_replace($rus, $lat, $text);
    }
}


if (!function_exists('compileMjml')) {
    function compileMjml($mjmlCode) {
        if (empty($mjmlCode)) return '';

        // Если это уже HTML, возвращаем как есть
        if (str_contains($mjmlCode, '<html')) return $mjmlCode;

        $tmpPath = storage_path('app/temp_' . md5($mjmlCode) . '.mjml');
        file_put_contents($tmpPath, $mjmlCode);

        $mjmlBin = base_path('node_modules/.bin/mjml');

        if (! is_file($mjmlBin)) {
            if (file_exists($tmpPath)) unlink($tmpPath);
            return '';
        }

        $result = Process::path(base_path())->run([$mjmlBin, $tmpPath, '-s']);

        if (file_exists($tmpPath)) unlink($tmpPath);

        return $result->successful() ? ($result->output() ?: "") : "";
    }
}
