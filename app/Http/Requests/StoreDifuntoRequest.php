<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDifuntoRequest extends FormRequest
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
            
                'fecnac' => 'required',
               
                'numerodoc' =>  ['required', Rule::unique('cem_difunto')->ignore($this->id)],
                // 'required|string|max:8|min:8|unique:cem_difunto,numerodoc,'.$this->numerodoc,
                'nombre_dif' => 'required|string',
                'gendifu' => 'required',
                'diredifu' => 'required',
                // 'fechorafalle' => 'required'
           
        ];
    }
    public function messages()
    {
        return [
            'fecnac.required' => 'Ingresa Fecha de Nac.',
            'numerodoc.required' => 'Ingresa Nro de Documento',
            'gendifu.required' => 'seleciona genero',
            'diredifu.required' => 'la Direccion es requerida',
            // 'fechorafalle.required' => 'Ingresa Fecha y hora de fall.',
            'numerodoc.unique' => 'este nro de ya esta registrado',
        ];
    }
}
