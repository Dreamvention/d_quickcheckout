/**
 *   Account Model
 */

(function() {
    this.validate = function(value, errors) {
        var result = false;
        dv_cash.each(errors, function(i, error) {
            for (rule in error) {
                if (typeof this[rule] !== "undefined") {
                    if (!this[rule](error[rule], value)) {
                        result = error.text;
                    }
                }
            }
        }.bind(this));
        return result;
    }

    this.not_empty = function(rule, value) {
        return (value.length > 0);
    }

    this.min_length = function(rule, value) {
        return (value.length >= rule);
    }

    this.max_length = function(rule, value) {
        return (value.length <= rule);
    }

    this.checked = function(rule, value) {
        return (value);
    }

    this.compare_to = function(rule, value) {
        var parts = rule.split('.');

        var session = this.getSession();

        return (session[parts[0]][parts[1]] == value);
    }

    this.telephone = function(rule, value) {
        return true;
    }

    this.email_exists = function(rule, value) {
        /*validate straight via server*/
        return true;
    }

    this.regex = function(rule, value) {
        rule.split('/');
        rule = new RegExp(rule[1], 'i');
        return (value.match(rule));
    }

    this.text = function(rule, value) {
        return rule;
    }

    this.goToError = function() {
        /*console.log('AQC: Sorry, you have errors');*/
        setTimeout(function() {
            if (this.getState()['captcha_status'] == 1 && this.in_array(this.getAccount(), this.getState()['config_captcha_page']) && this.getState()['captcha_type'] == 'google') {
                dv_cash('textarea[name="g-recaptcha-response"]').val('');
                var sitekey = dv_cash('#input-payment-captcha').attr('data-sitekey');
                dv_cash('#gRecaptcha').html('');
                dv_cash('#gRecaptcha').html('<script src="//www.google.com/recaptcha/api.js" type="text/javascript"></script><input type="text" style="display: none;" no-reorder="" id="payment_address_google_recaptcha" name="payment_address[google_recaptcha]" class="ve-input d-vis validate qc-required google_recaptcha google_recaptcha"><div id="input-payment-captcha" class="g-recaptcha" data-sitekey="' + sitekey + '" change></div>');
            }
            document.querySelector('html, body').scroll({
                behavior: 'smooth',
                top: document.querySelector('.ve-field--error').offsetTop - 60
            });
        }, 10);
    }

})(qc);