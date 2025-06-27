<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Filtres
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->past();
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Pagination et tri
        $events = $query->orderBy('event_date', 'desc')->paginate(12);
        
        // Statistiques pour l'interface d'administration future
        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::upcoming()->count(),
            'past' => Event::past()->count(),
            'featured' => Event::featured()->count()
        ];

        // Types d'événements pour le filtre
        $eventTypes = [
            'cultural' => 'Culturel',
            'educational' => 'Éducatif',
            'social' => 'Social', 
            'community' => 'Communautaire',
            'sports' => 'Sportif',
            'other' => 'Autre'
        ];

        return view('events.index', compact('events', 'stats', 'eventTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $eventTypes = [
            'cultural' => 'Culturel',
            'educational' => 'Éducatif',
            'social' => 'Social',
            'community' => 'Communautaire', 
            'sports' => 'Sportif',
            'other' => 'Autre'
        ];

        $statusOptions = [
            'upcoming' => 'À venir',
            'ongoing' => 'En cours',
            'completed' => 'Terminé',
            'cancelled' => 'Annulé'
        ];

        return view('events.create', compact('eventTypes', 'statusOptions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date|after_or_equal:today',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'type' => 'required|in:cultural,educational,social,community,sports,other',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240' // 10MB max
        ]);

        // Gérer l'upload de l'image
        if ($request->hasFile('image')) {
            $validated['image'] = $this->handleImageUpload($request->file('image'));
        }

        // Préparer les informations de contact
        $contactInfo = [];
        if ($request->filled('contact_email')) {
            $contactInfo['email'] = $request->contact_email;
        }
        if ($request->filled('contact_phone')) {
            $contactInfo['phone'] = $request->contact_phone;
        }
        $validated['contact_info'] = $contactInfo;

        // Ajouter l'utilisateur créateur (quand l'auth sera implémentée)
        // $validated['created_by'] = auth()->id();

        $event = Event::create($validated);

        return redirect()->route('events.show', $event)
                        ->with('success', 'Événement créé avec succès !');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        // Événements similaires (même type)
        $relatedEvents = Event::where('type', $event->type)
                             ->where('id', '!=', $event->id)
                             ->upcoming()
                             ->limit(3)
                             ->get();

        return view('events.show', compact('event', 'relatedEvents'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $eventTypes = [
            'cultural' => 'Culturel',
            'educational' => 'Éducatif', 
            'social' => 'Social',
            'community' => 'Communautaire',
            'sports' => 'Sportif',
            'other' => 'Autre'
        ];

        $statusOptions = [
            'upcoming' => 'À venir',
            'ongoing' => 'En cours', 
            'completed' => 'Terminé',
            'cancelled' => 'Annulé'
        ];

        return view('events.edit', compact('event', 'eventTypes', 'statusOptions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255', 
            'description' => 'required|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i',
            'location' => 'required|string|max:255',
            'type' => 'required|in:cultural,educational,social,community,sports,other',
            'status' => 'required|in:upcoming,ongoing,completed,cancelled',
            'max_participants' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'requirements' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_featured' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240', // 10MB max
            'remove_image' => 'boolean'
        ]);

        // Gérer la suppression de l'image
        if ($request->boolean('remove_image') && $event->image) {
            $this->deleteImage($event->image);
            $validated['image'] = null;
        }

        // Gérer l'upload de la nouvelle image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($event->image) {
                $this->deleteImage($event->image);
            }
            $validated['image'] = $this->handleImageUpload($request->file('image'));
        }

        // Préparer les informations de contact
        $contactInfo = [];
        if ($request->filled('contact_email')) {
            $contactInfo['email'] = $request->contact_email;
        }
        if ($request->filled('contact_phone')) {
            $contactInfo['phone'] = $request->contact_phone;
        }
        $validated['contact_info'] = $contactInfo;

        // Ajouter l'utilisateur qui modifie (quand l'auth sera implémentée)
        // $validated['updated_by'] = auth()->id();

        $event->update($validated);

        return redirect()->route('events.show', $event)
                        ->with('success', 'Événement modifié avec succès !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        // L'image sera automatiquement supprimée grâce au boot() du modèle
        $event->delete();

        return redirect()->route('events.index')
                        ->with('success', 'Événement supprimé avec succès !');
    }

    /**
     * Gérer l'upload d'une image
     */
    private function handleImageUpload($file)
    {
        // Générer un nom unique pour le fichier
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Stocker le fichier dans storage/app/public/events
        $path = $file->storeAs('events', $filename, 'public');
        
        return $path;
    }

    /**
     * Supprimer une image
     */
    private function deleteImage($imagePath)
    {
        if ($imagePath && str_starts_with($imagePath, 'events/')) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * API endpoint pour obtenir les événements (utile pour future interface admin)
     */
    public function apiIndex(Request $request)
    {
        $query = Event::query();

        if ($request->filled('status')) {
            if ($request->status === 'upcoming') {
                $query->upcoming();
            } elseif ($request->status === 'past') {
                $query->past();
            }
        }

        $events = $query->orderBy('event_date', 'desc')->get();

        return response()->json([
            'events' => $events->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->event_date->format('d/m/Y'),
                    'location' => $event->location,
                    'status' => $event->status_label,
                    'participants' => $event->current_participants,
                    'max_participants' => $event->max_participants,
                    'image_url' => $event->image_url
                ];
            })
        ]);
    }

    /**
     * Toggle featured status
     */
    public function toggleFeatured(Event $event)
    {
        $event->update(['is_featured' => !$event->is_featured]);
        
        $status = $event->is_featured ? 'mis en avant' : 'retiré de la mise en avant';
        
        return redirect()->back()
                        ->with('success', "Événement {$status} avec succès !");
    }
}