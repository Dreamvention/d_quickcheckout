qc.FieldView = qc.View.extend({
    initialize: function(e){
        this.template = e.template;
    },


    events: {
        'change input[type=text].not-required': 'updateField',
        'change input[type=text].required': 'validateField',
        'change input[type=tel].not-required': 'validateTelephone',
        'change input[type=tel].required': 'validateTelephone',
        'change input[type=email].not-required': 'updateField',
        'change input[type=email].required': 'validateField',
        'change input[type=password].not-required': 'updateField',
        'change input[type=password].required': 'validateField',
        'change input[type=datetime].not-required': 'updateField',
        'change input[type=datetime].required': 'validateField',
        'change input[type=date].not-required': 'updateField',
        'change input[type=date].required': 'validateField',
        'change input[type=time].not-required': 'updateField',
        'change input[type=time].required': 'validateField',
        'change input[type=radio].not-required': 'updateField',
        'change input[type=radio].required': 'validateField',
        'change input[type=checkbox].not-required': 'updateCheckbox',
        'change input[type=checkbox].required': 'validateCheckbox',
        'change textarea': 'validateField',
        'change select': 'validateField',

    },

    template: '',

    render: function(){

        console.log('field:render');
        this.setValidate();
        $(this.el).html(this.template({'model': this.model.toJSON() }));
        this.setDateTime();
        $('.sort-item').tsort({attr:'data-sort'});
        $('.qc-mask').each(function(){
            $(this).mask($(this).attr('qc-mask'));
        })

        $('.bootstrap-datetimepicker-widget').hide();
        var telephone = $('.telephone-validation');
        telephone.each(function(){
            var telephone_countries = $(this).data('telephone_countries');

            var telephone_preferred_countries = $(this).data('telephone_preferred_countries');

            if (telephone_countries.length == 0){
                telephone_countries="";
            }else{
                telephone_countries=telephone_countries.split(',');
            }
            if (telephone_preferred_countries.length == 0){
                telephone_preferred_countries = "";
            } else {
                telephone_preferred_countries=telephone_preferred_countries.split(',');
            }
            $(this).intlTelInput({
                onlyCountries: telephone_countries,
                preferredCountries: telephone_preferred_countries,
                autoPlaceholder: true,
                utilsScript: "catalog/view/javascript/d_quickcheckout/library/phoneformat/js/utils.js"
            });
        })


    },
// #d_quickcheckout .country-list {display:none;}
// #d_quickcheckout .iti-arrow {display:none;}

    setDateTime: function(){
        var that = this;
        $('.date', this.el).datetimepicker({
            pickTime: false,
            dateFormat: "mm/DD/YYYY",
        })

        $('.time', this.el).datetimepicker({
            pickDate: false,
             dateFormat: "mm/DD/YYYY",
        })

        $('.datetime', this.el).datetimepicker({
            pickDate: true,
            pickTime: true,
             dateFormat: "mm/DD/YYYY",
        })
    },

    setValidate: function(){
        $(this.el).validate({

            submitHandler: function(form) {
            },
            errorPlacement: function(error, element) {
                error.appendTo( element.closest('div[class^="col-"]'));
            },
            highlight: function(element, errorClass, validClass) {
                $(element.form).find("#" + element.id.replace(/\./g, '\\\.') + "_input")
                    .addClass("has-error");
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element.form).find("#" + element.id.replace(/\./g, '\\\.') + "_input")
                    .removeClass("has-error");
            },

            errorClass: "text-danger",
            errorElement: "div"
        });
    },

    validateTelephone: function(e){
        if($('.telephone-validation').length !== 0){
            if ($.trim($(e.currentTarget).val())) {
                if ($(e.currentTarget).intlTelInput("isValidNumber")) {
                    $(e.currentTarget).val($(e.currentTarget).intlTelInput("getNumber"));
                    console.log($(e.currentTarget).intlTelInput("getNumber"))
                    this.updateField(e);
                } else {
                    $(e.currentTarget).val('');
                    $(e.currentTarget).parents('.text-input').removeClass("has-error")
                    .find('.text-danger').remove();

                    $(e.currentTarget).parents('.text-input').addClass("has-error");
                    $(e.currentTarget).parent().after('<div id=\"'+$(e.currentTarget).attr('id')+'-error\" class=\"text-danger\">'+$(e.currentTarget).data('msg-telephone')+'</div>');
                    if(parseInt(config.general.analytics_event)){
                        ga('send', 'event', config.name, 'error', e.currentTarget.name+'.'+e.currentTarget.value);
                    }
                    preloaderStop();
                }
            }
        } else {
            if($('#payment_address_telephone_input').hasClass('required')){
                this.validateField(e);
            } else {
                this.updateField(e);
            }
        }
    },

    validateField: function(e){
        if($(e.currentTarget).valid()){
            this.updateField(e);
        }else{
            if(parseInt(config.general.analytics_event)){
                ga('send', 'event', config.name, 'error', e.currentTarget.name+'.'+e.currentTarget.value);
            }
            preloaderStop();
        }

    },

    updateField:function(e){
        var that = this;
        setTimeout(function() {
            that.model.updateField(e.currentTarget.name, e.currentTarget.value );
        }, 500);

        if(parseInt(config.general.analytics_event)){
            ga('send', 'event', config.name, 'update', e.currentTarget.name);
        }
        preloaderStart();
    },

    validateCheckbox: function(e){
        if($(e.currentTarget).valid()){
            this.updateCheckbox(e);
        }else{
            if(parseInt(config.general.analytics_event)){
                ga('send', 'event', config.name, 'error', e.currentTarget.name+'.'+e.currentTarget.value);
            }
            preloaderStop();
        }

    },

    updateCheckbox: function(e){
        if($(e.currentTarget).is(":checked")){
            this.model.updateField(e.currentTarget.name, e.currentTarget.value );
        }else{
            this.model.updateField(e.currentTarget.name, 0 );
        }
        if(parseInt(config.general.analytics_event)){
            ga('send', 'event', config.name, 'update', e.currentTarget.name);
        }
        preloaderStart();
        if(e.currentTarget.name == 'payment_address.shipping_address'){
            $("#payment_address_zone_id").attr("disabled", true);
        }
    },

});