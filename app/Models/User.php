<?php

namespace App\Models;

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

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'trial_ends_at' => 'datetime',
        ];
    }

    public function isSuperuser(): bool
    {
        return $this->hasRole('admin');
    }

    public function isAdmin(): bool
    {
        if (session()->has('view_as_role')) {
            return false;
        }

        return $this->hasRole('admin');
    }

    public function isStudent(): bool
    {
        if (session()->get('view_as_role') === 'student') {
            return true;
        }

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
            return 'pal';
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

    public function lessons(): BelongsToMany
    {
        return $this->belongsToMany(Lesson::class)
            ->withPivot(['stage', 'completed'])
            ->withTimestamps();
    }

    protected ?array $lessonProgressCache = null;
    protected ?array $scoreCountsCache = null;

    public function forgetLessonProgressCache(): void
    {
        $this->lessonProgressCache = null;
    }

    public function getLessonProgress(): array
    {
        if ($this->lessonProgressCache === null) {
            $this->lessonProgressCache = $this->lessons()
                ->get()
                ->keyBy('id')
                ->map(fn($lesson) => [
                    'stage' => (int) $lesson->pivot->stage,
                    'completed' => (bool) $lesson->pivot->completed,
                ])
                ->toArray();
        }

        return $this->lessonProgressCache;
    }

    public function getScoreCounts(): array
    {
        if ($this->scoreCountsCache === null) {
            $this->scoreCountsCache = $this->scores()
                ->where('score', 1)
                ->select('scorable_type', 'scorable_id', \DB::raw('count(*) as total'))
                ->groupBy('scorable_type', 'scorable_id')
                ->get()
                ->mapWithKeys(function ($item) {
                    return ["{$item->scorable_type}:{$item->scorable_id}" => (int) $item->total];
                })
                ->toArray();
        }

        return $this->scoreCountsCache;
    }

    public function hasUnlockedLesson(Lesson $lesson): bool
    {
        if ($this->isAdmin()) return true;

        return isset($this->getLessonProgress()[$lesson->id]);
    }

    public function getLessonStage(Lesson $lesson): int
    {
        if ($this->isAdmin()) return 3;

        return $this->getLessonProgress()[$lesson->id]['stage'] ?? 0;
    }
}
