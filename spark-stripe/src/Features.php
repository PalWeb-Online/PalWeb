<?php

namespace Spark;

class Features
{
    /**
     * Determine if the given feature is enabled.
     */
    public static function enabled(string $feature): bool
    {
        return in_array($feature, config('spark.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     */
    public static function optionEnabled(string $feature, string $option): bool
    {
        return static::enabled($feature) &&
               config("spark-options.{$feature}.{$option}") === true;
    }

    /**
     * Get the value of the given option.
     *
     * @return mixed
     */
    public static function option(string $feature, string $option)
    {
        return config("spark-options.{$feature}.{$option}");
    }

    /**
     * Determine if the application requires users to accept the terms of service before subscribing.
     */
    public static function enforcesAcceptingTerms(): bool
    {
        return static::enabled('must-accept-terms');
    }

    /**
     * Determine if the application is using the EU VAT collection feature.
     */
    public static function collectsEuVat(): bool
    {
        if (config('spark.collects_eu_vat')) {
            return config('spark.collects_eu_vat');
        }

        return static::enabled('eu-vat-collection');
    }

    /**
     * Determine if the application is using the billing address collection feature.
     */
    public static function collectsBillingAddress(): bool
    {
        return static::enabled('billing-address-collection');
    }

    /**
     * Determine if the application is using the receipt emails sending feature.
     */
    public static function sendsReceiptEmails(): bool
    {
        return static::enabled('receipt-emails-sending');
    }

    /**
     * Determine if the application is using the payment notifications sending feature.
     */
    public static function sendsPaymentNotificationEmails(): bool
    {
        return static::enabled('sends-payment-notification-emails');
    }

    /**
     * Enable requiring accepting terms before subscribing.
     */
    public static function mustAcceptTerms(): string
    {
        return 'must-accept-terms';
    }

    /**
     * Enable the VAT collection feature.
     */
    public static function euVatCollection(array $options = []): string
    {
        config(['spark-options.eu-vat-collection' => $options]);

        return 'eu-vat-collection';
    }

    /**
     * Enable the billing address collection feature.
     */
    public static function billingAddressCollection(array $options = []): string
    {
        config(['spark-options.billing-address-collection' => $options]);

        return 'billing-address-collection';
    }

    /**
     * Enable the receipt emails sending feature.
     */
    public static function receiptEmails(array $options = []): string
    {
        config(['spark-options.receipt-emails-sending' => $options]);

        return 'receipt-emails-sending';
    }

    /**
     * Enable the receipt emails sending feature.
     */
    public static function paymentNotificationEmails(): string
    {
        return 'sends-payment-notification-emails';
    }
}
