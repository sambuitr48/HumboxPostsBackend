<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para realizar esta solicitud.
     * En este caso se permite siempre porque no hay autenticación aún.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true; // Permitir siempre (modificable si se implementa auth)
    }

    /**
     * Reglas de validación para la creación de una publicación.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // Título obligatorio, texto, máximo 255 caracteres
            'title' => ['required', 'string', 'max:255'],

            // Descripción obligatoria, sin límite de caracteres aquí
            'description' => ['required', 'string'],

            // Ubicación opcional
            'location' => ['nullable', 'string'],

            // Etiquetas requeridas (como string JSON, se decodifican en el controlador)
            'tags' => ['required', 'string'],

            // Tipo de recurso requerido, debe existir en la tabla resource_types
            'resource_type_id' => ['required', 'exists:resource_types,id'],

            // Colaboradores opcionales, deben ser un arreglo de IDs enteros
            'collaborators' => ['nullable', 'array'],
            'collaborators.*' => ['integer'],

            // Archivos adjuntos requeridos: estructura de array anidado
            'attached_files' => ['required', 'array'],
            'attached_files.*.file' => ['required', 'file'], // Cada elemento debe incluir un archivo válido
            'attached_files.*.alt_text' => ['nullable', 'string'], // Texto alternativo opcional
        ];
    }

    /**
     * Mensajes personalizados para errores de validación.
     * Estos se retornan al frontend si alguna regla no se cumple.
     *
     * @return array<string, string>
     */
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
