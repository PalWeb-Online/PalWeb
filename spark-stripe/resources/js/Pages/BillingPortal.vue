<template>
    <div class="font-sans antialiased min-h-screen bg-white lg:bg-gray-100">
        <div class="lg:min-h-screen bg flex flex-wrap lg:flex-nowrap">
            <!-- Static Desktop Sidebar -->
            <div class="order-last lg:order-first lg:max-w-md py-10 lg:pt-24 px-6 bg-white lg:shadow-lg" id="sideBar">
                <div class="max-w-md" v-if="$page.props.appLogo" v-html="$page.props.appLogo">
                </div>

                <h1 class="text-3xl font-bold text-gray-900" v-else>
                    {{ $page.props.appName }}
                </h1>

                <h2 class="mt-1 text-lg font-semibold text-gray-700">
                    {{ __('Billing Management') }}
                </h2>

                <div class="flex items-center mt-12 text-gray-600">
                    <div>
                        {{ __('Signed in as') }}
                    </div>

                    <img :src="$page.props.userAvatar" class="ml-2 h-6 w-6 rounded-full" v-if="$page.props.userAvatar"/>

                    <div :class="{'ml-1': ! $page.props.userAvatar, 'ml-2': $page.props.userAvatar}">
                        {{ $page.props.userName }}.
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-600" v-if="$page.props.billableName">
                    {{ __('Managing billing for :billableName', {billableName: $page.props.billableName}) }}.
                </div>

                <div class="mt-12 text-gray-600">
                    {{ __('Our billing management portal allows you to conveniently manage your subscription plan, payment methods, and download your recent invoices.') }}
                </div>

                <div class="mt-12" id="sideBarTermsLink" v-if="$page.props.termsUrl">
                    <a :href="$page.props.termsUrl" class="text-gray-600 underline" target="_blank">
                        {{ __('Terms of Service') }}
                    </a>
                </div>

                <div class="mt-12" id="sideBarReturnLink">
                    <a :href="$page.props.dashboardUrl" class="flex items-center">
                        <svg viewBox="0 0 20 20" fill="currentColor" class="arrow-left w-5 h-5 text-gray-400">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                        </svg>

                        <div class="ml-2 text-gray-600 underline">
                            {{ __('Return to :appName', {appName: $page.props.appName}) }}
                        </div>
                    </a>
                </div>
            </div>

            <div class="w-full lg:flex-1 bg-gray-100">
                <!-- Mobile Top Nav -->
                <a :href="$page.props.dashboardUrl" class="lg:hidden flex items-center w-full px-4 py-4 bg-white shadow-lg" id="topNavReturnLink">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="arrow-left w-4 h-4 text-gray-400">
                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                    </svg>

                    <div class="ml-2 text-gray-600 underline">
                        {{ __('Return to :appName', {appName: $page.props.appName}) }}
                    </div>
                </a>

                <!-- Main Content -->
                <div class="sm:px-8 pb-10 pt-6 lg:pt-24 lg:pb-24 lg:max-w-4xl lg:mx-auto">
                    <!-- Custom Message -->
                    <div class="mb-10" v-if="$page.props.message">
                        <div class="px-6 py-4 text-sm text-gray-600 bg-blue-100 border border-blue-200 sm:rounded-lg shadow-sm mb-6">
                            {{ $page.props.message }}
                        </div>
                    </div>

                    <!-- Success Message -->
                    <success-message class="mb-10" v-if="$page.props.spark.flash.success">
                        {{ $page.props.spark.flash.success }}
                    </success-message>

                    <!-- Error Messages -->
                    <error-messages class="mb-10" :errors="errors" v-if="errors.length > 0"/>

                    <!-- Subscription Form (Shown After A Plan Is Selected) -->
                    <div v-show="subscribing">
                        <section-heading>
                            {{ __('Subscribe') }}
                        </section-heading>

                        <div class="mt-6">
                            <div class="bg-white sm:rounded-lg shadow-sm overflow-hidden" v-if="subscribing">
                                <plan :plan="subscribing" :collection-method="collectionMethod" :seat-name="seatName"/>
                            </div>

                            <div class="mt-6 bg-white sm:rounded-lg shadow-sm overflow-hidden">
                                <div class="p-6">
                                    <h2 class="text-xl font-semibold text-gray-700">
                                        {{ __('Subscription Information') }}
                                    </h2>

                                    <div v-if="collectsVat || collectsBillingAddress">
                                        <div class="mt-6 md:flex items-center">
                                            <span class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                {{ __('Country') }}
                                                <span v-if="collectsBillingAddress" class="text-gray-500">(*)</span>
                                            </span>

                                            <select name="country" id="country"
                                                    v-model="subscriptionForm.country"
                                                    class="form-select w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none">
                                                <option value="" disabled="">{{ __('Select') }}</option>
                                                <option v-for="(name, iso) in $page.props.countries" :value="iso">{{ name }}</option>
                                            </select>
                                        </div>

                                        <div class="mt-1 md:flex items-center" v-if="! addingVatNumber && vatComplicit">
                                            <span class="hidden md:block w-1/3 mr-10">&nbsp;</span>

                                            <button class="w-full mt-1 md:mt-0 text-gray-400 text-sm underline text-left active:outline-none focus:outline-none" @click="showVatNumber">
                                                {{ __('Add VAT Number') }}
                                            </button>
                                        </div>

                                        <div v-if="(addingVatNumber && vatComplicit) || collectsBillingAddress">
                                            <div v-if="addingVatNumber"
                                                 class="mt-6 md:flex items-center">
                                                <label for="vat" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">{{ __('VAT Number') }}</label>

                                                <input type="text" id="vat" ref="vat"
                                                       v-model="subscriptionForm.vat"
                                                       :placeholder="__('VAT Number')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>

                                            <div class="mt-6 md:flex items-center">
                                                <label for="address" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                    {{ __('Address') }}
                                                    <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                </label>

                                                <input type="text" id="address" ref="address"
                                                       v-model="subscriptionForm.address"
                                                       :placeholder="__('Address')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>

                                            <div class="mt-6 md:flex items-center">
                                                <label for="address2" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">{{ __('Address Line 2') }}</label>

                                                <input type="text" id="address2" ref="address2"
                                                       v-model="subscriptionForm.address2"
                                                       :placeholder="__('Address Line 2')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>

                                            <div class="mt-6 md:flex items-center">
                                                <label for="city" class="w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                    {{ __('City') }}
                                                    <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                </label>

                                                <input type="text" id="city" ref="city"
                                                       v-model="subscriptionForm.city"
                                                       :placeholder="__('City')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>

                                            <div class="mt-6 md:flex items-center">
                                                <label for="state" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                    {{ __('State / County') }}
                                                    <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                </label>

                                                <input type="text" id="state" ref="state"
                                                       v-model="subscriptionForm.state"
                                                       :placeholder="__('State / County')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>

                                            <div class="mt-6 md:flex items-center">
                                                <label for="postal_code" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                    {{ __('Zip / Postal Code') }}
                                                    <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                </label>

                                                <input type="text" id="postal_code" ref="postal_code"
                                                       v-model="subscriptionForm.postal_code"
                                                       :placeholder="__('Zip / Postal Code')"
                                                       class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 md:flex">
                                        <label for="extra" class="md:w-1/3 mr-10 mt-2 text-gray-800 text-sm font-semibold">{{ __('Extra Billing Information') }}</label>

                                        <textarea id="extra" rows="5"
                                                  v-model="subscriptionForm.extra"
                                                  :placeholder="__('If you need to add specific contact or tax information to your receipts, like your full business name, VAT identification number, or address of record, you may add it here.')"
                                                  class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded focus:outline-none"></textarea>
                                    </div>

                                    <div class="mt-1 md:flex items-center" v-if="! showingCouponCode">
                                        <span class="hidden md:block w-1/3 mr-10">&nbsp;</span>

                                        <button class="w-full text-gray-400 text-sm underline text-left active:outline-none focus:outline-none" @click="showCouponCode">
                                            {{ __('Have a coupon code?') }}
                                        </button>
                                    </div>

                                    <div class="mt-6 md:flex items-center" v-if="showingCouponCode">
                                        <label for="coupon" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">{{ __('Coupon') }}</label>

                                        <input type="text" id="coupon" ref="coupon"
                                               v-model="subscriptionForm.coupon"
                                               :placeholder="__('Coupon')"
                                               @keyup.enter="confirmSubscription"
                                               class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                    </div>

                                    <div v-if="enforcesAcceptingTerms" class="mt-6 md:flex">
                                        <label for="extra" class="hidden md:block w-1/3 mr-10 text-gray-800 text-sm font-semibold"></label>

                                        <div class="flex items-center w-full">
                                            <input type="checkbox" name="accept" v-model="subscriptionForm.accept">

                                            <a class="ml-2 text-sm font-semibold underline" :href="$page.props.termsUrl">
                                                {{ __('I accept the terms of service') }}.
                                            </a>
                                        </div>
                                    </div>

                                    <p v-if="collectsBillingAddress" class="text-gray-500 text-sm mt-4">
                                        (*) {{ __('Required fields') }}
                                    </p>
                                </div>

                                <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 flex items-center">
                                    <div v-if="! loadingTaxCalculations">
                                        <span v-if="checkoutAmount" v-html="__('Total:') + ' ' + checkoutAmount"></span>
                                        <span v-if="checkoutTax" v-html="'(' + checkoutTax + ' ' + __('TAX') + ')'" class="ml-1 text-gray-600"></span>
                                    </div>

                                    <div v-else>
                                        ...
                                    </div>

                                    <spark-button class="ml-auto" @click.native="confirmSubscription" ref="confirmSubscriptionButton">
                                        {{ __('Checkout') }}
                                    </spark-button>
                                </div>
                            </div>
                        </div>

                        <!-- Nevermind -->
                        <button class="flex items-center mt-4" @click="subscribing = false">
                            <svg viewBox="0 0 20 20" fill="currentColor" class="text-gray-400 w-4 h-4">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>

                            <div class="ml-2 text-sm text-gray-600 underline">
                                {{ __('Select a different plan') }}
                            </div>
                        </button>
                    </div>

                    <div v-show="! subscribing">
                        <!-- Subscribe -->
                        <div v-if="$page.props.state == 'none'">
                            <section-heading>
                                {{ __('Subscribe') }}
                            </section-heading>

                            <div class="mt-6">
                                <info-messages v-if="! $page.props.genericTrialEndsAt">
                                    {{ __('It looks like you do not have an active subscription. You may choose one of the subscription plans below to get started. Subscription plans may be changed or cancelled at your convenience.') }}
                                </info-messages>

                                <info-messages v-else>
                                    {{ __('You are currently within your free trial period. Your trial will expire on :date.', {'date': $page.props.genericTrialEndsAt}) }}
                                </info-messages>
                            </div>

                            <!-- Interval Selector -->
                            <interval-selector class="mt-6"
                                               :showing-default-interval-plans="showingPlansOfInterval == $page.props.defaultInterval"
                                               @toggled="toggleDisplayedPlanIntervals"
                                               v-if="monthlyPlans.length > 0 && yearlyPlans.length > 0"/>

                            <transition name="component-fade" mode="out-in">
                                <!-- Monthly Plans -->
                                <plan-list class="mt-6" key="subscribe-monthly-plans"
                                           :plans="monthlyPlans"
                                           interval="monthly"
                                           :seat-name="seatName"
                                           :current-plan="plan"
                                           @plan-selected="startSubscribingToPlan($event)"
                                           v-if="monthlyPlans.length > 0 && showingPlansOfInterval == 'monthly'"/>

                                <!-- Yearly Plans -->
                                <plan-list class="mt-6" key="subscribe-yearly-plans"
                                           :plans="yearlyPlans"
                                           interval="yearly"
                                           :seat-name="seatName"
                                           :current-plan="plan"
                                           @plan-selected="startSubscribingToPlan($event)"
                                           v-if="yearlyPlans.length > 0 && showingPlansOfInterval == 'yearly'"/>
                            </transition>
                        </div>

                        <!-- Active Subscription -->
                        <div v-if="$page.props.state == 'active'">
                            <!-- Change Subscription Plan -->
                            <section-heading v-if="! selectingNewPlan">
                                {{ __('Current Subscription Plan') }}
                            </section-heading>

                            <section-heading v-else>
                                {{ __('Change Subscription Plan') }}
                            </section-heading>

                            <div class="mt-3">
                                <info-messages class="mb-10" v-if="$page.props.trialEndsAt">
                                    {{ __('You are currently within your free trial period. Your trial will expire on :date.', {'date': $page.props.trialEndsAt}) }}
                                </info-messages>

                                <div class="bg-white sm:rounded-lg shadow-sm" v-if="! selectingNewPlan">
                                    <plan :plan="plan" :collection-method="collectionMethod" :seat-name="seatName" :hide-incentive="true"/>

                                    <div class="px-6 pb-4">
                                        <spark-button v-if="(monthlyPlans.length + yearlyPlans.length) > 1"
                                                    @click.native="selectingNewPlan = true">
                                            {{ __('Change Subscription Plan') }}
                                        </spark-button>
                                    </div>
                                </div>
                            </div>

                            <div v-if="selectingNewPlan">
                                <!-- Interval Selector -->
                                <interval-selector class="mt-6"
                                                   :showing-default-interval-plans="showingPlansOfInterval == $page.props.defaultInterval"
                                                   @toggled="toggleDisplayedPlanIntervals"
                                                   v-if="monthlyPlans.length > 0 && yearlyPlans.length > 0"/>

                                <transition name="component-fade" mode="out-in">
                                    <!-- Monthly Plans -->
                                    <plan-list class="mt-6" key="change-monthly-plans"
                                               :plans="monthlyPlans"
                                               interval="monthly"
                                               :seat-name="seatName"
                                               :current-plan="plan"
                                               @plan-selected="open(switchToPlan, __('Are you sure you would like to switch billing plans?'), [$event])"
                                               v-if="monthlyPlans.length > 0 && showingPlansOfInterval == 'monthly'"/>

                                    <!-- Yearly Plans -->
                                    <plan-list class="mt-6" key="change-yearly-plans"
                                               :plans="yearlyPlans"
                                               interval="yearly"
                                               :current-plan="plan"
                                               :seat-name="seatName"
                                               @plan-selected="open(switchToPlan, __('Are you sure you would like to switch billing plans?'), [$event])"
                                               v-if="yearlyPlans.length > 0 && showingPlansOfInterval == 'yearly'"/>
                                </transition>

                                <!-- Nevermind, Keep Old Plan -->
                                <div class="px-6 sm:px-0">
                                    <button class="flex items-center mt-4" @click="selectingNewPlan = false">
                                        <svg viewBox="0 0 20 20" fill="currentColor" class="text-gray-400 w-4 h-4">
                                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                        </svg>

                                        <div class="ml-2 text-sm text-gray-600 underline">
                                            {{ __('Nevermind, I\'ll keep my old plan') }}
                                        </div>
                                    </button>
                                </div>
                            </div>

                            <!-- Payment Methods -->
                            <div v-if="!selectingNewPlan">
                                <section-heading class="mt-10">
                                    {{ __('Payment Methods') }}
                                </section-heading>

                                <div class="mt-3">
                                    <div class="bg-white sm:rounded-lg shadow-sm overflow-hidden">
                                        <div class="p-6">
                                            <ul v-if="$page.props.paymentMethods.length > 0" role="list" class="border border-gray-100 rounded-md divide-y divide-gray-200">
                                                <li v-for="(pm) in $page.props.paymentMethods" v-bind:key="pm.id"
                                                    class="pl-3 pr-4 py-3 flex items-center justify-between text-sm">
                                                    <div class="w-0 flex-1 flex items-center">
                                                        <svg class="flex-shrink-0 h-5 w-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                                                        </svg>

                                                        <div class="ml-3 flex-1 w-0 truncate">
                                                            {{ pm.brand }}
                                                            &bull;&bull;&bull;&bull; {{ pm.last4 }}

                                                            <span v-if="pm.default" class="inline-flex items-center ml-2 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                                {{ __('Default') }}
                                                            </span>

                                                            <button v-else @click="open(defaultPaymentMethod, __('Are you sure you want to set this payment method as your default?'), [pm.id])"
                                                                class="ml-2 font-medium text-blue-800 hover:text-blue-700">
                                                                {{ __('Set as default') }}
                                                            </button>

                                                            <br><span class="text-xs text-gray-500">
                                                                {{ __('Expires :expiration', { expiration: pm.expiration }) }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="ml-4 flex-shrink-0">
                                                        <button @click="open(deletePaymentMethod, __('Are you sure you want to delete this payment method?'), [pm.id])"
                                                            class="font-medium text-gray-700 hover:text-gray-500">
                                                            <svg class="flex-shrink-0 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </li>
                                            </ul>

                                            <p v-else class="max-w-2xl text-sm text-gray-600">
                                                {{ __('No payment methods on file.') }}
                                            </p>

                                            <spark-button class="mt-4" @click.native="addPaymentMethod" ref="addPaymentMethod">
                                                {{ __('Add Payment Method') }}
                                            </spark-button>

                                            <info-messages class="mt-4" v-if="hasUnpaidInvoices()">
                                                {{ __('You have some unpaid invoices. After updating your default payment method, you may retry the payments via the invoice list below.') }}
                                            </info-messages>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Update Payment Information -->
                            <div v-if="!selectingNewPlan && (collectsVat || collectsBillingAddress)">
                                <section-heading class="mt-10">
                                    {{ __('Payment Information') }}
                                </section-heading>

                                <div class="mt-3">
                                    <div class="bg-white sm:rounded-lg shadow-sm overflow-hidden">
                                        <div class="p-6">
                                            <div v-if="!updatingPaymentInformation">
                                                <p class="max-w-2xl text-sm text-gray-600" v-if="$page.props.billable.billing_address || $page.props.billable.billing_address2 || $page.props.billable.billing_city || $page.props.billable.billing_state || $page.props.billable.billing_postal_code || $page.props.billable.billing_country"
                                                    v-html="__('Your billing address is :address :address2 :postal_code :city :state :country', {
                                                        address: $page.props.billable.billing_address ? '<br>'+$page.props.billable.billing_address : '',
                                                        address2: $page.props.billable.billing_address_line_2 ? '<br>'+$page.props.billable.billing_address_line_2 : '',
                                                        postal_code: $page.props.billable.billing_postal_code ? '<br>'+$page.props.billable.billing_postal_code : '',
                                                        city: $page.props.billable.billing_city ? $page.props.billable.billing_city : '',
                                                        state: $page.props.billable.billing_state ? '<br>'+$page.props.billable.billing_state : '',
                                                        country: $page.props.billable.billing_country ? '<br>'+$page.props.countries[$page.props.billable.billing_country] : '',
                                                    })">
                                                </p>

                                                <p class="max-w-2xl text-sm text-gray-600 mt-3" v-if="$page.props.billable.vat_id"
                                                    v-html="__('Your registered VAT Number is :vatNumber.', {
                                                        vatNumber: '<span class=\'font-semibold\'>'+$page.props.billable.vat_id+'</span>',
                                                    })">
                                                </p>

                                                <spark-button class="mt-4" @click.native="updatingPaymentInformation = true">
                                                    {{ __('Update Payment Information') }}
                                                </spark-button>
                                            </div>

                                            <div v-if="updatingPaymentInformation">
                                                <div class="md:flex items-center">
                                                    <label for="address" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                        {{ __('Address') }}
                                                        <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                    </label>

                                                    <input type="text" id="address" ref="address"
                                                           v-model="paymentInformationForm.address"
                                                           :placeholder="__('Address')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>

                                                <div class="mt-6 md:flex items-center">
                                                    <label for="address2" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">{{ __('Address Line 2') }}</label>

                                                    <input type="text" id="address2" ref="address2"
                                                           v-model="paymentInformationForm.address2"
                                                           :placeholder="__('Address Line 2')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>

                                                <div class="mt-6 md:flex items-center">
                                                    <label for="city" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                        {{ __('City') }}
                                                        <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                    </label>

                                                    <input type="text" id="city" ref="city"
                                                           v-model="paymentInformationForm.city"
                                                           :placeholder="__('City')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>

                                                <div class="mt-6 md:flex items-center">
                                                    <label for="state" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                        {{ __('State / County') }}
                                                        <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                    </label>

                                                    <input type="text" id="state" ref="state"
                                                           v-model="paymentInformationForm.state"
                                                           :placeholder="__('State / County')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>

                                                <div class="mt-6 md:flex items-center">
                                                    <label for="postal_code" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                        {{ __('Zip / Postal Code') }}
                                                        <span v-if="collectsBillingAddress && billingAddressRequired" class="text-gray-500">(*)</span>
                                                    </label>

                                                    <input type="text" id="postal_code" ref="postal_code"
                                                           v-model="paymentInformationForm.postal_code"
                                                           :placeholder="__('Zip / Postal Code')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>

                                                <div class="mt-6 md:flex items-center">
                                                    <span class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">
                                                        {{ __('Country') }}
                                                        <span v-if="collectsBillingAddress" class="text-gray-500">(*)</span>
                                                    </span>

                                                    <select name="country" id="country"
                                                            v-model="paymentInformationForm.country"
                                                            class="form-select w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none">
                                                        <option value="" disabled="">{{ __('Select') }}</option>
                                                        <option v-for="(name, iso) in $page.props.countries" :value="iso">{{ name }}</option>
                                                    </select>
                                                </div>

                                                <div v-if="collectsVat"
                                                     class="mt-6 md:flex items-center">
                                                    <label for="vat" class="md:w-1/3 mr-10 text-gray-800 text-sm font-semibold">{{ __('VAT Number') }}</label>

                                                    <input type="text" id="vat" ref="vat"
                                                           v-model="paymentInformationForm.vat"
                                                           :placeholder="__('VAT Number')"
                                                           class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 text-right" v-if="updatingPaymentInformation">
                                            <spark-secondary-button @click.native="updatingPaymentInformation = false">
                                                {{ __('Cancel') }}
                                            </spark-secondary-button>

                                            <spark-button @click.native="updatePaymentInformation">
                                                {{ __('Update Payment Information') }}
                                            </spark-button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Apply Coupons -->
                            <section-heading class="mt-10">
                                {{ __('Apply Coupon') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="bg-white sm:rounded-lg shadow-sm">
                                    <div class="p-6">
                                        <div class="md:flex">
                                            <label for="coupon_for_existing" class="md:w-1/3 mr-10 mt-2 text-gray-800 text-sm font-semibold">{{ __('Coupon') }}</label>

                                            <input type="text" id="coupon_for_existing" ref="coupon_for_existing"
                                                   v-model="couponForm.coupon"
                                                   :placeholder="__('Coupon')"
                                                   class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 text-right">
                                        <spark-button @click.native="applyCoupon" disabled="true" ref="applyCouponButton">
                                            {{ __('Apply') }}
                                        </spark-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- On Grace Period / Subscription Paused -->
                        <div v-if="$page.props.state == 'onGracePeriod'">
                            <!-- Resume Subscription -->
                            <section-heading>
                                {{ __('Resume Subscription') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="px-6 py-4 bg-white sm:rounded-lg shadow-sm">
                                    <div class="max-w-2xl text-sm text-gray-600">
                                        {{ __('Having second thoughts about cancelling your subscription? You can instantly reactive your subscription at any time until the end of your current billing cycle. After your current billing cycle ends, you may choose an entirely new subscription plan.') }}
                                    </div>

                                    <div class="mt-4">
                                        <spark-button @click.native="open(resumeSubscription, __('Are you sure you want to resume your subscription?'))">
                                            {{ __('Resume Subscription') }}
                                        </spark-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Extra Billing Information -->
                        <div v-if="$page.props.state != 'none' && !selectingNewPlan">
                            <section-heading class="mt-10">
                                {{ __('Billing Information') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="bg-white sm:rounded-lg shadow-sm">
                                    <div class="p-6">
                                        <div class="max-w-2xl text-sm text-gray-600">
                                            {{ __('If you need to add specific contact or tax information to your receipts, like your full business name, VAT identification number, or address of record, you may add it here.') }}
                                        </div>

                                        <div class="mt-6 md:flex">
                                            <label for="extra" class="md:w-1/3 mr-10 mt-2 text-gray-800 text-sm font-semibold">{{ __('Extra Billing Information') }}</label>

                                            <textarea id="extra" rows="3"
                                                      v-model="billingInformationForm.extra"
                                                      class="w-full mt-1 md:mt-0 border border-gray-200 px-3 py-2 rounded focus:outline-none"></textarea>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 text-right">
                                        <spark-button @click.native="updateBillingInformation">
                                            {{ __('Update') }}
                                        </spark-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Receipts Emails -->
                        <div v-if="$page.props.sendsReceiptsToCustomAddresses">
                            <section-heading class="mt-10">
                                {{ __('Receipt Email Addresses') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="bg-white sm:rounded-lg shadow-sm">
                                    <div class="p-6">
                                        <div class="max-w-2xl text-sm text-gray-600">
                                            {{ __('We will send a receipt download link to the email addresses that you specify below. You may separate multiple email addresses using commas.') }}
                                        </div>

                                        <div class="mt-6 md:flex">
                                            <label for="receipt_emails" class="md:w-1/3 mr-10 mt-2 text-gray-800 text-sm font-semibold">{{ __('Email Addresses') }}</label>

                                            <input type="text" id="receipt_emails" ref="city"
                                                   v-model="receiptEmailsForm.receipt_emails"
                                                   :placeholder="__('Email Addresses')"
                                                   class="w-full mt-1 md:mt-0 bg-white border border-gray-200 px-3 py-2 rounded outline-none"/>
                                        </div>
                                    </div>

                                    <div class="px-6 py-4 bg-gray-100 bg-opacity-50 border-t border-gray-100 text-right">
                                        <spark-button @click.native="updateReceiptEmails" disabled="true" ref="updateReceiptEmailsButton">
                                            {{ __('Save') }}
                                        </spark-button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payments -->
                        <div v-if="rawBalance != 0 || $page.props.state != 'none'">
                            <section-heading class="mt-10">
                                {{ __('Payments') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                    <div class="relative bg-white shadow sm:rounded-lg overflow-hidden" v-if="rawBalance != 0">
                                        <div class="p-6">
                                            <p class="text-sm font-medium text-gray-500 truncate">
                                                {{ __('Customer Balance') }}
                                            </p>

                                            <p class="text-2xl font-semibold text-gray-900">
                                                {{ formattedBalance }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="relative bg-white shadow sm:rounded-lg overflow-hidden" v-if="$page.props.state != 'none'">
                                        <div class="p-6">
                                            <p class="text-sm font-medium text-gray-500 truncate">
                                                <span v-if="$page.props.nextPayment">
                                                    {{ __('Next Payment on') }} {{ $page.props.nextPayment.date }}
                                                </span>
                                                <span v-else>
                                                    {{ __('Next Payment') }}
                                                </span>
                                            </p>

                                            <div v-if="$page.props.nextPayment">
                                                <p class="mt-2 text-2xl font-semibold text-gray-900">
                                                    {{ $page.props.nextPayment.amount }}
                                                </p>
                                            </div>

                                            <div v-else>
                                                <p class="mt-2">
                                                    {{ __('No payment scheduled.') }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="$page.props.state == 'active'">
                            <!-- Cancel Subscription -->
                            <section-heading class="mt-10">
                                {{ __('Cancel Subscription') }}
                            </section-heading>

                            <div class="mt-3">
                                <div class="p-6 bg-white sm:rounded-lg shadow-sm">
                                    <div class="max-w-2xl text-sm text-gray-600">
                                        {{ __('You may cancel your subscription at any time. Once your subscription has been cancelled, you will have the option to resume the subscription until the end of your current billing cycle.') }}
                                    </div>

                                    <spark-secondary-button class="mt-4" @click.native="open(cancelSubscription, __('Are you sure you want to cancel your subscription?'))">
                                        {{ __('Cancel Subscription') }}
                                    </spark-secondary-button>
                                </div>
                            </div>
                        </div>

                        <!-- Open Invoices -->
                        <div v-if="openInvoices.length > 0">
                            <section-heading class="mt-10">
                                {{ __('Open Invoices') }}
                            </section-heading>

                            <receipt-list
                                @payment-retried="open(retryPayment, __('Are you sure you want to attempt to pay :amount?', { amount: $event.amount }), [$event])"
                                class="mt-3"
                                :receipts="openInvoices"
                            />
                        </div>

                        <!-- Receipts -->
                        <div v-if="receipts && receipts.data.length > 0">
                            <section-heading class="mt-10">
                                {{ __('Receipts') }}
                            </section-heading>

                            <receipt-list class="mt-3" :receipts="receipts" reload-key="receipts" />
                        </div>

                        <div class="text-center mt-10 lg:hidden" id="footerTermsLink" v-if="$page.props.termsUrl">
                            <a :href="$page.props.termsUrl" class="text-gray-600 underline">
                                {{ __('Terms of Service') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="overlay" v-if="processing">
            <div class="lds-ring">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>

        <modal v-if="showModal" ref="modal" :title="__('Confirm Billing Action')" :max-width="800" @closed="showModal = false">
            <template #default>
                {{ confirmText }}
            </template>

            <template #footer>
                <div class="flex justify-end mt-4">
                    <spark-secondary-button @click.native="close">{{ __('Nevermind') }}</spark-secondary-button>
                    <spark-button class="ml-2" @click.native="confirm">{{ __('Confirm') }}</spark-button>
                </div>
            </template>
        </modal>
    </div>

    <!-- bg-gray-50 bg-gray-100 bg-gray-200 bg-gray-300 bg-gray-400 bg-gray-500 bg-gray-600 bg-gray-700 bg-gray-800 bg-gray-900 -->
    <!-- bg-red-50 bg-red-100 bg-red-200 bg-red-300 bg-red-400 bg-red-500 bg-red-600 bg-red-700 bg-red-800 bg-red-900 -->
    <!-- bg-yellow-50 bg-yellow-100 bg-yellow-200 bg-yellow-300 bg-yellow-400 bg-yellow-500 bg-yellow-600 bg-yellow-700 bg-yellow-800 bg-yellow-900 -->
    <!-- bg-green-50 bg-green-100 bg-green-200 bg-green-300 bg-green-400 bg-green-500 bg-green-600 bg-green-700 bg-green-800 bg-green-900 -->
    <!-- bg-blue-50 bg-blue-100 bg-blue-200 bg-blue-300 bg-blue-400 bg-blue-500 bg-blue-600 bg-blue-700 bg-blue-800 bg-blue-900 -->
    <!-- bg-indigo-50 bg-indigo-100 bg-indigo-200 bg-indigo-300 bg-indigo-400 bg-indigo-500 bg-indigo-600 bg-indigo-700 bg-indigo-800 bg-indigo-900 -->
    <!-- bg-purple-50 bg-purple-100 bg-purple-200 bg-purple-300 bg-purple-400 bg-purple-500 bg-purple-600 bg-purple-700 bg-purple-800 bg-indigo-900 -->
    <!-- bg-pink-50 bg-pink-100 bg-pink-200 bg-pink-300 bg-pink-400 bg-pink-500 bg-pink-600 bg-pink-700 bg-pink-800 bg-indigo-900 -->
</template>

<script>
import ErrorMessages from './../Components/ErrorMessages';
import InfoMessages from './../Components/InfoMessages';
import IntervalSelector from './../Components/IntervalSelector';
import Modal from './../Components/Modal';
import Plan from './../Components/Plan';
import PlanList from './../Components/PlanList';
import PlanSectionHeading from './../Components/PlanSectionHeading';
import ReceiptList from './../Components/ReceiptList';
import SectionHeading from './../Components/SectionHeading';
import SparkButton from './../Components/Button';
import SparkSecondaryButton from './../Components/SecondaryButton';
import SuccessMessage from './../Components/SuccessMessage';
import {router} from '@inertiajs/vue2'

export default {
    components: {
        ErrorMessages,
        InfoMessages,
        IntervalSelector,
        Modal,
        Plan,
        PlanList,
        PlanSectionHeading,
        ReceiptList,
        SectionHeading,
        SparkButton,
        SparkSecondaryButton,
        SuccessMessage,
    },

    props: [
        'balance',
        'invoices',

        'billableId',
        'billableType',
        'billingAddressRequired',
        'collectionMethod',
        'collectsVat',
        'collectsBillingAddress',
        'enforcesAcceptingTerms',
        'monthlyPlans',
        'paymentMethod',
        'paymentMethods',
        'plan',
        'seatName',
        'userName',
        'yearlyPlans',
    ],

    data() {
        return {
            errors: [],
            processing: false,

            showingPlansOfInterval: 'monthly',

            subscribing: null,
            showingCouponCode: false,
            addingVatNumber: false,
            subscriptionForm: {
                coupon: null,
                country: '',
                accept: false,
                vat: '',
                postal_code: '',
                address: '',
                address2: '',
                city: '',
                state: '',
                extra: ''
            },

            checkoutTax: 0,
            checkoutAmount: 0,
            rawCheckoutAmount: 0,
            loadingTaxCalculations: true,

            paymentInformationForm: {
                country: '',
                vat: '',
                postal_code: '',
                address: '',
                address2: '',
                city: '',
                state: '',
                extra: ''
            },

            receiptEmailsForm: {
                receipt_emails: '',
            },

            couponForm: {
                coupon: '',
            },

            selectingNewPlan: false,
            updatingPaymentInformation: false,

            billingInformationForm: {
                extra: ''
            },

            confirmAction: null,
            confirmArguments: [],
            confirmText: '',
            showModal: false,
        };
    },

    watch: {
        "couponForm.coupon"(val) {
            if (val) {
                this.$refs.applyCouponButton.$el.removeAttribute('disabled')
            } else {
                this.$refs.applyCouponButton.$el.setAttribute('disabled', 'disabled')
            }
        },

        "receiptEmailsForm.receipt_emails"(newValue, oldValue) {
            if (newValue || oldValue) {
                this.$refs.updateReceiptEmailsButton.$el.removeAttribute('disabled')
            } else {
                this.$refs.updateReceiptEmailsButton.$el.setAttribute('disabled', 'disabled')
            }
        },

        subscribing(val) {
            if (!val) {
                window.history.pushState({}, document.title, window.location.pathname);
            } else {
                window.history.pushState({}, document.title, window.location.pathname + '?subscribe=' + val.id);

                this.calculatingTax(this.subscribing, (data) => {
                    this.checkoutTax = data.tax;
                    this.checkoutAmount = data.total;
                    this.rawCheckoutAmount = data.raw_total;
                });
            }

            this.checkoutTax = 0;

            if (!this.$page.props.billable.vat_id) {
                this.addingVatNumber = false;
            }
        },

        'subscriptionForm.country'(val) {
            if (!this.$page.props.billable.vat_id) {
                this.addingVatNumber = false;
            }

            if (this.collectsVat && this.subscribing) {
                this.calculatingTax(this.subscribing, (data) => {
                    this.checkoutTax = data.tax;
                    this.checkoutAmount = data.total;
                    this.rawCheckoutAmount = data.raw_total;
                });
            }
        },

        'subscriptionForm.vat': _.debounce(function () {
            if (this.collectsVat && this.subscribing) {
                this.calculatingTax(this.subscribing, (data) => {
                    this.checkoutTax = data.tax;
                    this.checkoutAmount = data.total;
                    this.rawCheckoutAmount = data.raw_total;
                });
            }
        }, 500)
    },

    /**
     * Initialize the component.
     */
    mounted() {
        this.subscriptionForm.extra = this.$page.props.billable.extra_billing_information;
        this.subscriptionForm.address = this.$page.props.billable.billing_address;
        this.subscriptionForm.address2 = this.$page.props.billable.billing_address_line_2;
        this.subscriptionForm.city = this.$page.props.billable.billing_city;
        this.subscriptionForm.state = this.$page.props.billable.billing_state;
        this.subscriptionForm.postal_code = this.$page.props.billable.billing_postal_code;
        this.subscriptionForm.country = this.$page.props.billable.billing_country || '';
        this.subscriptionForm.vat = this.$page.props.billable.vat_id;

        this.paymentInformationForm.address = this.$page.props.billable.billing_address;
        this.paymentInformationForm.address2 = this.$page.props.billable.billing_address_line_2;
        this.paymentInformationForm.city = this.$page.props.billable.billing_city;
        this.paymentInformationForm.state = this.$page.props.billable.billing_state;
        this.paymentInformationForm.postal_code = this.$page.props.billable.billing_postal_code;
        this.paymentInformationForm.country = this.$page.props.billable.billing_country;
        this.paymentInformationForm.vat = this.$page.props.billable.vat_id;

        if (this.$page.props.billable.vat_id) {
            this.addingVatNumber = true;
        }

        this.billingInformationForm.extra = this.$page.props.billable.extra_billing_information;

        this.receiptEmailsForm.receipt_emails = this.$page.props.billable.receipt_emails.join(',');

        router.on('invalid', (event) => {
            event.preventDefault();

            if (event.detail.response.request.responseURL) {
                window.location.href = event.detail.response.request.responseURL;
            } else {
                console.error(event);
            }
        });

        if (this.monthlyPlans.length == 0 &&
            this.yearlyPlans.length > 0) {
            this.showingPlansOfInterval = 'yearly';
        } else {
            this.showingPlansOfInterval = this.$page.props.defaultInterval;
        }

        if (this.$page.props.state == 'none' && this.$page.props.subscribingTo) {
            this.startSubscribingToPlan(this.$page.props.subscribingTo);
        }

        this.loadLazyData()
    },

    computed: {
        /**
         * Get all open invoices once the data has been loaded.
         */
        openInvoices() {
            return this.receipts?.open || []
        },

        /**
         * Get all receipts once the data has been loaded.
         */
        receipts() {
            return this.invoices?.receipts || null
        },

        /**
         * Get the formatted balance once the data has been loaded.
         */
        formattedBalance() {
            return this.balance?.formatted
        },

        /**
         * Get the raw balance once the data has been loaded.
         */
        rawBalance() {
            return this.balance?.raw || 0
        },

        /**
         * Determine if the selected country is a country where VAT is collected.
         */
        vatComplicit() {
            return this.collectsVat ? _.includes([
                'BE', 'BG', 'CZ', 'DK', 'DE',
                'EE', 'IE', 'GR', 'ES', 'FR',
                'HR', 'IT', 'CY', 'LV', 'LT',
                'LU', 'HU', 'MT', 'NL', 'AT',
                'PL', 'PT', 'RO', 'SI', 'SK',
                'FI', 'SE', 'GB',
            ], this.subscriptionForm.country) : false;
        },
    },

    methods: {
        /**
         * Begin the process of subscription to a plan.
         */
        startSubscribingToPlan(plan) {
            this.subscribing = plan;
        },

        /**
         * Actually create a new subscription for the billable.
         */
        confirmSubscription() {
            if (this.enforcesAcceptingTerms && !this.subscriptionForm.accept) {
                this.errors = [this.__('Please accept the terms of service.')];

                return;
            }

            this.processing = true;

            this.request('POST', '/spark/subscription', {
                plan: this.subscribing.id,
                coupon: this.subscriptionForm.coupon,
                extra_billing_information: this.subscriptionForm.extra,
                billing_address: this.subscriptionForm.address,
                billing_address_line_2: this.subscriptionForm.address2,
                billing_city: this.subscriptionForm.city,
                billing_state: this.subscriptionForm.state,
                billing_postal_code: this.subscriptionForm.postal_code,
                billing_country: this.subscriptionForm.country,
                vat_id: this.subscriptionForm.vat,
            }).then(response => {
                this.billingInformationForm.extra = this.subscriptionForm.extra;

                if (response) {
                    window.location.href = response.data.redirect;
                } else {
                    this.processing = false;
                }
            });
        },

        /**
         * Handle a payment response and optionally redirect to the payment page.
         */
        handlePaymentResponse(response) {
            if (response && response.data.paymentId) {
                window.location = '/' + this.$page.props.cashierPath + '/payment/' + response.data.paymentId + '?redirect=' + window.location.origin + '/' + this.$page.props.sparkPath;
            } else if (response) {
                this.reloadData(['nextPayment', 'plan', 'openInvoices', 'invoices', 'state', 'trialEndsAt']);
            } else {
                this.processing = false;
            }
        },

        /**
         * Switch to the given plan.
         */
        switchToPlan(plan) {
            this.processing = true;

            this.request('PUT', '/spark/subscription', {
                plan: plan.id,
            }).then(response => {
                this.handlePaymentResponse(response);
            });
        },

        /**
         * Toggle the plan intervals that are being displayed.
         */
        toggleDisplayedPlanIntervals() {
            if (this.showingPlansOfInterval == 'monthly') {
                this.showingPlansOfInterval = 'yearly';
            } else {
                this.showingPlansOfInterval = 'monthly';
            }
        },

        /**
         * Show the coupon code entry field.
         */
        showCouponCode() {
            this.showingCouponCode = true;

            this.$nextTick(() => this.$refs.coupon.focus());
        },

        /**
         * Show the VAT number entry field.
         */
        showVatNumber() {
            this.addingVatNumber = true;

            this.$nextTick(() => this.$refs.vat.focus());
        },

        /**
         * Set up a new payment method to use for later.
         */
        addPaymentMethod() {
            this.processing = true;

            this.request('POST', '/spark/subscription/payment-method').then(response => {
                if (response) {
                    window.location.href = response.data.redirect;
                } else {
                    this.processing = false;
                }
            });
        },

        /**
         * Set a payment method as the default.
         */
        defaultPaymentMethod(paymentMethod) {
            this.processing = true;

            this.request('PUT', '/spark/subscription/payment-method/default', {
                payment_method: paymentMethod,
            }).then(response => {
                if (response) {
                    this.reloadData(['paymentMethods']);
                } else {
                    this.processing = false;
                }
            });
        },

        /**
         * Delete a payment method.
         */
        deletePaymentMethod(paymentMethod) {
            this.processing = true;

            this.request('DELETE', '/spark/subscription/payment-method', {
                payment_method: paymentMethod,
            }).then(response => {
                if (response) {
                    this.reloadData(['paymentMethods']);
                } else {
                    this.processing = false;
                }
            });
        },

        /**
         * Update the customer's payment information.
         */
        updatePaymentInformation() {
            this.processing = true;

            this.request('PUT', '/spark/subscription/payment-information', {
                billing_address: this.paymentInformationForm.address,
                billing_address_line_2: this.paymentInformationForm.address2,
                billing_city: this.paymentInformationForm.city,
                billing_state: this.paymentInformationForm.state,
                billing_postal_code: this.paymentInformationForm.postal_code,
                billing_country: this.paymentInformationForm.country,
                vat_id: this.paymentInformationForm.vat,
            }).then(response => {
                if (response) {
                    this.reloadData();
                } else {
                    this.processing = false;
                }
            });
        },

        /**
         * Check if there are any unpaid invoices.
         */
        hasUnpaidInvoices() {
            return this.openInvoices.length > 0;
        },

        /**
         * Update the extra billing information for the customer.
         */
        updateBillingInformation() {
            this.processing = true;

            this.request('PUT', '/spark/billing-information', {
                extra_billing_information: this.billingInformationForm.extra,
            }).then(response => {
                this.subscriptionForm.extra = this.billingInformationForm.extra;

                this.reloadData();
            });
        },

        /**
         * Update the email addresses we send receipts to.
         */
        updateReceiptEmails() {
            this.processing = true;

            this.request('PUT', '/spark/receipt-emails', {
                receipt_emails: this.receiptEmailsForm.receipt_emails,
            }).then(response => {
                this.reloadData();

                if (! this.receiptEmailsForm.receipt_emails) {
                    this.$refs.updateReceiptEmailsButton.$el.setAttribute('disabled', 'disabled')
                }
            });
        },

        /**
         * Apply a coupon to the existing subscription.
         */
        applyCoupon() {
            this.processing = true;

            this.request('PUT', '/spark/coupon', {
                coupon: this.couponForm.coupon,
            }).then(response => {
                this.reloadData();
            });
        },

        /**
         * Cancel the customer's subscription.
         */
        cancelSubscription() {
            this.processing = true;

            this.request('PUT', '/spark/subscription/cancel').then(response => {
                this.reloadData();
            });
        },

        /**
         * Resume a cancelled subscription.
         */
        resumeSubscription() {
            this.processing = true;

            this.request('PUT', '/spark/subscription/resume', {}).then(response => {
                this.reloadData();
            });
        },

        /**
         * Calculate the appropriate TAX for the given total.
         */
        calculatingTax(plan, callback) {
            this.loadingTaxCalculations = true;

            return this.request('POST', '/spark/tax-rate', {
                total: plan.raw_price,
                currency: plan.currency,
                vat_number: this.subscriptionForm.vat,
                country: this.subscriptionForm.country,
                postal_code: this.subscriptionForm.postal_code,
            }).then(response => {
                this.loadingTaxCalculations = false;

                callback(response.data)
            });
        },

        /**
         * Retry a failed payment for a given invoice.
         */
        retryPayment(invoice) {
            this.processing = true;

            this.request('POST', `/spark/${invoice.id}/pay`)
                .then(response => {
                    this.handlePaymentResponse(response);
                });
        },

        /**
         * Reload the page's data, while maintaining any current state.
         */
        reloadData(only = []) {
            return this.$inertia.reload({
                ...(only.length && {only}),
                onSuccess: () => {
                    this.selectingNewPlan = false;
                    this.processing = false;
                    this.subscribing = null;
                    this.updatingPaymentInformation = false;
                }
            });
        },

        /**
         * Load only the lazy-loaded data.
         */
        loadLazyData() {
            return this.$inertia.reload({
                only: ['balance', 'invoices'],
            })
        },

        /**
         * Make an outgoing request to the Laravel application.
         */
        request(method, url, data = {}) {
            this.errors = [];

            data.billableType = this.billableType;
            data.billableId = this.billableId;

            return axios.request({
                url: url,
                method: method,
                data: data,
            }).then(response => {
                return response;
            }).catch(error => {
                if (error.response.status == 422) {
                    this.errors = _.flatMap(error.response.data.errors)
                } else {
                    this.errors = [this.__('An unexpected error occurred and we have notified our support team. Please try again later.')]
                }

                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            });
        },

        /**
         * Confirm a modal action.
         */
        confirm() {
            this.$refs.modal?.close();

            this.confirmAction(...this.confirmArguments);

            this.confirmAction = null;
            this.confirmArguments = [];
            this.confirmText = '';
        },

        /**
         * Open a confirm modal.
         */
        open(confirmAction, confirmText, confirmArguments = []) {
            this.confirmAction = confirmAction;
            this.confirmArguments = confirmArguments;
            this.confirmText = confirmText;
            this.showModal = true;
        },

        /**
         * Close a confirm modal.
         */
        close() {
            this.$refs.modal?.close();

            this.confirmAction = null;
            this.confirmArguments = [];
            this.confirmText = '';
        },
    },
}
</script>
