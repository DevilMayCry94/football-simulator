<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MatchUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'home_team_score' => 'required|integer',
            'away_team_score' => 'required|integer',
        ];
    }
}
