<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEligibilityService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.ai.url', 'http://127.0.0.1:8001');
    }

    public function predict(array $donorData): array
    {
        try {
            $response = Http::timeout(10)->post($this->apiUrl . '/predict', [
                'age'             => (float) $donorData['age'],
                'weight_kg'       => (float) $donorData['weight_kg'],
                'hemoglobin'      => (float) $donorData['hemoglobin'],
                'total_donations' => (float) ($donorData['total_donations'] ?? 0),
                'blood_group'     => $donorData['blood_group'] ?? 'O+',
                'gender'          => $donorData['gender'] ?? 'Male',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'eligible'   => $data['eligible']   ?? false,
                    'confidence' => $data['confidence']  ?? 0,
                    'status'     => 'ai',
                ];
            }

            // API failed — fall back to rule-based
            return $this->fallback($donorData);

        } catch (\Exception $e) {
            Log::warning('AI API unavailable: ' . $e->getMessage());
            return $this->fallback($donorData);
        }
    }

    // Fallback if Python API is down
    private function fallback(array $data): array
    {
        $eligible = (
            ($data['age']        ?? 0) >= 18 &&
            ($data['weight_kg']  ?? 0) >= 50 &&
            ($data['hemoglobin'] ?? 0) >= 12
        );

        return [
            'eligible'   => $eligible,
            'confidence' => $eligible ? 85.0 : 0.0,
            'status'     => 'fallback',
        ];
    }


    // ── RESPONSE PROBABILITY ──
public function predictResponse(array $donorData): array
{
    try {
        $response = Http::timeout(10)->post($this->apiUrl . '/predict-response', [
            'age'             => (float) ($donorData['age']        ?? 0),
            'weight_kg'       => (float) ($donorData['weight_kg']  ?? 0),
            'hemoglobin'      => (float) ($donorData['hemoglobin'] ?? 0),
            'total_donations' => (float) ($donorData['total_donations'] ?? 0),
            'gender'          => $donorData['gender'] ?? 'Male',
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return ['response_probability' => 50.0, 'level' => 'medium', 'status' => 'fallback'];

    } catch (\Exception $e) {
        Log::warning('Response AI unavailable: ' . $e->getMessage());
        return ['response_probability' => 50.0, 'level' => 'medium', 'status' => 'fallback'];
    }
}

// ── ANOMALY DETECTION ──
public function detectAnomaly(array $donorData): array
{
    try {
        $response = Http::timeout(10)->post($this->apiUrl . '/detect-anomaly', [
            'age'             => (float) ($donorData['age']        ?? 0),
            'weight_kg'       => (float) ($donorData['weight_kg']  ?? 0),
            'hemoglobin'      => (float) ($donorData['hemoglobin'] ?? 0),
            'total_donations' => (float) ($donorData['total_donations'] ?? 0),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return ['is_anomaly' => false, 'anomaly_score' => 0, 'label' => 'normal', 'status' => 'fallback'];

    } catch (\Exception $e) {
        Log::warning('Anomaly AI unavailable: ' . $e->getMessage());
        return ['is_anomaly' => false, 'anomaly_score' => 0, 'label' => 'normal', 'status' => 'fallback'];
    }
}
}