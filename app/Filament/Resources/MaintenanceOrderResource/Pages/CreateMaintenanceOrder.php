<?php

namespace App\Filament\Resources\MaintenanceOrderResource\Pages;

use App\Filament\Resources\MaintenanceOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMaintenanceOrder extends CreateRecord
{
    protected static string $resource = MaintenanceOrderResource::class;

    protected function getRedirectUrl(): string
    {
        return url('/admin/maintenance-orders');
    }
    
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Maintenance order created successfully.';
    }
}
