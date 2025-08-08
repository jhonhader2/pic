<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Servicio para manejo de archivos
 * 
 * Responsabilidades:
 * - Subir archivos al storage
 * - Eliminar archivos del storage
 * - Validar tipos de archivo
 * - Generar nombres únicos
 * 
 * Principios aplicados: POO, DRY, KISS, Single Responsibility
 */
class FileService
{
    /**
     * Tipos de archivo permitidos por defecto
     */
    private const DEFAULT_ALLOWED_MIMES = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];

    /**
     * Tamaño máximo por defecto (5MB)
     */
    private const DEFAULT_MAX_SIZE = 5120;

    /**
     * Sube un archivo al storage
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $allowedMimes
     * @param int $maxSize
     * @return string
     * @throws \Exception
     */
    public function uploadFile(
        UploadedFile $file,
        string $directory = 'uploads',
        array $allowedMimes = self::DEFAULT_ALLOWED_MIMES,
        int $maxSize = self::DEFAULT_MAX_SIZE
    ): string {
        // Validar tipo de archivo
        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
            throw new \Exception('Tipo de archivo no permitido');
        }

        // Validar tamaño
        if ($file->getSize() > ($maxSize * 1024)) {
            throw new \Exception('El archivo excede el tamaño máximo permitido');
        }

        // Generar nombre único
        $fileName = $this->generateUniqueFileName($file);

        // Subir archivo
        $path = $file->storeAs($directory, $fileName, 'public');

        if (!$path) {
            throw new \Exception('Error al subir el archivo');
        }

        return $path;
    }

    /**
     * Elimina un archivo del storage
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteFile(string $filePath): bool
    {
        if (empty($filePath)) {
            return false;
        }

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->delete($filePath);
        }

        return false;
    }

    /**
     * Elimina múltiples archivos
     *
     * @param array $filePaths
     * @return int
     */
    public function deleteMultipleFiles(array $filePaths): int
    {
        $deletedCount = 0;

        foreach ($filePaths as $filePath) {
            if ($this->deleteFile($filePath)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Verifica si un archivo existe
     *
     * @param string $filePath
     * @return bool
     */
    public function fileExists(string $filePath): bool
    {
        return Storage::disk('public')->exists($filePath);
    }

    /**
     * Obtiene la URL pública de un archivo
     *
     * @param string $filePath
     * @return string|null
     */
    public function getFileUrl(string $filePath): ?string
    {
        if ($this->fileExists($filePath)) {
            return asset('storage/' . $filePath);
        }

        return null;
    }

    /**
     * Genera un nombre único para el archivo
     *
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqueFileName(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $baseName = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        $timestamp = now()->format('Y-m-d_H-i-s');
        $random = Str::random(8);

        return "{$baseName}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Valida un archivo según las reglas especificadas
     *
     * @param UploadedFile $file
     * @param array $allowedMimes
     * @param int $maxSize
     * @return array
     */
    public function validateFile(
        UploadedFile $file,
        array $allowedMimes = self::DEFAULT_ALLOWED_MIMES,
        int $maxSize = self::DEFAULT_MAX_SIZE
    ): array {
        $errors = [];

        // Validar tipo de archivo
        if (!in_array($file->getClientOriginalExtension(), $allowedMimes)) {
            $errors[] = 'Tipo de archivo no permitido. Tipos válidos: ' . implode(', ', $allowedMimes);
        }

        // Validar tamaño
        if ($file->getSize() > ($maxSize * 1024)) {
            $errors[] = 'El archivo excede el tamaño máximo de ' . ($maxSize / 1024) . 'MB';
        }

        return $errors;
    }

    /**
     * Obtiene información del archivo
     *
     * @param string $filePath
     * @return array|null
     */
    public function getFileInfo(string $filePath): ?array
    {
        if (!$this->fileExists($filePath)) {
            return null;
        }

        return [
            'name' => basename($filePath),
            'size' => Storage::disk('public')->size($filePath),
            'mime_type' => mime_content_type(storage_path('app/public/' . $filePath)),
            'last_modified' => Storage::disk('public')->lastModified($filePath),
            'url' => $this->getFileUrl($filePath)
        ];
    }
}
