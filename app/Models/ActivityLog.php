<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * A single, append-only audit trail for everything worth knowing "who did
 * what, when" about: auth, registration, profile edits, blood requests,
 * notifications, inventory changes, donations, appointment actions, AI
 * predictions, and admin management actions. Mirrors Notification's
 * poor-man's-polymorphism (actor_type + actor_id, not a real FK) so an
 * actor's account can be deleted later without breaking old log rows --
 * actor_name is a snapshot taken at write time for the same reason.
 */
class ActivityLog extends Model
{
    use HasFactory;

    public const UPDATED_AT = null;

    protected $fillable = [
        'actor_type', 'actor_id', 'actor_name', 'category', 'action',
        'description', 'subject_type', 'subject_id', 'properties', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'properties' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function categoryClass(): string
    {
        return match ($this->category) {
            'auth', 'admin' => 'al-red',
            'registration', 'donation' => 'al-green',
            'notification', 'appointment' => 'al-blue',
            default => 'al-gray',
        };
    }

    // Resolves the actor for the currently active guard, checked in
    // admin > hospital > donor order -- there's no single "current user"
    // concept shared across the app's three independent guards. Falls back
    // to 'system' when nothing is authenticated (e.g. logic that runs
    // before a brand-new account is logged in, like registration).
    public static function currentActor(): array
    {
        if (auth('admin')->check()) {
            $admin = auth('admin')->user();
            return ['admin', $admin->id, $admin->name];
        }
        if (auth('hospital')->check()) {
            $hospital = auth('hospital')->user();
            return ['hospital', $hospital->id, $hospital->name];
        }
        if (auth('donor')->check()) {
            $donor = auth('donor')->user();
            return ['donor', $donor->id, $donor->full_name];
        }

        return ['system', null, 'System'];
    }

    public static function record(
        string $actorType,
        ?int $actorId,
        ?string $actorName,
        string $category,
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $properties = []
    ): self {
        return self::create([
            'actor_type'   => $actorType,
            'actor_id'     => $actorId,
            'actor_name'   => $actorName,
            'category'     => $category,
            'action'       => $action,
            'description'  => $description,
            'subject_type' => $subjectType,
            'subject_id'   => $subjectId,
            'properties'   => $properties,
            'ip_address'   => request()?->ip(),
        ]);
    }

    // Convenience wrapper that auto-resolves the actor from whichever guard
    // is currently authenticated -- used everywhere except login (actor is
    // known explicitly), logout (actor must be captured before the guard is
    // cleared), and registration (the actor is the brand-new account, not
    // yet logged in when it's created).
    public static function log(
        string $category,
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null,
        array $properties = []
    ): self {
        [$actorType, $actorId, $actorName] = self::currentActor();

        return self::record($actorType, $actorId, $actorName, $category, $action, $description, $subjectType, $subjectId, $properties);
    }
}
