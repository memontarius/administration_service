<?php

namespace App\Models;


use App\Models\Enums\UserPermission;
use App\Models\Traits\HasTable;
use Illuminate\Contracts\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use \Illuminate\Auth\Authenticatable as HasAuthentication;
use \Illuminate\Foundation\Auth\Access\Authorizable as HasAuthorization;


class User extends Model implements Authenticatable, Authorizable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasTable;
    use HasRoles;
    use HasAuthentication;
    use HasAuthorization;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'login',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function bans(): HasMany
    {
        return $this->hasMany(Ban::class);
    }

    public function isAdmin(): bool
    {
        return $this->hasPermissionTo(UserPermission::USER_MANAGEMENT->value);
    }

    public function isSelf(User $other): bool
    {
        return $this->id === $other->id;
    }

    public function isBanned(): bool
    {
        return $this->bans()->exists();
    }
}
