<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/**
 * Pemanggil Gemini API (generateContent) yang dibuat sehemat mungkin:
 * thinking minimal, jawaban JSON terstruktur, dan batas token output.
 */
class GeminiClient
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent';

    /**
     * @param  array<int, array{role: string, text: string}>  $messages
     * @return array{data: array, input_tokens: int, output_tokens: int}
     */
    public function generateJson(string $systemPrompt, array $messages, array $schema, int $maxOutputTokens = 512): array
    {
        $key = config('services.gemini.key');

        if (! $key) {
            throw new RuntimeException('GEMINI_API_KEY belum diisi di .env');
        }

        $response = Http::timeout(config('services.gemini.timeout'))
            ->withHeaders(['x-goog-api-key' => $key])
            ->acceptJson()
            ->post(sprintf(self::ENDPOINT, config('services.gemini.model')), [
                'systemInstruction' => ['parts' => [['text' => $systemPrompt]]],
                'contents' => array_map(fn ($m) => [
                    'role' => $m['role'] === 'model' ? 'model' : 'user',
                    'parts' => [['text' => $m['text']]],
                ], $messages),
                'generationConfig' => [
                    'temperature' => 0.5,
                    'maxOutputTokens' => $maxOutputTokens,
                    'responseMimeType' => 'application/json',
                    'responseSchema' => $schema,
                    'thinkingConfig' => ['thinkingLevel' => 'minimal'],
                ],
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Gemini HTTP ' . $response->status() . ': ' . mb_substr($response->body(), 0, 300));
        }

        $usage = $response->json('usageMetadata', []);
        $text = collect($response->json('candidates.0.content.parts', []))
            ->reject(fn ($part) => $part['thought'] ?? false)
            ->pluck('text')
            ->implode('');

        $data = json_decode($text, true);

        return [
            // Bila model tidak mengembalikan JSON yang valid, pakai teksnya sebagai jawaban
            'data' => is_array($data) ? $data : ['reply' => trim($text)],
            'input_tokens' => (int) ($usage['promptTokenCount'] ?? 0),
            'output_tokens' => (int) ($usage['candidatesTokenCount'] ?? 0) + (int) ($usage['thoughtsTokenCount'] ?? 0),
        ];
    }
}
