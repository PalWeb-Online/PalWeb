<?php

namespace App\Traits;

use App\Exceptions\UnauthorizedAccessException;
use Closure;

trait RedirectsToSubscribe
{
    /**
     * Redirects to subscription page when an UnauthorizedAccessException is thrown
     *
     * @return void
     */
    protected function redirectToSubscribeOnFail(Closure $callback): mixed
    {
        $result = null;

        try {
            $result = $callback();
        } catch (UnauthorizedAccessException $e) {
            $result = to_route('dashboard.subscription');
        }

        return $result;
    }
}
