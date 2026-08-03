<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPrediction extends Model
{
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
        return self::create([
            'donor_id'        => $donorId,
            'prediction_type' => $predictionType,
            'model'           => $output['model'] ?? ($output['status'] ?? 'unknown'),
            'input'           => $input,
            'output'          => $output,
            'confidence'      => $output['confidence'] ?? $output['response_probability'] ?? $output['anomaly_score'] ?? null,
        ]);
    }
}
