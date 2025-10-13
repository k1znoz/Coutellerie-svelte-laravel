<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'status',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Les statuts possibles pour un message de contact
     */
    const STATUS_NEW = 'new';
    const STATUS_READ = 'read';
    const STATUS_REPLIED = 'replied';

    /**
     * Récupère tous les statuts disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW => 'Nouveau',
            self::STATUS_READ => 'Lu',
            self::STATUS_REPLIED => 'Répondu',
        ];
    }

    /**
     * Scope pour récupérer uniquement les nouveaux messages
     */
    public function scopeNew($query)
    {
        return $query->where('status', self::STATUS_NEW);
    }

    /**
     * Scope pour récupérer les messages par statut
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Marquer le message comme lu
     */
    public function markAsRead()
    {
        $this->update(['status' => self::STATUS_READ]);
    }

    /**
     * Marquer le message comme répondu
     */
    public function markAsReplied()
    {
        $this->update(['status' => self::STATUS_REPLIED]);
    }
}