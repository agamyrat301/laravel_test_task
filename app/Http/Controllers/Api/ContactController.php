<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Services\ContactService;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    public function __construct(private readonly ContactService $contactService) {}

    public function store(ContactRequest $request): JsonResponse
    {
        $result = $this->contactService->process(
            $request->validated(),
            $request->ip() ?? '0.0.0.0',
        );

        return response()->json([
            'success' => true,
            'message' => 'Your message has been received. We will get back to you shortly.',
            'data'    => [
                'auto_response' => $result['ai_result']['auto_response'],
                'request_type'  => $result['ai_result']['request_type'],
                'sentiment'     => $result['ai_result']['sentiment'],
            ],
        ]);
    }
}
