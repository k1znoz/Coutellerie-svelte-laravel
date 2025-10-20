<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Connexion utilisateur
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // Tentative de connexion avec email ou name
        $user = User::where('email', $request->username)
                   ->orWhere('name', $request->username)
                   ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['Les informations d\'identification sont incorrectes.'],
            ]);
        }

        // Supprimer les anciens tokens
        $user->tokens()->delete();

        // Créer un nouveau token
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'expiresIn' => config('sanctum.expiration', 525600), // Minutes (1 an par défaut)
        ]);
    }

    /**
     * Vérification de token
     */
    public function verify(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        try {
            // Le middleware auth:sanctum a déjà vérifié le token
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'error' => 'Token invalide',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Token invalide',
            ], 401);
        }
    }

    /**
     * Rafraîchissement de token (pour l'instant, on retourne le même)
     */
    public function refresh(Request $request)
    {
        $request->validate([
            'refreshToken' => 'required|string',
        ]);

        // Pour l'instant, retourner une erreur car pas implémenté
        return response()->json([
            'success' => false,
            'error' => 'Rafraîchissement de token non implémenté',
        ], 501);
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        // Supprimer le token actuel
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Déconnexion réussie',
        ]);
    }
}
