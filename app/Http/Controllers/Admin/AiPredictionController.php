<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AiPrediction;
use Illuminate\Http\Request;

class AiPredictionController extends Controller
{
    // Read-only audit log of every AI call the system has made, across all
    // donors and prediction types -- the "AI Predictions" sidebar item
    // previously pointed nowhere even though this data has existed in the
    // ai_predictions table since it was introduced.
    public function index(Request $request)
    {
        $query = AiPrediction::with('donor')->latest();

        if ($request->filled('prediction_type')) {
            $query->where('prediction_type', $request->prediction_type);
        }
        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        $predictions = $query->paginate(20)->withQueryString();

        $predictionTypes = AiPrediction::query()->distinct()->pluck('prediction_type');
        $models = AiPrediction::query()->distinct()->pluck('model');

        return view('admin.ai_predictions.index', compact('predictions', 'predictionTypes', 'models'));
    }
}
