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
                ->post(self::BASE_URL . '/token');

            return $response->successful() ? $response->json('token') : null;
        } catch (\Exception $e) {
            Log::error('Failed to retrieve token: ' . $e->getMessage());
            return null;
        }
    }

    public function findFalcone(array $selectedPlanets, array $selectedVehicles): array
    {
        $token = $this->getToken();
        
        if (!$token) {
            return [
                'status' => 'error', 
                'message' => 'Failed to retrieve authentication token'
            ];
        }

        try {
            $response = Http::post(self::BASE_URL . '/find', [
                'token' => $token,
                'planet_names' => array_column($selectedPlanets, 'name'),
                'vehicle_names' => array_column($selectedVehicles, 'name')
            ]);

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Find Falcone request failed: ' . $e->getMessage());
            return [
                'status' => 'error', 
                'message' => 'Failed to complete the Find Falcone request'
            ];
        }
    }
}