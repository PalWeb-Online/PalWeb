<?php

namespace App\Http\Controllers;

use App\Events\UserDeniedAccess;
use App\Exceptions\UnauthorizedAccessException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * Throws exception if provided value is falsy. Used to check to ensure we have permission to perform an action.
     * This function allows us to circumvent Laravel's way too magical (and very slow) policy checking system. Wrap
     * calls to laravel policy methods in this and if they fail the system will automatically abort execution.
     *
     * EXAMPLE
     * We want to access the index method of a PostController and have a policy called PostPolicy. In the index
     * method of this controller write the following code
     * $this->failIfFalse( $postPolicy->viewAny(auth()->user() );
     *
     * Policy class methods return true if authorized and false if denied so by 'failing on false', we accomplish
     * exactly the same end result as the laravel auth system except this way we can see exactly how and why it
     * works.
     *
     * @param  bool  $action  if false we throw an exception
     *
     * @throws UnauthorizedAccessException
     */
    public function failIfFalse(bool $action)
    {
        if (! $action) {
            // Fire off a UserDeniedAccess event. The details will be filled in by the implementation
            event(new UserDeniedAccess);

            throw UnauthorizedAccessException::forClass(static::class);
        }
    }
}
