<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLog extends Model
{
    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'product_ids' => 'array',
        'input_tokens' => 'integer',
        'output_tokens' => 'integer',
    ];

    /**
     * Perkiraan biaya dalam USD berdasarkan harga di config/services.php.
     */
    public static function estimateCost(int $inputTokens, int $outputTokens): float
    {
        return ($inputTokens * config('services.gemini.price_input') + $outputTokens * config('services.gemini.price_output')) / 1_000_000;
    }
}
