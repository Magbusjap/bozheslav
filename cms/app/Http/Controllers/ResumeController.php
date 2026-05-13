<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResumeController extends Controller
{
    public function download(): StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        // Получаем путь к файлу из таблицы options
        // Get the file path from the options table
        $resumePath = Option::where('key', 'resume_pdf')->value('value');

        if (!$resumePath || !Storage::disk('public')->exists($resumePath)) {
            abort(404, 'Резюме пока не загружено');
        }

        $fullPath = Storage::disk('public')->path($resumePath);
        $fileName = 'resume-bozheslav.pdf';

        return response()->streamDownload(function () use ($fullPath) {
            readfile($fullPath);
        }, $fileName, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }
}