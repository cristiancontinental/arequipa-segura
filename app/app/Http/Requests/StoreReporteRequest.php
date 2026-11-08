<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Validación en el servidor del formulario público de reportes.
 * La validación del navegador ayuda al usuario, pero no sustituye a esta.
 */
class StoreReporteRequest extends FormRequest
{
    /** El formulario es público: no requiere cuenta. */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $l = config('arequipa.limites');

        return [
            'categoria_id' => [
                'required',
                'integer',
                Rule::exists('categorias', 'id')->where('activa', true),
            ],
            'descripcion' => ['required', 'string', 'min:10', 'max:500'],
            'latitud' => ['required', 'numeric', "between:{$l['lat_min']},{$l['lat_max']}"],
            'longitud' => ['required', 'numeric', "between:{$l['lng_min']},{$l['lng_max']}"],
            // Evidencia opcional: solo imágenes JPG/PNG de hasta 2 MB.
            'evidencia' => ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ];
    }

    public function attributes(): array
    {
        return [
            'categoria_id' => 'categoría',
            'descripcion' => 'descripción',
            'latitud' => 'ubicación',
            'longitud' => 'ubicación',
            'evidencia' => 'fotografía',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'descripcion.min' => 'Describe lo ocurrido con al menos :min caracteres.',
            'descripcion.max' => 'La descripción no puede superar los :max caracteres.',
            'categoria_id.exists' => 'Elige una categoría válida.',
            'latitud.between' => 'La ubicación debe estar dentro de la zona de Arequipa.',
            'longitud.between' => 'La ubicación debe estar dentro de la zona de Arequipa.',
            'evidencia.image' => 'El archivo debe ser una imagen.',
            'evidencia.mimes' => 'Solo se permiten imágenes JPG o PNG.',
            'evidencia.max' => 'La fotografía no puede superar los 2 MB.',
        ];
    }
}
