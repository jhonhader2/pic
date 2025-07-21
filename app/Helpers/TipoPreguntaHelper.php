<?php

namespace App\Helpers;

/**
 * Helper para manejar tipos de preguntas de encuesta
 * 
 * Este helper centraliza la lógica de tipos de preguntas
 * siguiendo los principios de DRY y Single Responsibility
 */
class TipoPreguntaHelper
{
    /**
     * Obtiene todos los tipos de preguntas disponibles
     */
    public static function getTiposDisponibles(): array
    {
        return [
            'seleccion_unica' => [
                'nombre' => 'Selección Única',
                'descripcion' => 'El usuario puede seleccionar una sola opción',
                'icono' => 'fas fa-dot-circle',
                'requiere_opciones' => true,
                'requiere_parametros' => true
            ],
            'seleccion_multiple' => [
                'nombre' => 'Selección Múltiple',
                'descripcion' => 'El usuario puede seleccionar varias opciones',
                'icono' => 'fas fa-check-square',
                'requiere_opciones' => true,
                'requiere_parametros' => true
            ],
            'texto_corto' => [
                'nombre' => 'Texto Corto',
                'descripcion' => 'Respuesta de texto corto (máximo 255 caracteres)',
                'icono' => 'fas fa-font',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ],
            'texto_largo' => [
                'nombre' => 'Texto Largo',
                'descripcion' => 'Respuesta de texto largo (máximo 1000 caracteres)',
                'icono' => 'fas fa-align-left',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ],
            'numero' => [
                'nombre' => 'Número',
                'descripcion' => 'Respuesta numérica',
                'icono' => 'fas fa-hashtag',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ],
            'fecha' => [
                'nombre' => 'Fecha',
                'descripcion' => 'Selección de fecha',
                'icono' => 'fas fa-calendar',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ],
            'escala' => [
                'nombre' => 'Escala',
                'descripcion' => 'Escala de 1 a 5 o 1 a 10',
                'icono' => 'fas fa-star',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ],
            'archivo' => [
                'nombre' => 'Archivo',
                'descripcion' => 'Subida de archivo',
                'icono' => 'fas fa-file-upload',
                'requiere_opciones' => false,
                'requiere_parametros' => false
            ]
        ];
    }

    /**
     * Obtiene información de un tipo específico
     */
    public static function getTipoInfo(string $tipo): ?array
    {
        $tipos = self::getTiposDisponibles();
        return $tipos[$tipo] ?? null;
    }

    /**
     * Verifica si un tipo requiere opciones
     */
    public static function requiereOpciones(string $tipo): bool
    {
        $info = self::getTipoInfo($tipo);
        return $info ? $info['requiere_opciones'] : false;
    }

    /**
     * Verifica si un tipo requiere parámetros
     */
    public static function requiereParametros(string $tipo): bool
    {
        $info = self::getTipoInfo($tipo);
        return $info ? $info['requiere_parametros'] : false;
    }

    /**
     * Obtiene el nombre legible de un tipo
     */
    public static function getNombre(string $tipo): string
    {
        $info = self::getTipoInfo($tipo);
        return $info ? $info['nombre'] : 'Desconocido';
    }

    /**
     * Obtiene el icono de un tipo
     */
    public static function getIcono(string $tipo): string
    {
        $info = self::getTipoInfo($tipo);
        return $info ? $info['icono'] : 'fas fa-question';
    }
}
