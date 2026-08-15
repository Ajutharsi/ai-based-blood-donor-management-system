<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPrediction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'prediction_type', 'model', 'input', 'output', 'confidence',
    ];

    protected function casts(): array
    {
        return [
            'input'  => 'array',
            'output' => 'array',
        ];
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(Donor::class);
    }

    public static function log(?int $donorId, string $predictionType, array $input, array $output): self
    {
        $prediction = self::create([
            'donor_id'        => $donorId,
            'prediction_type' => $predictionType,
            'model'           => $output['model'] ?? ($output['status'] ?? 'unknown'),
            'input'           => $input,
            'output'          => $output,
            'confidence'      => $output['confidence'] ?? $output['response_probability'] ?? $output['anomaly_score'] ?? null,
        ]);

        ActivityLog::log(
            'ai_prediction', 'ai_prediction_logged',
            __('AI :type prediction recorded' . ($donorId ? ' for donor #:donorId.' : '.'), ['type' => $predictionType, 'donorId' => $donorId]),
            'AiPrediction', $prediction->id
        );

        return $prediction;
    }
}
