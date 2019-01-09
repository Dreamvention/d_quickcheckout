/**
 *   Account Model
 */

(function() {
    this.validate = function(value, errors) {
        var result = false;
        $.each(errors, function(i, error) {
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
        //validate straight via server
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
        // console.log('AQC: Sorry, you have errors');
        setTimeout(function() {
            $('html,body').animate({ scrollTop: $(".ve-field--error").offset().top - 60 }, 'slow');
        }, 10);
    }

})(qc);