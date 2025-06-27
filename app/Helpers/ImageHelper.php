<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
// Removed the Image facade import since it's optional

class ImageHelper
{
    /**
     * Upload et optimise une image pour les événements
     */
    public static function uploadEventImage(UploadedFile $file, ?string $oldImagePath = null): string
    {
        // Supprimer l'ancienne image si elle existe
        if ($oldImagePath) {
            self::deleteImage($oldImagePath);
        }

        // Générer un nom unique
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Redimensionner et optimiser l'image (optionnel - nécessite intervention/image)
        if (class_exists('Intervention\Image\Facades\Image')) {
            $imageClass = app('Intervention\Image\Facades\Image');
            $image = $imageClass::make($file);
            
            // Redimensionner si trop grande (max 1920x1080)
            $image->resize(1920, 1080, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            
            // Optimiser la qualité
            $image->encode($file->getClientOriginalExtension(), 85);
            
            // Sauvegarder
            $path = 'events/' . $filename;
            Storage::disk('public')->put($path, $image->stream());
        } else {
            // Upload simple sans optimisation
            $path = $file->storeAs('events', $filename, 'public');
        }
        
        return $path;
    }

    /**
     * Upload une image d'avatar (pour futurs membres)
     */
    public static function uploadAvatarImage(UploadedFile $file, ?string $oldImagePath = null): string
    {
        if ($oldImagePath) {
            self::deleteImage($oldImagePath);
        }

        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        if (class_exists('Intervention\Image\Facades\Image')) {
            $imageClass = app('Intervention\Image\Facades\Image');
            $image = $imageClass::make($file);
            
            // Créer un carré de 300x300 pour les avatars
            $image->fit(300, 300);
            $image->encode('jpg', 90);
            
            $path = 'avatars/' . $filename;
            Storage::disk('public')->put($path, $image->stream());
        } else {
            $path = $file->storeAs('avatars', $filename, 'public');
        }
        
        return $path;
    }

    /**
     * Supprimer une image
     */
    public static function deleteImage(string $imagePath): bool
    {
        if (Storage::disk('public')->exists($imagePath)) {
            return Storage::disk('public')->delete($imagePath);
        }
        
        return false;
    }

    /**
     * Obtenir l'URL complète d'une image
     */
    public static function getImageUrl(?string $imagePath, string $default = 'img/default-event.jpg'): string
    {
        if (!$imagePath) {
            return asset($default);
        }

        // Si c'est un chemin dans storage
        if (str_starts_with($imagePath, 'events/') || str_starts_with($imagePath, 'avatars/')) {
            return Storage::url($imagePath);
        }

        // Si c'est un chemin dans public
        if (str_starts_with($imagePath, 'img/')) {
            return asset($imagePath);
        }

        // Autres cas
        return Storage::url($imagePath);
    }

    /**
     * Vérifier si une image existe
     */
    public static function imageExists(?string $imagePath): bool
    {
        if (!$imagePath) {
            return false;
        }

        return Storage::disk('public')->exists($imagePath);
    }

    /**
     * Obtenir la taille d'un fichier en format lisible
     */
    public static function getFileSize(string $imagePath): string
    {
        if (!self::imageExists($imagePath)) {
            return 'N/A';
        }

        $bytes = Storage::disk('public')->size($imagePath);
        
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }

    /**
     * Valider le type et la taille d'une image
     */
    public static function validateImage(UploadedFile $file, int $maxSizeInMB = 10): array
    {
        $errors = [];
        
        // Vérifier le type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedTypes)) {
            $errors[] = 'Type de fichier non autorisé. Utilisez: JPEG, JPG, PNG, GIF ou WEBP.';
        }
        
        // Vérifier la taille
        $maxSizeInBytes = $maxSizeInMB * 1024 * 1024;
        if ($file->getSize() > $maxSizeInBytes) {
            $errors[] = "Le fichier est trop volumineux. Taille maximum: {$maxSizeInMB} MB.";
        }
        
        return $errors;
    }

    /**
     * Créer des images de différentes tailles (thumbnails)
     */
    public static function createThumbnails(string $imagePath): array
    {
        if (!class_exists('Intervention\Image\Facades\Image')) {
            return ['error' => 'Package Intervention Image non installé'];
        }

        $thumbnails = [];
        $originalImage = Storage::disk('public')->path($imagePath);
        
        if (!file_exists($originalImage)) {
            return ['error' => 'Image originale introuvable'];
        }

        $sizes = [
            'thumbnail' => ['width' => 150, 'height' => 150],
            'medium' => ['width' => 400, 'height' => 300],
            'large' => ['width' => 800, 'height' => 600]
        ];

        $imageClass = app('Intervention\Image\Facades\Image');
        $image = $imageClass::make($originalImage);
        $pathInfo = pathinfo($imagePath);
        
        foreach ($sizes as $sizeName => $dimensions) {
            $filename = $pathInfo['filename'] . '_' . $sizeName . '.' . $pathInfo['extension'];
            $thumbnailPath = $pathInfo['dirname'] . '/' . $filename;
            
            $thumbnail = clone $image;
            $thumbnail->fit($dimensions['width'], $dimensions['height']);
            
            Storage::disk('public')->put($thumbnailPath, $thumbnail->stream());
            $thumbnails[$sizeName] = $thumbnailPath;
        }

        return $thumbnails;
    }

    /**
     * Check if Intervention Image package is available
     */
    public static function isInterventionImageAvailable(): bool
    {
        return class_exists('Intervention\Image\Facades\Image');
    }

    /**
     * Get installation instructions for Intervention Image
     */
    public static function getInterventionImageInstructions(): string
    {
        return 'Pour activer l\'optimisation d\'images, installez le package: composer require intervention/image';
    }
}