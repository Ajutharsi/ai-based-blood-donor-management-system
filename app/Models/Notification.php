<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_type', 'user_id', 'title', 'message', 'type', 'related_id', 'read_at',
    ];

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    // Maps a notification's type to one of the dot-color classes already
    // defined on every dashboard (nd-g/nd-b/nd-r -- green/blue/amber, never
    // red), so attention-worthy alerts (shortage/anomaly) stand out from
    // routine or positive updates without introducing a new color scheme.
    public function dotClass(): string
    {
        return match ($this->type) {
            'fulfillment', 'donor_match', 'appointment_approved', 'appointment_completed' => 'nd-g',
            'shortage_alert', 'ai_alert', 'appointment_rejected', 'appointment_cancelled' => 'nd-r',
            default => 'nd-b',
        };
    }

    public function isRead(): bool
    {
        return $this->read_at !== null;
    }

    public function markAsRead(): void
    {
        if ($this->read_at === null) {
            $this->update(['read_at' => now()]);
        }
    }

    // Best-effort link to the resource a notification is about, from the
    // viewing role's perspective -- the same notification can point
    // somewhere different (or nowhere) depending on who's looking at it.
    public function urlFor(string $viewerType): ?string
    {
        if (!$this->related_id) {
            return $viewerType === 'admin' && $this->type === 'shortage_alert'
                ? route('admin.dashboard')
                : null;
        }

        return match (true) {
            $viewerType === 'donor' && in_array($this->type, ['blood_request', 'donor_match', 'fulfillment'])
                => route('donor.requests.index'),
            $viewerType === 'donor' && str_starts_with($this->type, 'appointment_')
                => route('donor.appointments.index'),
            $viewerType === 'hospital' && $this->type === 'donor_response'
                => route('hospital.requests.show', $this->related_id),
            $viewerType === 'hospital' && str_starts_with($this->type, 'appointment_')
                => route('hospital.appointments.index'),
            $viewerType === 'admin' && $this->type === 'hospital_registration'
                => route('admin.hospitals.show', $this->related_id),
            $viewerType === 'admin' && $this->type === 'shortage_alert'
                => route('admin.dashboard'),
            $viewerType === 'admin' && $this->type === 'ai_alert'
                => route('admin.donors.show', $this->related_id),
            $viewerType === 'admin' && $this->type === 'appointment_completed'
                => route('admin.appointments.index'),
            default => null,
        };
    }

    public static function notify(string $userType, int $userId, string $title, string $message, string $type, ?int $relatedId = null): self
    {
        return self::create([
            'user_type'  => $userType,
            'user_id'    => $userId,
            'title'      => $title,
            'message'    => $message,
            'type'       => $type,
            'related_id' => $relatedId,
        ]);
    }

    // Broadcasts a notification to every admin account -- used for
    // system-wide alerts (new hospital registrations, critical shortages,
    // AI anomaly flags) that aren't tied to a single admin.
    public static function notifyAllAdmins(string $title, string $message, string $type, ?int $relatedId = null): void
    {
        foreach (Admin::pluck('id') as $adminId) {
            self::notify('admin', $adminId, $title, $message, $type, $relatedId);
        }
    }
}
