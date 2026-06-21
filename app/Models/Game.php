<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Contracts\Auditable;

class Game extends Model implements Auditable
{
    /** @use HasFactory<\Database\Factories\GameFactory> */
    use HasFactory, SoftDeletes, AuditableTrait;

    protected $fillable = [
        'home_team_id',
        'away_team_id',
        'group',
        'stage',
        'match_datetime',
        'stadium',
        'city',
        'home_score',
        'away_score',
        'status',
        'external_id',
    ];

    protected $casts = [
        'match_datetime' => 'datetime',
        'home_score' => 'integer',
        'away_score' => 'integer',
    ];

    /**
     * Campos cujas alterações são registradas pela auditoria
     * (foco no resultado e nos times do jogo).
     */
    protected array $auditInclude = [
        'home_team_id',
        'away_team_id',
        'match_datetime',
        'home_score',
        'away_score',
        'status',
    ];

    public const STAGES = [
        'group', 'round_32', 'round_16', 'quarter', 'semi', 'third_place', 'final',
    ];

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    /** O jogo já tem placar lançado? */
    public function hasResult(): bool
    {
        return ! is_null($this->home_score) && ! is_null($this->away_score);
    }

    /** Retorna 'home', 'away' ou 'draw' (null se ainda sem resultado). */
    public function winner(): ?string
    {
        if (! $this->hasResult()) {
            return null;
        }

        return match (true) {
            $this->home_score > $this->away_score => 'home',
            $this->home_score < $this->away_score => 'away',
            default => 'draw',
        };
    }

    /** O jogo já começou (com base no horário do servidor)? */
    public function hasStarted(): bool
    {
        return $this->match_datetime->isPast();
    }

    /** Prazo final para palpitar (início do jogo menos o buffer configurado). */
    public function bettingDeadline(): \Illuminate\Support\Carbon
    {
        return $this->match_datetime->copy()
            ->subMinutes((int) config('bolao.bet_lock_buffer_minutes'));
    }

    /** Ainda dá para criar/editar palpite? */
    public function isBettingOpen(): bool
    {
        return now()->lt($this->bettingDeadline());
    }

    /** É um jogo de fase eliminatória (mata-mata)? */
    public function isKnockout(): bool
    {
        return $this->stage !== 'group';
    }
}
