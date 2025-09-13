<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\CustomerPet;

class ApiTestController extends Controller
{
    /**
     * Test page for Customer Pets API.
     * If API_BASE_URL is set (e.g. http://127.0.0.1:8001/api), we will call it over HTTP.
     * Otherwise (typically in local dev), we fall back to direct DB to avoid self-HTTP deadlocks.
     */
    public function index(Request $request)
    {
        $userId = (int) ($request->query('user_id', 1));
        $limit  = (int) ($request->query('limit', 10));

        $base = rtrim((string) env('API_BASE_URL', 'http://127.0.0.1:8001/api'), '/'); // e.g. http://127.0.0.1:8001/api
        $usingHttp = !empty($base);

        if ($usingHttp) {
            $url = $base . "/customers/{$userId}/pets";
            try {
                $resp = Http::acceptJson()
                    ->timeout(5)->connectTimeout(2)->retry(1, 200)
                    ->get($url, ['limit' => $limit]);

                $pets = $resp->json();
            } catch (\Throwable $e) {
                $pets = ['ok' => false, 'data' => [], 'error' => $e->getMessage()];
            }
        } else {
            // Local fallback: direct DB (no HTTP to self)
            $pets = [
                'ok'   => true,
                'data' => CustomerPet::where('user_id', $userId)
                    ->latest('id')
                    ->limit($limit)
                    ->get(['id','name','pet_type_id','size_id','pet_breed_id as breed_id','photo_path'])
                    ->map(function ($p) {
                        return [
                            'id'          => $p->id,
                            'name'        => $p->name,
                            'pet_type_id' => $p->pet_type_id,
                            'size_id'     => $p->size_id,
                            'breed_id'    => $p->breed_id,
                            'photo_path'  => $p->photo_path,
                        ];
                    }),
            ];
        }

        // Pass a small flag so the Blade can show which mode we're using
        $mode = $usingHttp ? 'http (' . $base . ')' : 'db';

        return view('apitest.index', compact('pets', 'mode', 'userId', 'limit'));
    }
}