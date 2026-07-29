<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Http\Resources\ListingResource;
use App\Http\Requests\StoreListingRequest;
use App\Http\Requests\UpdateListingRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    /**
     * Récupérer tous les listings (paginés, avec filtres)
     * GET /api/listings
     */
    public function index(Request $request): JsonResponse
    {
        $query = Listing::with('user');

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('device_type')) {
            $query->where('device_type', $request->device_type);
        }

        if ($request->has('condition')) {
            $query->where('condition', $request->condition);
        }

        if ($request->has('price_min') || $request->has('price_max')) {
            $min = $request->price_min ?? 0;
            $max = $request->price_max ?? 999999.99;
            $query->whereBetween('price', [$min, $max]);
        }

        if ($request->has('search')) {
            $query->search($request->search);
        }

        // Tri
        $sortBy = $request->sort_by ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = min($request->per_page ?? 15, 100);
        $listings = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => ListingResource::collection($listings->items()),
            'meta' => [
                'current_page' => $listings->currentPage(),
                'last_page' => $listings->lastPage(),
                'per_page' => $listings->perPage(),
                'total' => $listings->total(),
            ],
        ]);
    }

    /**
     * Créer un nouveau listing
     * POST /api/listings
     */
    public function store(StoreListingRequest $request): JsonResponse
    {
        $listing = auth()->user()->listings()->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
            'message' => 'Listing créé avec succès',
        ], 201);
    }

    /**
     * Récupérer un listing spécifique
     * GET /api/listings/{id}
     */
    public function show(Listing $listing): JsonResponse
    {
        // Incrémenter les vues
        $listing->incrementViews();

        $listing->load('user', 'reviews.reviewer');

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
        ]);
    }

    /**
     * Mettre à jour un listing
     * PATCH /api/listings/{id}
     */
    public function update(UpdateListingRequest $request, Listing $listing): JsonResponse
    {
        // Vérifier l'autorisation
        if ($listing->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $listing->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
            'message' => 'Listing mis à jour avec succès',
        ]);
    }

    /**
     * Supprimer un listing
     * DELETE /api/listings/{id}
     */
    public function destroy(Listing $listing): JsonResponse
    {
        // Vérifier l'autorisation
        if ($listing->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $listing->delete();

        return response()->json([
            'success' => true,
            'message' => 'Listing supprimé avec succès',
        ]);
    }

    /**
     * Publier un listing (passer de draft à active)
     * POST /api/listings/{id}/publish
     */
    public function publish(Listing $listing): JsonResponse
    {
        if ($listing->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $listing->publish();

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
            'message' => 'Listing publié avec succès',
        ]);
    }

    /**
     * Marquer un listing comme vendu
     * POST /api/listings/{id}/mark-sold
     */
    public function markSold(Listing $listing): JsonResponse
    {
        if ($listing->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $listing->markAsSold();

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
            'message' => 'Listing marqué comme vendu',
        ]);
    }

    /**
     * Archiver un listing
     * POST /api/listings/{id}/archive
     */
    public function archive(Listing $listing): JsonResponse
    {
        if ($listing->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé',
            ], 403);
        }

        $listing->archive();

        return response()->json([
            'success' => true,
            'data' => new ListingResource($listing),
            'message' => 'Listing archivé',
        ]);
    }
}