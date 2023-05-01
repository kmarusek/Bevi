/* global ppress_stripe_vars */
/* global ppressCheckoutForm */
/* global pp_ajax_form */

(function ($) {

    function PPressStripe() {

        var _this = this,
            stripe = Stripe(ppress_stripe_vars.publishable_key, {
                'locale': ppress_stripe_vars.locale
            });

        this.init = function () {

            window.processCheckoutFlag = false;
            window.confirmPaymentFlag = false;

            $(document).on('ppress_updated_checkout', _this.updated_checkout);

            $(document).on('ppress_update_checkout', _this.unmountPaymentElement);

            $(document).on('click', '#ppress-checkout-button', function () {
                window.processCheckoutFlag = true;
            });

            _this.updatePaymentElement();
        };

        this.updated_checkout = function (e, response) {

            _this.checkout_form = $('form#ppress_mb_checkout_form');

            _this.checkout_form.on('ppress_checkout_place_order_stripe', _this.validateFormSubmission);
            _this.checkout_form.on('ppress_process_checkout_stripe', _this.processCheckout);

            _this.mountPaymentElement(response);
        };

        this.getBillingDetails = function () {
            return {
                name: $('#stripe-card_name').val(),
                email: $('#ppmb_email').val(),
                phone: $('#stripe_ppress_billing_phone').val(),
                address: {
                    line1: $('#stripe_ppress_billing_address').val(),
                    line2: '',
                    city: $('#stripe_ppress_billing_city').val(),
                    state: $('#stripe_ppress_billing_state').val(),
                    country: $('#stripe_ppress_billing_country').val(),
                    postal_code: $('#stripe_ppress_billing_postcode').val(),
                }
            };
        };

        this.updatePaymentElement = function () {

            let callback = function () {

                _this.unmountPaymentElement();

                if (typeof window.elements.create !== 'undefined') {
                    window.paymentElement = window.elements.create('payment', _this.getPaymentOptions());
                    window.paymentElement.mount('#ppress-stripe-card-element');
                }
            };

            // If the email address is changed, re-mount to allow the Link element to show.
            $(document).on('change', '#ppmb_email', callback);
        };

        this.getPaymentOptions = function () {
            return {
                layout: {type: 'tabs'},
                fields: {
                    billingDetails: ppress_stripe_vars.hideBillingFields === 'true' ? 'never' : 'auto'
                },
                defaultValues: {
                    billingDetails: _this.getBillingDetails()
                },
                terms: {
                    card: 'never'
                }
            };
        };

        this.mountPaymentElement = function (response) {

            if ($('#ppress-stripe-card-element').length === 0) return;

            window.elements = stripe.elements(response.data.stripe_args);

            window.paymentElement = window.elements.create('payment', _this.getPaymentOptions());

            window.paymentElement.mount('#ppress-stripe-card-element');
        };

        this.unmountPaymentElement = function () {

            if ($('#ppress-stripe-card-element').length === 0) return;

            if (typeof window.paymentElement.destroy !== 'undefined') {
                window.paymentElement.destroy();
            }
        };

        this.validateFormSubmission = function () {

            if (window.processCheckoutFlag === true) {

                window.processCheckoutFlag = false;

                window.elements.submit().then(function (result) {

                    if ('error' in result && typeof result.error.message !== 'undefined') {
                        ppressCheckoutForm.createAlertMessage(result.error.message);
                    } else {
                        _this.checkout_form.submit();
                    }
                });

                return false;
            }
        };

        this.processCheckout = function (e, response, payment_method) {

            if (ppressCheckoutForm.is_var_defined(response.gateway_response) === true) {

                if (
                    (   // for subscription payments
                        ppressCheckoutForm.is_var_defined(response.gateway_response.latest_invoice) === true &&
                        ppressCheckoutForm.is_var_defined(response.gateway_response.latest_invoice.payment_intent) === true &&
                        ppressCheckoutForm.is_var_defined(response.gateway_response.latest_invoice.payment_intent.status) === true
                    )
                    ||
                    (   // for one-time payments
                        ppressCheckoutForm.is_var_defined(response.gateway_response.status) === true
                    )
                ) {

                    // ensure the below block of code runs once
                    if (window.confirmPaymentFlag === false) {

                        window.confirmPaymentFlag = true;

                        let client_secret;

                        if (ppressCheckoutForm.is_var_defined(response.gateway_response.client_secret)) {
                            client_secret = response.gateway_response.client_secret;
                        } else {
                            client_secret = response.gateway_response.latest_invoice.payment_intent.client_secret;
                        }

                        stripe.confirmPayment({
                            elements: window.elements,
                            clientSecret: client_secret,
                            confirmParams: {
                                return_url: response.order_success_url,
                                payment_method_data: {
                                    billing_details: _this.getBillingDetails(),
                                }
                            },
                            redirect: 'if_required'

                        }).then(function (result) {
                            if (result.error) {
                                window.confirmPaymentFlag = false;
                                ppressCheckoutForm.createAlertMessage(result.error.message);
                            } else {

                                if (result.paymentIntent.status === 'succeeded') {
                                    $(document.body).trigger('ppress_checkout_success', [response, payment_method]);
                                }

                                window.location.assign(response.order_success_url);
                            }
                        });
                    }

                    return false;
                }
            }
        };
    }

    (new PPressStripe()).init();

})(jQuery);