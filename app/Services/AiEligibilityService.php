<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEligibilityService
{
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('services.ai.url', 'http://127.0.0.1:8001');
    }

    /**
     * The real name/accuracy of whatever eligibility model is currently
     * deployed (see /model-info), for pages that describe the AI approach
     * to users. Cached briefly so public pages (landing/about) don't hit
     * the AI service on every request -- this changes only when the model
     * is retrained, not per-request.
     */
    public function getModelInfo(): ?array
    {
        return Cache::remember('ai_eligibility_model_info', now()->addMinutes(10), function () {
            try {
                $response = Http::connectTimeout(5)->timeout(5)->get($this->apiUrl . '/model-info');

                if ($response->successful() && $response->json('status') === 'success') {
                    return $response->json();
                }
            } catch (\Exception $e) {
                Log::warning('Model info unavailable: ' . $e->getMessage());
            }

            return null;
        });
    }

    public function predict(array $donorData): array
    {
        try {
            $response = Http::connectTimeout(5)->timeout(10)->post($this->apiUrl . '/predict', [
                'age'             => (float) $donorData['age'],
                'weight_kg'       => (float) $donorData['weight_kg'],
                'hemoglobin'      => (float) $donorData['hemoglobin'],
                'total_donations' => (float) ($donorData['total_donations'] ?? 0),
                'blood_group'     => $donorData['blood_group'] ?? 'O+',
                'gender'          => $donorData['gender'] ?? 'Male',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                // Coerced/whitelisted rather than passed through raw --
                // 'confidence' ends up in a decimal DB column downstream
                // (Donor.ai_confidence, AiPrediction.confidence), so a
                // malformed/non-numeric value from the external service
                // must not reach Eloquent::create()/update() as-is.
                return [
                    'eligible'   => (bool) ($data['eligible'] ?? false),
                    'confidence' => $this->toFloat($data['confidence'] ?? null, 0.0),
                    'model'      => is_string($data['model'] ?? null) ? $data['model'] : 'unknown',
                    'status'     => is_string($data['status'] ?? null) ? $data['status'] : 'success',
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
            'model'      => 'fallback',
            'status'     => 'fallback',
        ];
    }

    // Safely coerces an AI-service-supplied value to a float, since the
    // response body is untrusted input -- returns $default for anything
    // that isn't numeric (missing key, string, array, null, bool, ...).
    private function toFloat(mixed $value, float $default): float
    {
        return is_numeric($value) ? (float) $value : $default;
    }

    // ── RESPONSE PROBABILITY ──
    public function predictResponse(array $donorData): array
    {
        try {
            $response = Http::connectTimeout(5)->timeout(10)->post($this->apiUrl . '/predict-response', [
                'age'         => (float) ($donorData['age']        ?? 0),
                'weight_kg'   => (float) ($donorData['weight_kg']  ?? 0),
                'hemoglobin'  => (float) ($donorData['hemoglobin'] ?? 0),
                'blood_group' => $donorData['blood_group'] ?? 'O+',
                'gender'      => $donorData['gender'] ?? 'Male',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'response_probability' => $this->toFloat($data['response_probability'] ?? null, 50.0),
                    'level'                => is_string($data['level'] ?? null) ? $data['level'] : 'medium',
                    'model'                => is_string($data['model'] ?? null) ? $data['model'] : 'unknown',
                    'status'               => is_string($data['status'] ?? null) ? $data['status'] : 'success',
                ];
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
            $response = Http::connectTimeout(5)->timeout(10)->post($this->apiUrl . '/detect-anomaly', [
                'age'             => (float) ($donorData['age']        ?? 0),
                'weight_kg'       => (float) ($donorData['weight_kg']  ?? 0),
                'hemoglobin'      => (float) ($donorData['hemoglobin'] ?? 0),
                'total_donations' => (float) ($donorData['total_donations'] ?? 0),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'is_anomaly'    => (bool) ($data['is_anomaly'] ?? false),
                    'anomaly_score' => $this->toFloat($data['anomaly_score'] ?? null, 0.0),
                    'label'         => is_string($data['label'] ?? null) ? $data['label'] : 'normal',
                    'model'         => is_string($data['model'] ?? null) ? $data['model'] : 'unknown',
                    'status'        => is_string($data['status'] ?? null) ? $data['status'] : 'success',
                ];
            }

            return ['is_anomaly' => false, 'anomaly_score' => 0, 'label' => 'normal', 'status' => 'fallback'];

        } catch (\Exception $e) {
            Log::warning('Anomaly AI unavailable: ' . $e->getMessage());
            return ['is_anomaly' => false, 'anomaly_score' => 0, 'label' => 'normal', 'status' => 'fallback'];
        }
    }
}
