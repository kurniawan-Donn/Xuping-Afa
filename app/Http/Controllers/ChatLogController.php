<?php

namespace App\Http\Controllers;

use App\Models\ChatLog;
use App\Support\Chatbot;
use Illuminate\Support\Facades\DB;

class ChatLogController extends Controller
{
    public function index()
    {
        $summary = fn ($from) => ChatLog::where('created_at', '>=', $from)
            ->selectRaw('COUNT(*) as messages, COALESCE(SUM(input_tokens), 0) as input_tokens, COALESCE(SUM(output_tokens), 0) as output_tokens')
            ->first();

        $today = $summary(now()->startOfDay());
        $month = $summary(now()->startOfMonth());

        $stats = [
            'today' => (int) $today->messages,
            'used_today' => Chatbot::usedToday(),
            'daily_limit' => Chatbot::dailyLimit(),
            'month' => (int) $month->messages,
            'month_cost' => ChatLog::estimateCost((int) $month->input_tokens, (int) $month->output_tokens),
            'avg_tokens' => $month->messages ? (int) round(($month->input_tokens + $month->output_tokens) / $month->messages) : 0,
            'errors' => ChatLog::where('created_at', '>=', now()->startOfDay())->where('status', 'error')->count(),
        ];

        $logs = ChatLog::latest()->paginate(20);

        return view('dashboard.chatbot.index', [
            'stats' => $stats,
            'logs' => $logs,
            'enabled' => Chatbot::enabled(),
            'hasKey' => filled(config('services.gemini.key')),
            'model' => config('services.gemini.model'),
        ]);
    }
}
