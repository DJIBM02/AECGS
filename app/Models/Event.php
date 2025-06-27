<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'subtitle', 
        'description',
        'image',
        'event_date',
        'event_time',
        'location',
        'status',
        'type',
        'max_participants',
        'current_participants',
        'is_featured',
        'price',
        'requirements',
        'contact_info',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_time' => 'datetime:H:i',
        'contact_info' => 'array',
        'is_featured' => 'boolean',
        'price' => 'decimal:2'
    ];

    protected $dates = [
        'event_date',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Obtenir l'URL complète de l'image
     */
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            // Si c'est un chemin local
            if (str_starts_with($this->image, 'events/')) {
                return Storage::url($this->image);
            }
            // Si c'est un chemin dans public
            if (str_starts_with($this->image, 'img/')) {
                return asset($this->image);
            }
            // Autres cas
            return Storage::url($this->image);
        }
        
        // Image par défaut
        return asset('img/default-event.jpg');
    }

    /**
     * Obtenir une version optimisée de l'image pour les thumbnails
     */
    public function getImageThumbnailAttribute()
    {
        // Pour l'instant retourne la même image
        // Plus tard on peut implémenter un système de redimensionnement
        return $this->image_url;
    }

    /**
     * Scopes pour filtrer les événements
     */
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now()->toDateString())
                    ->where('status', 'upcoming')
                    ->orderBy('event_date');
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', now()->toDateString())
                    ->orWhere('status', 'completed')
                    ->orderBy('event_date', 'desc');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Vérifier si l'événement est complet
     */
    public function getIsFullAttribute()
    {
        if (!$this->max_participants) {
            return false;
        }
        return $this->current_participants >= $this->max_participants;
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'upcoming' => 'À venir',
            'ongoing' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé',
            default => 'Inconnu'
        };
    }

    /**
     * Obtenir le type formaté
     */
    public function getTypeLabelAttribute()
    {
        return match($this->type) {
            'cultural' => 'Culturel',
            'educational' => 'Éducatif', 
            'social' => 'Social',
            'community' => 'Communautaire',
            'sports' => 'Sportif',
            'other' => 'Autre',
            default => 'Communautaire'
        };
    }

    /**
     * Relation avec les utilisateurs (pour plus tard)
     * Uncomment when User model is ready
     */
    /*
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater() 
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
                    ->withPivot('registration_date', 'status')
                    ->withTimestamps();
    }
    */

    /**
     * Boot method pour gérer les événements du modèle
     */
    protected static function boot()
    {
        parent::boot();

        // Supprimer le fichier image lors de la suppression de l'événement
        static::deleting(function ($event) {
            if ($event->image && str_starts_with($event->image, 'events/')) {
                Storage::delete($event->image);
            }
        });
    }
}