<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/** Validación de la corrección de un reporte por un moderador. */
class UpdateReporteRequest extends FormRequest
{
    /** El acceso ya está restringido por la ruta (autenticación + rol). */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'categoria_id' => ['required', 'integer', Rule::exists('categorias', 'id')],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
        ];
    }
}
