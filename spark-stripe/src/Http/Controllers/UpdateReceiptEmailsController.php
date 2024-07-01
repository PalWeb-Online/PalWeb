<?php

namespace Spark\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UpdateReceiptEmailsController
{
    use RetrievesBillableModels;

    /**
     * Update the receipt emails for the given billable.
     *
     * @param  \Illuminate\Http\Request
     * @return void
     */
    public function __invoke(Request $request)
    {
        $emails = array_map(function ($email) {
            return trim($email);
        }, explode(',', $request->receipt_emails));

        if (validator($emails, ['*' => 'email'])->fails()) {
            throw ValidationException::withMessages([
                'emails' => __('The receipt emails must be valid email addresses.'),
            ]);
        }

        if (count($emails) > 3) {
            throw ValidationException::withMessages([
                'emails' => __('Please provide a maximum of three receipt emails addresses.'),
            ]);
        }

        $billable = $this->billable();

        $emails = $billable->hasCast('receipt_emails') ? $emails : json_encode($emails);

        $billable->forceFill([
            'receipt_emails' => $emails,
        ])->save();

        session(['spark.flash.success' => __('Receipt emails updated successfully.')]);
    }
}
