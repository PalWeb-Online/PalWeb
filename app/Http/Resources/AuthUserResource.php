<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AuthUserResource extends UserResource
{
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'email' => $this->email,
            'roles' => $this->getEffectiveRoles(),
            'is_superuser' => $this->isSuperuser(),
            'is_verified' => (bool) $this->email_verified_at,
            'has_discord' => (bool) $this->discord_id,
            'unlocked_lessons' => $this->when(
                $request->user()?->id === $this->id,
                fn() => array_keys($this->getLessonProgress())
            ),
        ]);
    }

    protected function getEffectiveRoles(): array
    {
        $viewAsRole = session()->get('view_as_role');

        if ($viewAsRole) {
            return [$viewAsRole, 'pal'];
        }

        $roles = $this->roles->pluck('name')->toArray();
        $roles[] = 'pal';

        return array_values(array_unique($roles));
    }
}
