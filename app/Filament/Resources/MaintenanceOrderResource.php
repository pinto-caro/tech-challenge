<?php

namespace App\Filament\Resources;

use App\Enums\Priority;
use App\Enums\Status;
use App\Enums\UserRole;
use App\Filament\Resources\MaintenanceOrderResource\Pages;
use App\Filament\Resources\MaintenanceOrderResource\RelationManagers;
use Filament\Notifications\Notification;
use App\Forms\RejectOrderForm;
use App\Models\MaintenanceOrder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\Auth;
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
                    ->label('Related Asset')
                    ->relationship('asset', 'name')
                    ->required(),
                    
                    Forms\Components\Select::make('user_id')
                    ->label('Assigned Technician')
                    ->relationship(
                        name: 'user',
                        titleAttribute: 'name',
                        modifyQueryUsing: (fn (Builder $query) => $query->where('role', UserRole::Technician->value))
                    )
                    ->required(),

                Forms\Components\Select::make('priority')
                    ->options([
                        Priority::High->value => Priority::High->label(),
                        Priority::Medium->value => Priority::Medium->label(),
                        Priority::Low->value => Priority::Low->label(),
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\BadgeColumn::make('status')
                    ->badge()
                    ->color(fn ($state): string => Status::tryFrom($state)?->color() ?? 'gray'),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->formatStateUsing(fn ($state) => Priority::tryFrom($state)?->label())
                    ->color(fn ($state): string => Priority::tryFrom($state)?->color() ?? 'gray')
                    ->icon(fn ($state): string => Priority::tryFrom($state)?->icon() ?? 'heroicon-o-question-mark-circle'),

                Tables\Columns\TextColumn::make('asset.name'),

                Tables\Columns\TextColumn::make('user.name'),
            ])

            ->defaultSort('priority', 'asc')

            ->modifyQueryUsing(function (Builder $query) {
                $user = Auth::user();

                if ($user->isTechnician()) {
                    $query->where('user_id', $user->id);
                }

                return $query;
            })

            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Action::make('start')
                        ->label('Mark as In Progress')
                        ->icon('heroicon-o-play')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->canBeStarted())
                        ->action(fn ($record) => $record->start()),

                    Action::make('mark_pending')
                        ->label('Mark as Pending Approval')
                        ->icon('heroicon-o-clock')
                        ->color('info')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->canBeMarkedAsPending())
                        ->action(fn ($record) => $record->markAsPending()),

                    Action::make('approve')
                        ->label('Approve')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->canBeApproved())
                        ->action(fn ($record) => $record->approve()),

                    Action::make('reject')
                        ->label('Reject')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->canBeRejected())
                        ->action(fn ($record, $data) => $record->reject($data['rejection_reason']))
                        ->modalHeading('Reject Maintenance Order')
                        ->modalDescription('This action will mark the order as rejected.')
                        ->modalSubmitActionLabel('Reject')
                        ->form(RejectOrderForm::make()),

                    Action::make('view_rejection_reason')
                        ->label('View rejection reason')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->color('danger')
                        ->modalHeading('Rejection reason')
                        ->modalDescription(fn ($record) => $record->rejection_reason)
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close')
                        ->visible(fn ($record) => $record->canViewRejectionReason()),
                ])
                ->label('Actions')
                ->icon('heroicon-m-ellipsis-vertical')
                ->color('gray')
                ->button()
                ->visible(fn ($record) => $record->hasAvailableActions()),
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
