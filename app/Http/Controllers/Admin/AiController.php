<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiController extends Controller
{
    public function index()
    {
        return view('admin.ai.assistant');
    }

    public function gemini(Request $request)
    {
        $data = $request->validate([
            'prompt' => 'required|string|max:8000',
            'max_tokens' => 'nullable|integer|min:50|max:4000',
        ]);

        $geminiKey = config('services.gemini.api_key');
        $maxTokens = $data['max_tokens'] ?? 1500;

        // Try Gemini first
        if ($geminiKey) {
            $result = $this->callGemini($geminiKey, $data['prompt'], $maxTokens);

            if ($result['ok']) {
                return response()->json(['success' => true, 'data' => $result['data'], 'raw' => $result['raw'], 'provider' => 'gemini']);
            }

            // If 429 (quota), fall through to Groq fallback
            if ($result['status'] !== 429) {
                Log::error('Gemini API error', ['status' => $result['status'], 'message' => $result['message']]);

                return response()->json(['error' => 'Gemini: '.$result['message']], 502);
            }

            Log::warning('Gemini quota exceeded (429), falling back to Groq');
        }

        // Groq fallback
        return $this->callGroqAsGemini($data['prompt'], $maxTokens);
    }

    public function groq(Request $request)
    {
        $data = $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,assistant,system',
            'messages.*.content' => 'required|string',
            'max_tokens' => 'nullable|integer|min:50|max:4000',
        ]);

        $apiKey = config('services.groq.key');
        $model = config('services.groq.model');

        if (! $apiKey) {
            return response()->json(['error' => 'Groq API key not configured.'], 503);
        }

        try {
            $response = Http::withToken($apiKey)->timeout(30)->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => $model,
                    'messages' => $data['messages'],
                    'max_tokens' => $data['max_tokens'] ?? 800,
                    'temperature' => 0.7,
                ]
            );

            if ($response->failed()) {
                $status = $response->status();
                $msg = $status === 429 ? 'Rate limit reached. Please wait a moment and try again.' : 'Groq API error: '.$status;

                return response()->json(['error' => $msg], $status === 429 ? 429 : 502);
            }

            $content = $response->json('choices.0.message.content') ?? '';

            return response()->json(['success' => true, 'content' => $content]);
        } catch (\Exception $e) {
            Log::error('Groq admin request exception', ['error' => $e->getMessage()]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ── private helpers ──────────────────────────────────────────────────────

    private function callGemini(string $key, string $prompt, int $maxTokens): array
    {
        try {
            $model = config('services.gemini.model');
            $response = Http::timeout(30)->post(
                "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$key}",
                [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['maxOutputTokens' => $maxTokens, 'temperature' => 0.7],
                ]
            );

            if ($response->failed()) {
                return ['ok' => false, 'status' => $response->status(), 'message' => 'HTTP '.$response->status()];
            }

            $text = $response->json('candidates.0.content.parts.0.text') ?? '';
            $parsed = $this->tryParseJson($text);

            return ['ok' => true, 'data' => $parsed ?? $text, 'raw' => $text];
        } catch (\Exception $e) {
            return ['ok' => false, 'status' => 500, 'message' => $e->getMessage()];
        }
    }

    private function callGroqAsGemini(string $prompt, int $maxTokens)
    {
        $apiKey = config('services.groq.key');
        $model = config('services.groq.model');

        if (! $apiKey) {
            return response()->json(['error' => 'Gemini quota exceeded and no Groq fallback configured.'], 503);
        }

        try {
            $response = Http::withToken($apiKey)->timeout(45)->post(
                'https://api.groq.com/openai/v1/chat/completions',
                [
                    'model' => $model,
                    'messages' => [['role' => 'user', 'content' => $prompt]],
                    'max_tokens' => min($maxTokens, 4000),
                    'temperature' => 0.7,
                ]
            );

            if ($response->failed()) {
                $status = $response->status();
                $msg = $status === 429 ? 'All AI providers are currently rate-limited. Please try again in a minute.' : 'Groq fallback error: '.$status;

                return response()->json(['error' => $msg], 502);
            }

            $text = $response->json('choices.0.message.content') ?? '';
            $parsed = $this->tryParseJson($text);

            return response()->json(['success' => true, 'data' => $parsed ?? $text, 'raw' => $text, 'provider' => 'groq']);
        } catch (\Exception $e) {
            Log::error('Groq fallback exception', ['error' => $e->getMessage()]);

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function tryParseJson(string $text): mixed
    {
        $trimmed = trim($text);

        // Strip markdown fences if present
        if (preg_match('/```(?:json)?\s*([\s\S]*?)```/i', $trimmed, $matches)) {
            $trimmed = trim($matches[1]);
        }

        if (str_starts_with($trimmed, '{') || str_starts_with($trimmed, '[')) {
            $decoded = json_decode($trimmed, true);

            return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
        }

        return null;
    }
}
