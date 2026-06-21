<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'group',
        'flag',
        'external_id',
    ];

    /** Bandeira como emoji (texto) ou <img> quando for uma URL de escudo. */
    public function flagDisplay(): string
    {
        if (blank($this->flag)) {
            return '';
        }

        if (str_starts_with($this->flag, 'http')) {
            return '<img src="'.e($this->flag).'" alt="'.e($this->code).'" class="inline-block h-4 w-5 object-contain align-text-bottom">';
        }

        return e($this->flag);
    }

    public function homeGames(): HasMany
    {
        return $this->hasMany(Game::class, 'home_team_id');
    }

    public function awayGames(): HasMany
    {
        return $this->hasMany(Game::class, 'away_team_id');
    }
}
