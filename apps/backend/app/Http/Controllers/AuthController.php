<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\AuthResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Enregistrer un nouvel utilisateur
     * POST /api/auth/register
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 'particulier',
            'phone' => $request->phone,
            'bio' => $request->bio,
            'is_active' => true,
            'is_verified' => false, // Email verification pas implémentée pour maintenant
        ]);

        // Générer le token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => (new AuthResource($user))->withToken($token),
            'message' => 'Enregistrement réussi',
        ], 201);
    }

    /**
     * Se connecter
     * POST /api/auth/login
     */
    public function login(LoginRequest $request): JsonResponse
    {
        // Récupérer l'utilisateur
        $user = User::where('email', $request->email)->first();

        // Vérifier le mot de passe
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email ou mot de passe incorrect',
                'errors' => [
                    'email' => ['Email ou mot de passe incorrect'],
                ],
            ], 401);
        }

        // Vérifier que l'utilisateur est actif
        if (!$user->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Ce compte est désactivé',
            ], 403);
        }

        // Générer le token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => (new AuthResource($user))->withToken($token),
            'message' => 'Connexion réussie',
        ]);
    }

    /**
     * Récupérer l'utilisateur actuel
     * GET /api/auth/me (protégé)
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => new AuthResource($user),
        ]);
    }

    /**
     * Se déconnecter
     * POST /api/auth/logout (protégé)
     */
    public function logout(Request $request): JsonResponse
    {
        // Révoquer le token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ]);
    }

    /**
     * Rafraîchir le token
     * POST /api/auth/refresh (protégé)
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        // Révoquer l'ancien token
        $request->user()->currentAccessToken()->delete();

        // Générer un nouveau token
        $newToken = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => (new AuthResource($user))->withToken($newToken),
            'message' => 'Token rafraîchi',
        ]);
    }
}