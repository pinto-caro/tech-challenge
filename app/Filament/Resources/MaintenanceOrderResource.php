<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceOrderResource\Pages;
use App\Filament\Resources\MaintenanceOrderResource\RelationManagers;
use App\Models\MaintenanceOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaintenanceOrderResource extends Resource
{
    protected static ?string $model = MaintenanceOrder::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->required(),
                Forms\Components\Select::make('asset_id')
                ->relationship('asset', 'name')
                ->required(),
                Forms\Components\Select::make('user_id')
                #->relationship('technician', 'name')
                ->relationship(
                    name: 'user',
                    titleAttribute: 'name',
                    modifyQueryUsing: (fn (Builder $query) => $query->where('role', 'technician'))
                )
                ->required(),
                Forms\Components\Select::make('priority')
                ->options([
                    'high' => 'High',
                    'medium' => 'Medium',
                    'low' => 'Low',
                ])
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMaintenanceOrders::route('/'),
            'create' => Pages\CreateMaintenanceOrder::route('/create'),
            'edit' => Pages\EditMaintenanceOrder::route('/{record}/edit'),
        ];
    }
}
