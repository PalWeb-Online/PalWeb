<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spark\Billable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Billable, HasApiTokens, HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'name',
        'ar_name',
        'username',
        'avatar',
        'bio',
        'home',
        'private',
        'dialect_id',
        'speaker_id',
        'discord_id',
        'discord_token',
        'discord_refresh_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new UserScope);
    }


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
        ];
    }

    /**
     * Returns true if the current user is an admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Returns all roles as a CSV string
     */
    public function getRolesAsString(): string
    {
        $roles = $this->roles->pluck('name')->map(function ($role) {
            return $role;
        })->all();

        if (empty($roles)) {
            return 'hobbyist';
        }

        return implode(',', $roles);
    }

    /**
     * Add the current user to the list of administrators
     */
    public function grantAdminRole(): self
    {
        $this->assignRole('admin');

        return $this;
    }

    /**
     * Remove the current user from the list of administrators
     */
    public function revokeAdminRole(): self
    {
        $this->removeRole('admin');

        return $this;
    }

    /**
     * Add the current user to the list of students
     */
    public function grantStudentRole(): self
    {
        $this->assignRole('student');

        return $this;
    }

    /**
     * Remove the current user from the list of students
     */
    public function revokeStudentRole(): self
    {
        $this->removeRole('student');

        return $this;
    }

    public function dialect(): BelongsTo
    {
        return $this->belongsTo(Dialect::class, 'dialect_id');
    }

    public function decks(): HasMany
    {
        return $this->hasMany(Deck::class);
    }

    public function badges(): BelongsToMany
    {
        return $this->belongsToMany(Badge::class);
    }

    public function speaker(): hasOne
    {
        return $this->hasOne(Speaker::class);
    }
}
