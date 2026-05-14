<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeController extends Controller
{
    private const SUPPORTED_LOCALES = ['ru', 'en', 'sr'];

    public function download(): StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        $locale = $this->currentLocale();
        $resumePath = Option::get('resume_pdf_' . $locale);

        if ($locale === 'ru') {
            $resumePath ??= Option::get('resume_pdf');
        }

        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            abort(404, 'Резюме пока не загружено');
        }

        $fullPath = Storage::disk('public')->path($resumePath);
        $fileName = 'resume-bozheslav-' . $locale . '.pdf';

        return response()->streamDownload(function () use ($fullPath) {
            readfile($fullPath);
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    private function currentLocale(): string
    {
        $locale = app()->getLocale();

        return in_array($locale, self::SUPPORTED_LOCALES, true) ? $locale : 'ru';
    }
}
