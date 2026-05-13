@php
    $mjmlCode = $getState();
    $renderedHtml = "";
    $errorMessage = "";

    if ($mjmlCode) {
        if (str_contains($mjmlCode, '<html')) {
            $renderedHtml = $mjmlCode;
        } else {
            // Temporary file for compilation
            $tmpPath = storage_path('app/temp_mjml_' . auth()->id() . '.mjml');
            file_put_contents($tmpPath, $mjmlCode);

            $mjmlBin = base_path('node_modules/.bin/mjml');

            if (is_file($mjmlBin)) {
                $result = \Illuminate\Support\Facades\Process::path(base_path())->run([$mjmlBin, $tmpPath, '-s']);
                $output = $result->successful() ? $result->output() : null;
            } else {
                $output = null;
            }

            if ($output && str_contains($output, '<table')) {
                $renderedHtml = $output;
            } else {
                $errorMessage = $output ?: "Неизвестная ошибка компиляции (пустой ответ).";
            }

            if (file_exists($tmpPath)) unlink($tmpPath);
        }
    }
@endphp

<div class="w-full bg-white border rounded-lg shadow-inner" style="min-height: 400px;">
    @if($renderedHtml)
        <iframe 
            srcdoc="{{ $renderedHtml }}" 
            class="w-full border-none"
            style="height: 1000px; width: 100%; display: block;"
            onload="const ifr = this; setTimeout(() => { ifr.style.height = ifr.contentWindow.document.documentElement.scrollHeight + 'px'; }, 100);"
        ></iframe>
    @else
        <div class="flex items-center justify-center h-[400px] text-gray-400">
            Введите MJML код...
        </div>
    @endif
</div>
