<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ParserController extends Controller
{
    // Proxy request to Python Flask parser
    public function search(Request $request)
    {
        $query    = $request->input('query', '');
        $area     = $request->input('area', 113); // 113 = Russia
        $schedule = $request->input('schedule', ''); // remote / fullDay etc

        if (empty($query)) {
            return response()->json(['error' => 'Введите поисковый запрос'], 400);
        }

        try {
            $response = Http::timeout(15)->get('http://127.0.0.1:5000/search', [
                'query'    => $query,
                'area'     => $area,
                'schedule' => $schedule,
            ]);

            return response()->json($response->json());

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}