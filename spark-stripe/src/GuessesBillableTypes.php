<?php

namespace Spark;

trait GuessesBillableTypes
{
    /**
     * Get the default billable type for the application.
     */
    protected function guessBillableType(): string
    {
        if (count(config('spark.billables')) == 1) {
            return array_keys(config('spark.billables'))[0];
        }

        return 'user';
    }
}
