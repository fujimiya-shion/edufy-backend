<?php

namespace App\Filament\Resources;

use App\Services\Contracts\IService;
use Filament\Resources\Resource;
use Filament\Tables\Actions\DeleteBulkAction;

abstract class BaseResource extends Resource
{
    protected static string $permissionPrefix = '';

    protected static bool $shouldRegisterNavigation = true;
    protected static string $serviceContract;

    protected static array $actionPermissions = [
        'viewAny' => 'read',
        'create' => 'create',
        'edit' => 'update',
        'delete' => 'delete',
    ];

    public static function getService(): IService
    {
        return app(static::$serviceContract);
    }

    protected static function hasPermission(string $action): bool
    {
        $user = auth('admin')->user();
        $prefix = static::$permissionPrefix;
        $permission = static::$actionPermissions[$action] ?? $action;

        return $user?->hasPermission("{$permission}-{$prefix}")
            || $user?->hasPermission("manage-{$prefix}") || $user->hasRole('super-admin');
    }

    public static function canViewAny(): bool
    {
        return static::hasPermission('viewAny');
    }

    public static function canCreate(): bool
    {
        return static::hasPermission('create');
    }

    public static function canEdit($record): bool
    {
        return static::hasPermission('edit');
    }

    public static function canDelete($record): bool
    {
        return static::hasPermission('delete');
    }

    public static function canAccess(): bool
    {
        return static::_checkRoleAndPermission();
    }

    public static function getAuthorizedDeleteBulkAction(): ?DeleteBulkAction
    {
        if (static::hasPermission('delete')) {
            return DeleteBulkAction::make()
                ->action(function (array $records) {
                    foreach ($records as $record) {
                        $record->delete();
                    }
                })
                ->requiresConfirmation()
                ->deselectRecordsAfterCompletion()
                ->label(__('base-resource.bulk-actions.delete.label'))
                ->modalHeading(__('base-resource.bulk-actions.delete.confirm.title'))
                ->modalDescription(__('base-resource.bulk-actions.delete.confirm.message'))
                ->modalSubmitActionLabel(__('base-resource.bulk-actions.delete.confirm.buttons.confirm'));
        }
        return null;
    }

    private static function _checkRoleAndPermission(): bool
    {
        $user = auth('admin')->user();
        $prefix = static::$permissionPrefix;
        return $user?->hasRole('super-admin')
            || $user?->hasPermission("manage-{$prefix}")
            || static::hasPermission("viewAny")
            || static::hasPermission("create")
            || static::hasPermission("edit")
            || static::hasPermission("delete");
    }

}
