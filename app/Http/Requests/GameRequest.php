<?php

namespace App\Http\Requests;

use App\Models\Game;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('games.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'home_team_id' => ['required', 'exists:teams,id'],
            'away_team_id' => ['required', 'exists:teams,id', 'different:home_team_id'],
            'group' => ['nullable', Rule::in(range('A', 'L'))],
            'stage' => ['required', Rule::in(Game::STAGES)],
            'match_datetime' => ['required', 'date'],
            'stadium' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['scheduled', 'live', 'finished'])],
            'external_id' => ['nullable', 'integer'],
        ];
    }

    public function attributes(): array
    {
        return [
            'home_team_id' => 'time mandante',
            'away_team_id' => 'time visitante',
            'match_datetime' => 'data/hora',
        ];
    }
}
