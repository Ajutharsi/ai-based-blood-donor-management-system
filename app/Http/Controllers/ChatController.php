<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:500',
        ]);

        // Get donor context if logged in
        $context = $this->getContext();

        try {
            $response = Http::connectTimeout(5)->timeout(10)->post(
                config('services.ai.url', 'http://127.0.0.1:8001') . '/chatbot',
                array_merge(['message' => $request->message], $context)
            );

            if ($response->successful()) {
                $data = $response->json();
                return response()->json([
                    'reply'   => is_string($data['reply'] ?? null) ? $data['reply'] : 'Sorry, I could not process that.',
                    'intent'  => is_string($data['intent'] ?? null) ? $data['intent'] : 'unknown',
                    'confidence' => is_numeric($data['confidence'] ?? null) ? (float) $data['confidence'] : 0,
                    'status'  => 'success',
                ]);
            }

            Log::warning('Chatbot request failed', ['status' => $response->status()]);

            return response()->json([
                'reply'  => 'Sorry, the assistant is unavailable. Please try again.',
                'status' => 'error',
            ]);

        } catch (\Exception $e) {
            Log::warning('Chatbot error: ' . $e->getMessage());
            return response()->json([
                'reply'  => 'Sorry, I am having trouble connecting. Please try again.',
                'status' => 'error',
            ]);
        }
    }

    private function getContext(): array
    {
        if (auth('donor')->check()) {
            $donor = auth('donor')->user();
            return [
                'role'               => 'donor',
                'donor_name'         => $donor->full_name,
                'blood_group'        => $donor->blood_group    ?? '',
                'is_eligible'        => (bool) $donor->is_eligible,
                'ai_confidence'      => (float) ($donor->ai_confidence ?? 0),
                'total_donations'    => (int) ($donor->total_donations ?? 0),
                'last_donation_date' => $donor->last_donation_date
                                        ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y')
                                        : '',
            ];
        }

        if (auth('hospital')->check()) {
            return ['role' => 'hospital', 'donor_name' => ''];
        }

        if (auth('admin')->check()) {
            return ['role' => 'admin', 'donor_name' => ''];
        }

        return ['role' => 'guest', 'donor_name' => ''];
    }
}