<?php

namespace App\Http\Requests\Admin;

use App\Models\Step;
use Illuminate\Foundation\Http\FormRequest;

class OperationRequest extends FormRequest
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

    public function prepareForValidation()
    {
        $this->merge([
            'start' => $this->start ? date('Y-m-d H:i:s', strtotime($this->start)) : null,
            'end' => $this->end ? date('Y-m-d H:i:s', strtotime($this->end)) : null,
            'step_id' => $this->step_id == null ? (isset($this->relatedSteps) ? min($this->relatedSteps) : Step::orderBy('sequence')->first()->id) : $this->step_id
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|max:191',
            'code' => 'nullable|max:191',
            'reference' => 'nullable|max:191',
            'spindle' => 'required|in:Y,X,W,V,U,T,S,R,Q,P,O,N,Z,A,B,C,D,E,F,G,H,I,K,L,M',
            'situation' => 'nullable|max:4000000000',
            'type' => 'nullable|in:Exploratória,Sistemática',
            'mission' => 'nullable|max:4000000000',
            'start' => 'nullable|date_format:Y-m-d H:i:s',
            'end' => $this->start ? "nullable|date_format:Y-m-d H:i:s|after_or_equal:$this->start" : "nullable|date_format:Y-m-d H:i:s",
            'classification' => 'nullable|in:Proteção,Exploração,Ataque',
            'step_id' => 'required|exists:steps,id',
            'execution' => 'nullable|max:4000000000',
            'instructions' => 'nullable|max:4000000000',
            'logistics' => 'nullable|max:4000000000',
            'file' => 'nullable|file|max:1048576'
        ];
    }

    public function messages()
    {
        return [
            'end.after_or_equal' => "O campo fim deve ser igual ou posterior a " . date("d/m/Y H:i", strtotime($this->start)) . ".",
        ];
    }
}
