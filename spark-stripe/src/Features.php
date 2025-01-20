<?php

namespace Spark;

class Features
{
    /**
     * Determine if the given feature is enabled.
     *
     * @return bool
     */
    public static function enabled(string $feature)
    {
        return in_array($feature, config('spark.features', []));
    }

    /**
     * Determine if the feature is enabled and has a given option enabled.
     *
     * @return bool
     */
    public static function optionEnabled(string $feature, string $option)
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
     *
     * @return bool
     */
    public static function enforcesAcceptingTerms()
    {
        return static::enabled('must-accept-terms');
    }

    /**
     * Determine if the application is using the EU VAT collection feature.
     *
     * @return bool
     */
    public static function collectsEuVat()
    {
        if (config('spark.collects_eu_vat')) {
            return config('spark.collects_eu_vat');
        }

        return static::enabled('eu-vat-collection');
    }

    /**
     * Determine if the application is using the billing address collection feature.
     *
     * @return bool
     */
    public static function collectsBillingAddress()
    {
        return static::enabled('billing-address-collection');
    }

    /**
     * Determine if the application is using the receipt emails sending feature.
     *
     * @return bool
     */
    public static function sendsReceiptEmails()
    {
        return static::enabled('receipt-emails-sending');
    }

    /**
     * Determine if the application is using the payment notifications sending feature.
     *
     * @return bool
     */
    public static function sendsPaymentNotificationEmails()
    {
        return static::enabled('sends-payment-notification-emails');
    }

    /**
     * Enable requiring accepting terms before subscribing.
     *
     * @return string
     */
    public static function mustAcceptTerms()
    {
        return 'must-accept-terms';
    }

    /**
     * Enable the VAT collection feature.
     *
     * @return string
     */
    public static function euVatCollection(array $options = [])
    {
        config(['spark-options.eu-vat-collection' => $options]);

        return 'eu-vat-collection';
    }

    /**
     * Enable the billing address collection feature.
     *
     * @return string
     */
    public static function billingAddressCollection(array $options = [])
    {
        config(['spark-options.billing-address-collection' => $options]);

        return 'billing-address-collection';
    }

    /**
     * Enable the receipt emails sending feature.
     *
     * @return string
     */
    public static function receiptEmails(array $options = [])
    {
        config(['spark-options.receipt-emails-sending' => $options]);

        return 'receipt-emails-sending';
    }

    /**
     * Enable the receipt emails sending feature.
     *
     * @return string
     */
    public static function paymentNotificationEmails()
    {
        return 'sends-payment-notification-emails';
    }
}
