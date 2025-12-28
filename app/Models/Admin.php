<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable implements LaratrustUser, FilamentUser, HasAvatar
{
    use HasFactory, Notifiable, HasRolesAndPermissions, SoftDeletes;

    public const ROLE_ADMIN = 'admin';
    public const STATUS_PUBLIC = 1;
    public const STATUS_PRIVATE = 0;

    /** @use HasFactory<\Database\Factories\UserFactory> *=-0
    use HasFactory, Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status',
        'role',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @throws \Exception an exception is thrown if the panel is not found
     */
    public function canAccessPanel(Panel $panel): bool
    {
      return $panel->getId() === 'admin';
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return !empty($this->avatar) ? Storage::url($this->avatar) : null;
    }
}
