<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminResource\Pages;
use App\Models\Admin;
use App\Models\Role;
use App\Services\Contracts\Account\IAdminService;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;

class AdminResource extends BaseResource
{
    protected static string $permissionPrefix = 'admin';
    protected static ?string $model = Admin::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('admin-management.form.name'))
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'name' => __('admin-management.validations.name_required'),
                    ]),

                TextInput::make('email')
                    ->label(__('admin-management.form.email'))
                    ->email()
                    ->required()
                    ->maxLength(255)
                    ->unique(
                        table: 'admins',
                        column: 'email',
                        ignorable: fn($record) => $record,
                    )
                    ->validationMessages([
                        'unique' => __('admin-management.validations.email_unique'),
                        'email' => __('admin-management.validations.email_invalid'),
                        'required' => __('admin-management.validations.email_required'),
                    ]),

                TextInput::make('password')
                    ->label(__('admin-management.form.password'))
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->dehydrateStateUsing(fn($state) => filled($state) ? bcrypt($state) : null)
                    ->dehydrated(fn($state) => filled($state))
                    ->maxLength(255)
                    ->minLength(6)
                    ->validationMessages([
                        'required' => __('admin-management.validations.password_required'),
                        'min' => __('admin-management.validations.password_min'),
                    ])
                    ->hidden(
                        fn(string $context) => $context !== 'create'
                    ),

                TextInput::make('password_confirmation')
                    ->label(__('admin-management.form.password_confirmation'))
                    ->password()
                    ->required(fn(string $context) => $context === 'create')
                    ->same('password')
                    ->validationMessages([
                        'same' => __('admin-management.validations.password_confirmed')
                    ])
                    ->hidden(
                        fn(string $context) => $context !== 'create'
                    )
                    ->dehydrated(false),

                FileUpload::make('avatar')
                    ->label(__('admin-management.form.avatar'))
                    ->image()
                    ->directory('avatars')
                    ->imagePreviewHeight('100')
                    ->maxSize(2048),

                Hidden::make('role')
                    ->default('admin')
                    ->dehydrateStateUsing(fn($state) => 'admin'),

                Select::make('roles')
                    ->label(__('admin-management.form.roles'))
                    ->relationship(
                        name: 'roles',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn($query) => $query->whereNotIn('name', ['super-admin'])
                    )
                    ->multiple()
                    ->searchable()
                    ->preload(),

                Toggle::make('status')
                    ->label(__('admin-management.form.status'))
                    ->default(true),

                Actions::make([
                    Actions\Action::make('sendResetPasswordEmail')
                        ->label(__('admin-management.form.send_reset_password_email'))
                        ->action(fn(Admin $record) => app(IAdminService::class)->resetPassword($record))
                        ->requiresConfirmation()
                        ->modalHeading(__('admin-management.action_confirms.reset_password.title'))
                        ->modalDescription(__('admin-management.action_confirms.reset_password.message'))
                        ->modalSubmitActionLabel(__('admin-management.action_confirms.reset_password.buttons.confirm'))
                        ->hidden(fn(?Admin $record, string $context) =>
                            !$record ||
                            $context !== 'edit' ||
                            $record->hasRole('super-admin') ||
                            $record->is(auth('admin')->user())
                        )
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('admin-management.table.avatar'))
                    ->circular()
                    ->height(40)
                    ->width(40),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('admin-management.table.name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('admin-management.table.email'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('roles')
                    ->label('Roles')
                    ->html()
                    ->formatStateUsing(function ($record) {
                        $roles = $record->roles;

                        if ($roles->isEmpty()) {
                            return "<span class='inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-600'>No roles</span>";
                        }

                        return $roles
                            ->map(fn($role) => "<span class='inline-flex items-center px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800 mr-1'>{$role->name}</span>")
                            ->implode(' ');
                    }),

                Tables\Columns\IconColumn::make('status')
                    ->label(__('admin-management.table.status'))
                    ->boolean()
                    ->trueIcon('heroicon-m-check-circle')
                    ->falseIcon('heroicon-m-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('admin-management.form.status'))
                    ->options([
                        1 => __('admin-management.table.active'),
                        0 => __('admin-management.table.inactive'),
                    ]),

                Tables\Filters\SelectFilter::make('role')
                    ->label(__('admin-management.form.role'))
                    ->options([
                        'super-admin' => 'Super Admin',
                    ])
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Admin $record) {
                        $record->delete();
                    })
                    ->hidden(function (Admin $record) {
                        return $record->hasRole('super-admin') || $record->is(auth('admin')->user());
                    })
                    ->requiresConfirmation()
                    ->modalHeading(__('admin-management.action_confirms.delete.title'))
                    ->modalDescription(__('admin-management.action_confirms.delete.message'))
                    ->modalSubmitActionLabel(__('admin-management.action_confirms.delete.buttons.confirm')),
            ])
            ->bulkActions([
                static::getAuthorizedDeleteBulkAction()
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
            'index' => Pages\ListAdmins::route('/'),
            'create' => Pages\CreateAdmin::route('/create'),
            'edit' => Pages\EditAdmin::route('/{record}/edit'),
        ];
    }
}
