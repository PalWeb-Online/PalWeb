<?php

use Illuminate\Support\Facades\Route;
use Spark\Http\Middleware\HandleInertiaRequests;

Route::group([
    'namespace' => 'Spark\Http\Controllers',
    'prefix' => 'spark',
], function () {
    // Stripe Webhook Controller...
    Route::post('webhook', 'WebhookController@handleWebhook');

    Route::group(['middleware' => config('spark.middleware', ['web', 'auth'])], function () {
        // Subscription...
        Route::post('/subscription', 'NewSubscriptionController');
        Route::put('/subscription', 'UpdateSubscriptionController');
        Route::put('/subscription/cancel', 'CancelSubscriptionController');
        Route::put('/subscription/resume', 'ResumeSubscriptionController');

        // Payment Methods...
        Route::post('/subscription/payment-method', 'PaymentMethodsController@setup');
        Route::put('/subscription/payment-method/default', 'PaymentMethodsController@default');
        Route::delete('/subscription/payment-method', 'PaymentMethodsController@delete');

        // Payment Information...
        Route::put('/subscription/payment-information', 'UpdatePaymentInformationController');

        // Invoice Controller...
        Route::post('/{invoiceId}/pay', 'PayInvoiceController');

        // Billing Information...
        Route::put('/billing-information', 'UpdateBillingInformationController');

        // Receipt Emails...
        Route::put('/receipt-emails', 'UpdateReceiptEmailsController');

        // Apply a Coupon...
        Route::put('/coupon', 'ApplyCouponController');

        // Vat Rate Controller...
        Route::post('/tax-rate', 'TaxController');

        // Billing Information...
        Route::get('/{type}/{id}/receipts/{receiptId}/download', 'DownloadReceiptController')->name('spark.receipts.download');
    });
});

Route::group([
    'middleware' => array_merge(config('spark.middleware', ['web', 'auth']), [HandleInertiaRequests::class]),
    'namespace' => 'Spark\Http\Controllers',
    'prefix' => config('spark.path'),
], function () {
    Route::get('/{type?}/{id?}', 'BillingPortalController')->name('spark.portal');
});
