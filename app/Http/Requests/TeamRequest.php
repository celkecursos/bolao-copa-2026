<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('teams.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:3'],
            'group' => ['nullable', Rule::in(range('A', 'L'))],
            'flag' => ['nullable', 'string', 'max:255'],
            'external_id' => ['nullable', 'integer'],
        ];
    }
}
