<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GameService
{
    private const BASE_URL = 'https://findfalcone.geektrust.com';

    public function getPlanets(): array
    {
        return $this->fetchData('/planets');
    }

    public function getVehicles(): array
    {
        return $this->fetchData('/vehicles');
    }

    private function fetchData(string $endpoint): array
    {
        try {
            $response = Http::withoutVerifying()->get(self::BASE_URL . $endpoint);
            return $response->successful() ? $response->json() : [];
        } catch (\Exception $e) {
            Log::error("Failed to fetch data from {$endpoint}: " . $e->getMessage());
            return [];
        }
    }

    public function getToken(): ?string
    {
        try {
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->withoutVerifying()
                ->post(self::BASE_URL . '/token');

            return $response->successful() ? $response->json('token') : null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve token: ' . $e->getMessage());
            return null;
        }
    }

    public function findFalcone(array $planetNames, array $vehicleNames): array
    {
        $token = $this->getToken();
        
        if (!$token) {
            return [
                'status' => 'error', 
                'message' => 'Failed to retrieve authentication token'
            ];
        }

        try {
            $response = Http::withHeaders(['Accept' => 'application/json'])
                ->withoutVerifying()
                ->post(self::BASE_URL . '/find', [
                    'token' => $token,
                    'planet_names' => $planetNames,
                    'vehicle_names' => $vehicleNames
                ]);

            if (!$response->successful()) {
                Log::error('Find Falcone API error: ' . $response->body());
                return [
                    'status' => 'error',
                    'error' => 'API returned an error: ' . $response->status()
                ];
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Find Falcone request failed: ' . $e->getMessage());
            return [
                'status' => 'error', 
                'error' => 'Failed to complete the Find Falcone request'
            ];
        }
    }
}