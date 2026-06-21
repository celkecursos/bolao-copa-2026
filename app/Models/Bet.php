<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    /** @use HasFactory<\Database\Factories\BetFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'game_id',
        'home_score',
        'away_score',
        'points_earned',
    ];

    protected $casts = [
        'home_score' => 'integer',
        'away_score' => 'integer',
        'points_earned' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /** Retorna 'home', 'away' ou 'draw' conforme o palpite. */
    public function predictedWinner(): string
    {
        return match (true) {
            $this->home_score > $this->away_score => 'home',
            $this->home_score < $this->away_score => 'away',
            default => 'draw',
        };
    }
}
