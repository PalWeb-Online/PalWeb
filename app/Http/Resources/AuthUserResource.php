<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AuthUserResource extends UserResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        $roles = $this->roles->pluck('name')->toArray();
        $roles[] = 'pal';

        $data['roles'] = $roles;
        $data['email'] = $this->email;
        $data['is_verified'] = (bool) $this->email_verified_at;
        $data['has_discord'] = (bool) $this->discord_id;

        return $data;
    }
}
