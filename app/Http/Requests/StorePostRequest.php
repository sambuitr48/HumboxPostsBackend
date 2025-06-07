<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitimos la petición (puedes modificarlo si necesitas auth luego)
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['nullable', 'string'],
            'tags' => ['required', 'string'], // Vendrá como string JSON

            'resource_type_id' => ['required', 'exists:resource_types,id'],

            // Por ahora los colaboradores no los vamos a procesar, pero no rompe nada
            'collaborators' => ['nullable', 'array'],
            'collaborators.*' => ['integer'],

            // Esta parte es clave: Laravel espera múltiples archivos en arrays anidados
            'attached_files' => ['required', 'array'],
            'attached_files.*.file' => ['required', 'file'],
            'attached_files.*.alt_text' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título es obligatorio.',
            'description.required' => 'La descripción es obligatoria.',
            'tags.required' => 'Debe especificar al menos una etiqueta.',
            'resource_type_id.required' => 'Debe seleccionar el tipo de recurso.',
            'resource_type_id.exists' => 'El tipo de recurso seleccionado no es válido.',
            'attached_files.required' => 'Debe adjuntar al menos un archivo.',
            'attached_files.*.file.required' => 'Cada archivo debe tener un archivo válido.',
        ];
    }
}
