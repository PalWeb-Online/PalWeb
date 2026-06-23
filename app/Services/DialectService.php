<?php

namespace App\Services;

use App\Models\Dialect;
use Illuminate\Support\Collection;
use Illuminate\Container\Attributes\Singleton;

#[Singleton]
class DialectService
{
    protected ?Dialect $userDialect = null;

    protected ?Collection $dialectIds = null;

    public function getUserDialect(): Dialect
    {
        if ($this->userDialect !== null) {
            return $this->userDialect;
        }

        $this->userDialect =
            auth()->user()?->dialect
            ?? Dialect::findOrFail(8);

        return $this->userDialect;
    }

    public function dialectIds(): Collection
    {
        if ($this->dialectIds !== null) {
            return $this->dialectIds;
        }

        $dialect = $this->getUserDialect();

        $this->dialectIds = $dialect
            ->ancestors
            ->sortDesc()
            ->pluck('id')
            ->prepend($dialect->id)
            ->values();

        return $this->dialectIds;
    }
}
