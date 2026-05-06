<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\ValidationException;

class FastApiService
{
    public function getFaceEmbedding(UploadedFile $photo): array
    {
        $url = env('FASTAPI_URL', 'http://127.0.0.1:8000') . '/api/v1/get-embedding';
        $apiKey = env('FASTAPI_KEY', 'super-secret-internal-key');

        try {
            $response = Http::withHeaders([
                'X-Internal-API-Key' => $apiKey
            ])->attach(
                'image',
                file_get_contents($photo->getRealPath()),
                $photo->getClientOriginalName()
            )->post($url);

            if ($response->successful()) {
                return $response->json('embedding');
            }

            $error = $response->json('detail') ?? 'Error desconocido al procesar el rostro.';
            throw ValidationException::withMessages(['photo' => 'Error API: ' . $error]);

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw ValidationException::withMessages(['photo' => 'No se pudo conectar con el microservicio de extracción de rostros.']);
        } catch (\Exception $e) {
            throw new \Exception('Error al comunicarse con el servicio biométrico: ' . $e->getMessage());
        }
    }
}
