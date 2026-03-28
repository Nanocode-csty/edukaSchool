<?php

namespace App\Http\Requests;

class StoreDocenteRequest extends PersonaRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        //TRAEMOS TODAS LAS VALIDACIONES DEL FORMULARIO DE PersonaRequest
        return array_merge(parent::rules(), [

             'especialidad' => 'required|max:100|min:2',
                'fecha_contratacion' => ['required', 'date'],
                'tipo_contrato'  => ['required', 'in:Nombrado, Contratado, Temporal'],

        ]);
    }

    /**
     * Custom validation messages
     */
    public function messages(): array
    {
        //TRAEMOS LOS MENSAJES DE LAS VALIDACIONES DEL FORMULARIO DE PersonaRequest
        return array_merge(parent::messages(), [
            'especialidad.required' => 'Debe ingresar la especialidad.',
            'especialidad.min' => 'Debe ingresar la especialidad válida.',
            'especialidad.max' => 'Debe ingresar la especialidad válida.',

            'fecha_contratacion.required' => 'Debe ingresar la fecha de contratación.',
            'fecha_contratacion.date' => 'Debe ingresar una fecha válida.',

            'tipo_contrato.required' => 'Debe ingresar el tipo de contrato.',
            'tipo_contrato.in' => 'Debe ingresar el tipo de contrato válido.',
        ]);
    }
}
