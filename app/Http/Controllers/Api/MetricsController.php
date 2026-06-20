<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\MetricsRepository;
use Illuminate\Http\JsonResponse;

class MetricsController extends Controller
{
    public function __construct(private readonly MetricsRepository $metricsRepository) {}

    public function index(): JsonResponse
    {
        $metrics = $this->metricsRepository->get();

        return response()->json([
            'success' => true,
            'data'    => $metrics,
        ]);
    }
}
