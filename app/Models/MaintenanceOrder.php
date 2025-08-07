<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Filament\Notifications\Notification;

class MaintenanceOrder extends Model
{
    protected $fillable = [
        'title',
        'status',
        'priority',
        'rejection_reason',
        'asset_id',
        'user_id',
    ];

    protected $attributes = [
        'status' => Status::Created->value, 
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function start(): void
    {
        if (!$this->canBeStarted()) {
            throw new \Exception('This order cannot be started');
        }

        $this->update(['status' => Status::InProgress->value]);
    }

    public function markAsPending(): void
    {
        if (!$this->canBeMarkedAsPending()) {
            throw new \Exception('This order cannot be marked as pending');
        }

        $this->update(['status' => Status::Pending->value]);
    }

    public function approve(): void
    {
        if (!$this->canBeApproved()) {
            throw new \Exception('This order cannot be approved');
        }

        $this->update(['status' => Status::Approved->value]);
    }

    public function reject(?string $reason = null): void
    {
        if (!$this->canBeRejected()) {
            throw new \Exception('This order cannot be rejected');
        }

        $this->update([
            'status' => Status::Rejected->value,
            'rejection_reason' => $reason
        ]);

        Notification::make()
            ->title('Order rejected')
            ->success()
            ->send();
    }

    public function isCreated(): bool
    {
        return $this->status === Status::Created->value;
    }

    public function isInProgress(): bool
    {
        return $this->status === Status::InProgress->value;
    }

    public function isPending(): bool
    {
        return $this->status === Status::Pending->value;
    }

    // Métodos de verificación de permisos
    public function canBeStarted(): bool
    {
        return auth()->user()?->isTechnician() && $this->isCreated();
    }

    public function canBeMarkedAsPending(): bool
    {
        $user = auth()->user();
        return ($user?->isTechnician() || $user?->isSupervisor()) && $this->isInProgress();
    }

    public function canBeApproved(): bool
    {
        return auth()->user()?->isSupervisor() && $this->isPending();
    }

    public function canBeRejected(): bool
    {
        return auth()->user()?->isSupervisor() && $this->isPending();
    }

    public function canViewRejectionReason(): bool
    {
        return $this->status === Status::Rejected->value && !empty($this->rejection_reason);
    }

    public function hasAvailableActions(): bool
    {
        $user = auth()->user();
        
        return $this->canBeStarted($user)
        || $this->canBeMarkedAsPending($user)
        || $this->canBeApproved($user)
        || $this->canBeRejected($user)
        || $this->canViewRejectionReason();
    }
}
