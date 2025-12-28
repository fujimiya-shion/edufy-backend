<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Models\Role;
use App\Services\Implementations\Role\RoleService;
use App\Services\Contracts\Role\IRoleService;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

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
                ->requiresConfirmation()
                ->color('danger')
                ->modalHeading(__('admin-management.action_confirms.delete.title'))
                ->modalDescription(__('admin-management.action_confirms.delete.message'))
                ->modalSubmitActionLabel(__('admin-management.action_confirms.delete.buttons.confirm')),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Get all permissions assigned to this role
        $rolePermissions = $this->record->permissions->pluck('name')->toArray();

        // Group the permissions by module
        $permissions = \App\Models\Permission::all()->groupBy(function ($item) {
            $nameParts = explode('-', $item->name);
            return implode('-', array_slice($nameParts, 1));
        });

        // For each permission module, set the selected permissions
        foreach ($permissions as $module => $perms) {
            // Get permissions for this module that are assigned to the role
            $modulePermissions = $perms->pluck('name')->toArray();
            $selectedPermissions = array_intersect($rolePermissions, $modulePermissions);

            // Add to form data
            $data['permissions_' . $module] = $selectedPermissions;

            // Check if all permissions for this module are selected
            $data['select_all_' . $module] = count($selectedPermissions) === count($modulePermissions);
        }

        return $data;
    }

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        return app(RoleService::class)->updateRole($record, $data);
    }
}
