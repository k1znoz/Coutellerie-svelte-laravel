<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Enregistrer un nouveau message de contact
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validation des données
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:2|max:100',
                'email' => 'required|email|max:100',
                'subject' => 'nullable|string|max:200',
                'message' => 'required|string|min:10',
            ], [
                'name.required' => 'Le nom est obligatoire',
                'name.min' => 'Le nom doit contenir au moins 2 caractères',
                'email.required' => 'L\'email est obligatoire',
                'email.email' => 'L\'email doit être valide',
                'message.required' => 'Le message est obligatoire',
                'message.min' => 'Le message doit contenir au moins 10 caractères',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'error' => 'Données invalides',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Protection anti-spam basique
            if ($this->isSpam($request->input('message'))) {
                Log::warning('Message de contact détecté comme spam', [
                    'email' => $request->input('email'),
                    'ip' => $request->ip(),
                    'message_length' => strlen($request->input('message'))
                ]);

                return response()->json([
                    'success' => false,
                    'error' => 'Message rejeté'
                ], 429);
            }

            // Créer le message de contact
            $contactMessage = ContactMessage::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'subject' => $request->input('subject') ?: 'Message de contact',
                'message' => $request->input('message'),
                'status' => ContactMessage::STATUS_NEW,
                'ip_address' => $request->ip(),
            ]);

            // Log du nouveau message
            Log::info('Nouveau message de contact reçu', [
                'id' => $contactMessage->id,
                'email' => $contactMessage->email,
                'subject' => $contactMessage->subject
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Message envoyé avec succès',
                'timestamp' => now()->toISOString()
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'enregistrement du message de contact', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de l\'envoi du message'
            ], 500);
        }
    }

    /**
     * Détection basique de spam
     */
    private function isSpam(string $message): bool
    {
        // Vérifier la longueur minimale
        if (strlen($message) < 10) {
            return true;
        }

        // Patterns suspects
        $suspiciousPatterns = [
            '/http[s]?:\/\/[^\s]+/i',  // URLs
            '/www\.[^\s]+/i',          // Domaines
            '/\b\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\b/', // IPs
            '/bitcoin|crypto|investment|loan|casino/i', // Mots suspects
        ];

        foreach ($suspiciousPatterns as $pattern) {
            if (preg_match($pattern, $message)) {
                return true;
            }
        }

        // Vérifier répétition excessive de caractères
        if (preg_match('/(.)\1{10,}/', $message)) {
            return true;
        }

        return false;
    }
}
