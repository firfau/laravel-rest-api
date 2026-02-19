<?php

namespace App\Repositories\Data;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataRepository
{
    private $externalUrl = 'https://bit.ly/48ejMhW';

    /**
     * Ambil dan parse data dari API eksternal
     * Mengembalikan array of associative array ['YMD', 'NIM', 'NAMA']
     */
    public function fetchAll(): ?array
    {
        try {
            $response = Http::withOptions([
                'allow_redirects' => true,
                'timeout'         => 15,
            ])->get($this->externalUrl);

            if (!$response->successful()) return null;

            $body      = $response->json();
            $rawString = $body['DATA'] ?? null;

            if (!$rawString) return null;

            // Parse string pipe-separated menjadi array
            $lines  = explode("\n", trim($rawString));
            $header = array_shift($lines);           // Baris pertama = header
            $keys   = explode('|', $header);         // ['YMD', 'NIM', 'NAMA']

            $result = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $values = explode('|', $line);
                if (count($values) !== count($keys)) continue;

                $result[] = array_combine($keys, $values);
            }

            return $result;

        } catch (\Exception $e) {
            Log::error('DataRepository fetchAll error: ' . $e->getMessage());
            return null;
        }
    }
}