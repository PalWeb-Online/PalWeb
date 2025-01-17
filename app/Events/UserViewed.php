<?php

namespace App\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

/**
 * An event that we fire off when a user accesses a web page
 * User data will magically be added before serializing to the database
 */
class UserViewed extends ShouldBeStored {}
