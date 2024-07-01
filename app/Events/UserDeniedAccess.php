<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

/**
 * An event that we fire off when a user is denied access to a controller.
 * User data will magically be added before serializing to the database
 */
class UserDeniedAccess extends ShouldBeStored
{
}
