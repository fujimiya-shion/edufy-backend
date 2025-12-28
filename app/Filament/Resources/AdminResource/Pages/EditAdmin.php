<?php

namespace App\Filament\Resources\AdminResource\Pages;

use App\Filament\Resources\AdminResource;
use App\Models\Admin;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditAdmin extends EditRecord
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label(__('admin-management.actions.delete'))
                ->icon('heroicon-m-trash')
                ->action(function ($record) {
                    $record->delete();
                    $this->notify('success', __('admin-management.action_successes.delete'));
                })
                ->hidden(function (Admin $record) {
                    return $record->hasRole('super-admin') || $record->is(auth('admin')->user());
                })
                ->requiresConfirmation()
                ->color('danger')
                ->modalHeading(__('admin-management.action_confirms.delete.title'))
                ->modalDescription(__('admin-management.action_confirms.delete.message'))
                ->modalSubmitActionLabel(__('admin-management.action_confirms.delete.buttons.confirm')),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($this->record->hasRole('super-admin')) {
            $data['roles'] = array_merge(
                [$this->getSuperAdminRoleId()],
                $data['roles'] ?? []
            );
        }

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);
        $record->syncRoles($data['roles'] ?? []);
        return $record;
    }

    private function getSuperAdminRoleId(): int
    {
        return \App\Models\Role::where('name', 'super-admin')->value('id');
    }
}
