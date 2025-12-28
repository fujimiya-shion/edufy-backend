<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoleResource\Pages;
use App\Models\Permission;
use App\Models\Role;
use App\Services\Contracts\Role\IRoleService;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;


class RoleResource extends BaseResource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $permissions = Permission::all()->groupBy(function ($item) {
            $nameParts = explode('-', $item->name);
            return implode('-', array_slice($nameParts, 1));
        });

        return $form
            ->schema([
                TextInput::make('display_name')
                    ->label(__('role-management.form.display_name'))
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(function (Forms\Set $set, $state) {
                        $set('name', strtolower(str_replace(' ', '-', trim($state))));
                    })
                    ->validationMessages([
                        'required' => __('role-management.validations.name_required'),
                        'max_length' => __('role-management.validations.name_max_length'),
                    ]),

                TextInput::make('name')
                    ->label(__('role-management.form.name'))
                    ->required()
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),

                Textarea::make('description')
                    ->label(__('role-management.form.description'))
                    ->maxLength(1000)
                    ->rows(3)
                    ->validationMessages([
                        'max_length' => __('role-management.validations.description_max_length'),
                    ]),


                ...$permissions->map(function ($perms, $module) {
                    return Section::make(ucfirst($module) . ' Permissions')
                    ->schema([
                        Checkbox::make('select_all_' . $module)
                            ->label(__('role-management.form.select_all_permissions'))
                            ->reactive()
                            ->afterStateUpdated(fn ($state, $set) => $set('permissions_' . $module, $state ? $perms->pluck('name')->toArray() : [])),

                        CheckboxList::make('permissions_' . $module)
                            ->options($perms->pluck('name', 'name')->toArray())
                            ->columns(2)
                            ->required()
                            ->disabled(fn ($get) => $get('select_all_' . $module)),
                    ]);
                })->toArray(),
            ])
            ->extraAttributes([
                'x-data' => '{ roleName: "", roleCode: "" }',
            ]);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('role_code')->label(__('role-management.table.role_code')),
                Tables\Columns\TextColumn::make('name')->label(__('role-management.table.name')),
                Tables\Columns\TextColumn::make('description')->label(__('role-management.table.description')),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label(__('role-management.actions.edit'))
                    ->icon('heroicon-o-pencil')
            ]);
//            ->bulkActions([
//                static::getAuthorizedDeleteBulkAction()(),
//            ]);
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
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
