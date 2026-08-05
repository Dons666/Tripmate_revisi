<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user';

    protected ?string $pendingRole = null;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'gambar',
        'is_active',
        'deactivation_reason_code',
        'deactivation_reason_detail',
        'warning_count',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Auto-sync weak entity Role record whenever User is created or updated
     */
    protected static function booted(): void
    {
        static::saved(function (User $user) {
            if ($user->pendingRole !== null && \Illuminate\Support\Facades\Schema::hasTable('roles')) {
                $user->assignRole($user->pendingRole);
                $user->pendingRole = null;
            }
        });
    }

    public function roles()
    {
        return $this->hasMany(Role::class, 'user_id', $this->getKeyName());
    }

    public function getIdAttribute()
    {
        return $this->attributes['id_user'] ?? $this->attributes['id'] ?? $this->getKey();
    }

    public function setNameAttribute($value): void
    {
        $this->attributes['username'] = $value;
    }

    public function getNameAttribute($value): string
    {
        return (string) ($this->attributes['username'] ?? $value ?? '');
    }

    public function getUsernameAttribute(): string
    {
        return (string) ($this->attributes['username'] ?? '');
    }

    public function setRoleAttribute($value): void
    {
        $targetRole = strtolower(trim((string) $value));
        $this->pendingRole = $targetRole;

        if ($this->exists && \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            $this->assignRole($targetRole);
            $this->pendingRole = null;
        }
    }

    public function getRoleAttribute(): string
    {
        if ($this->pendingRole !== null) {
            return $this->pendingRole;
        }

        if ($this->relationLoaded('roles')) {
            $first = $this->roles->first();
            return strtolower(trim((string) ($first ? $first->role : 'user')));
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
            $roleVal = $this->roles()->value('role');
            return strtolower(trim((string) ($roleVal ?? 'user')));
        }
        
        return strtolower(trim((string) ($this->attributes['role'] ?? 'user')));
    }

    public function scopeRole($query, string $role)
    {
        return $query->whereHas('roles', function ($q) use ($role) {
            $q->where('role', strtolower(trim($role)));
        });
    }

    public function assignRole(string $role): void
    {
        $targetRole = strtolower(trim($role));
        if (!empty($targetRole) && \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            $this->roles()->firstOrCreate(['role' => $targetRole]);
        }
    }

    public function removeRole(string $role): void
    {
        $targetRole = strtolower(trim($role));
        if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
            $this->roles()->where('role', $targetRole)->delete();
        }
    }

    public function hasRole(string $role): bool
    {
        $targetRole = strtolower(trim($role));

        if ($this->pendingRole !== null && $this->pendingRole === $targetRole) {
            return true;
        }

        if ($this->relationLoaded('roles')) {
            return $this->roles->contains(fn ($r) => strtolower(trim((string) $r->role)) === $targetRole);
        }

        if (\Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return $this->roles()->where('role', $targetRole)->exists();
        }

        return false;
    }

    public function preference()
    {
        return $this->hasOne(UserPreference::class, 'id_user', 'id_user');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class, 'id_user', 'id_user');
    }

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id', 'id_user');
    }

    public function searchHistories()
    {
        return $this->hasMany(SearchHistory::class, 'user_id', 'id_user');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTravel(): bool
    {
        return $this->hasRole('travel');
    }

    public function getAvatarAttribute(): string
    {
        return (string) ($this->attributes['gambar'] ?? '');
    }

    public function getIsActiveAttribute($value): bool
    {
        if (!is_null($value)) {
            return (bool) $value;
        }

        return true;
    }

    public function getWarningCountAttribute($value): int
    {
        return (int) ($value ?? 0);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'user_id', 'id_user');
    }

    public function travelPlans()
    {
        return $this->hasMany(TravelPlan::class, 'id_user', 'id_user');
    }

    public function armadas()
    {
        return $this->hasMany(Armada::class, 'user_id', 'id_user');
    }
}
