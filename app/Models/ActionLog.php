<?php

namespace App\Models;

use Domain\Legacy\Helpers\Log;
use Facades\Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Spatie\EventSourcing\StoredEvents\Models\EloquentStoredEvent;

/**
 * A class that can be used to log user actions as events. The events will then be stored in the stored_events table
 * to be replayed/projected in any way you wish.
 *
 * To use this class you must set it as the Stored Event model in the event_sourcing.php config file
 * Example
 * 'stored_event_model' => App\Models\ActionLog::class,
 */
class ActionLog extends EloquentStoredEvent
{
    public static function boot()
    {
        parent::boot();

        static::creating(function (ActionLog $storedEvent) {
            /**
             * In addition to the event itself, we also store the user who performed the action, the IP address of that
             * user, and the url/controller method that was accessed
             */
            $meta = $storedEvent->getAllMetadata();

            foreach ($meta as $key => $val) {
                $storedEvent->meta_data[$key] = $val;
            }
        });
    }

    public function getAllMetadata()
    {
        $route = $this->getCurrentRoute();

        return [
            'ip' => $this->getIp(),
            'user_id' => $this->getCurrentUserID(),
            'url' => $route['url'],
            'controller' => $route['controller'],
            'route_name' => $route['name'],
        ];
    }

    /**
     * Returns IP address for the current user
     */
    public function getIp(): ?string
    {
        return Request::ip();
    }

    /**
     * Returns the ID of the current user, or 1 (aka SSR BOT) if a user ID cant be located
     */
    public function getCurrentUserID(): int
    {
        $userID = 1;
        $user = Auth::user();

        if ($user) {
            $userID = $user->id;
        }

        return $userID;
    }

    /**
     * Returns the current time in unix format
     *
     * @return mixed
     */
    public function getTimestamp()
    {
        return Date::now()->getPreciseTimestamp(3);
    }

    /**
     * Returns information about the current page/route
     */
    public function getCurrentRoute(): array
    {
        $controller = null;
        $name = null;
        $url = UrlGenerator::current();

        $route = Route::current();
        if ($route) {
            $routeController = $route->controller;
            if ($routeController) {
                if (is_object($routeController) && ! ($routeController instanceof \Closure)) {
                    $controller = get_class($routeController);
                }
            }
            $name = $route->getName();
        }

        return [
            'url' => $url,
            'controller' => $controller,
            'name' => $name,
        ];
    }
}
