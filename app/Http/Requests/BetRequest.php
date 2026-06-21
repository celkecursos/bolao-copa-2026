<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BetRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('bets.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'home_score' => ['required', 'integer', 'min:0', 'max:99'],
            'away_score' => ['required', 'integer', 'min:0', 'max:99'],
        ];
    }

    public function attributes(): array
    {
        return [
            'home_score' => 'gols do mandante',
            'away_score' => 'gols do visitante',
        ];
    }
}
