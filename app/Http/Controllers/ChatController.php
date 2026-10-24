<?php

namespace App\Http\Controllers;

use App\Models\ChatLog;
use App\Support\Chatbot;
use App\Support\GeminiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ChatController extends Controller
{
    private const HISTORY_LIMIT = 6;

    public function send(Request $request, GeminiClient $gemini)
    {
        $request->validate([
            'messages' => 'required|array|min:1|max:20',
            'messages.*.role' => 'required|in:user,model',
            'messages.*.text' => 'required|string|max:1000',
        ]);

        if (! Chatbot::enabled()) {
            return $this->reply('Maaf Kak, Afa sedang istirahat. Silakan chat admin kami di WhatsApp ya 🙏', handoff: true, status: 'disabled');
        }

        // Hanya beberapa pesan terakhir yang dikirim ke model, dan setiap pesan dipotong, agar token tetap kecil
        $messages = collect($request->input('messages'))
            ->take(-self::HISTORY_LIMIT)
            ->map(fn ($m) => ['role' => $m['role'], 'text' => mb_substr(trim($m['text']), 0, 500)])
            ->values();

        if ($messages->last()['role'] !== 'user') {
            return response()->json(['message' => 'Pesan terakhir harus dari pengunjung.'], 422);
        }

        $question = $messages->last()['text'];

        if (! Chatbot::consumeQuota($request->ip())) {
            $this->log($question, null, [], 0, 0, 'limit');

            return $this->reply('Kuota tanya Afa hari ini sudah habis, Kak. Admin kami siap bantu langsung di WhatsApp ya 😊', handoff: true, status: 'limit');
        }

        try {
            $result = $gemini->generateJson(Chatbot::systemPrompt(), $messages->all(), Chatbot::RESPONSE_SCHEMA);
        } catch (Throwable $e) {
            Log::warning('Chatbot gagal memanggil Gemini.', ['error' => $e->getMessage()]);
            $this->log($question, null, [], 0, 0, 'error');

            return $this->reply('Maaf Kak, Afa lagi kesulitan menjawab. Coba lagi sebentar, atau langsung chat admin di WhatsApp ya.', handoff: true, status: 'error');
        }

        $data = $result['data'];
        $reply = trim((string) ($data['reply'] ?? '')) ?: 'Maaf Kak, bisa diulang pertanyaannya?';
        $products = Chatbot::productCards((array) ($data['product_ids'] ?? []));

        $this->log($question, $reply, array_column($products, 'id'), $result['input_tokens'], $result['output_tokens'], 'ok');

        return $this->reply($reply, $products, (bool) ($data['handoff'] ?? false));
    }

    private function reply(string $text, array $products = [], bool $handoff = false, string $status = 'ok')
    {
        return response()->json([
            'reply' => $text,
            'products' => $products,
            'handoff' => $handoff,
            'status' => $status,
        ]);
    }

    private function log(string $question, ?string $answer, array $productIds, int $in, int $out, string $status): void
    {
        ChatLog::create([
            'question' => $question,
            'answer' => $answer,
            'product_ids' => $productIds ?: null,
            'input_tokens' => $in,
            'output_tokens' => $out,
            'status' => $status,
        ]);

        // Tanpa cron di InfinityFree: sesekali bersihkan riwayat lebih dari 30 hari
        if (random_int(1, 50) === 1) {
            ChatLog::where('created_at', '<', now()->subDays(30))->delete();
        }
    }
}
