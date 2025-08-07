<?php

namespace App\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;

class RejectOrderForm
{
    public static function make(): array
    {
        return [
            Textarea::make('rejection_reason')
                ->label('Rejection Reason')
                ->placeholder('Please describe why this order is being rejected...')
                ->rows(4)
                ->helperText('This explanation will be shown to the person who requested the order.')
        ];
    }
   
}