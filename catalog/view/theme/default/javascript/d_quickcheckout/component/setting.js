/**
 *   Account Model
 */

(function() {

    this.pageCount = 0;

    this.subscribe('setting/save', function(data) {
        this.hideSetting();
        this.updateLayout();
        var state = this.getState();

        // var difference = this.getChange();
        // difference.layout = state.layout;
        this.send('extension/module/d_quickcheckout/update', JSON.stringify(state), function(json) {
            this.setState(json);
            this.render();
            this.hideLoader();
            this.stateCached.edited = false;
        }.bind(this));
    });

    this.subscribe('setting/edit', function(data) {
        this.setState(data);
    });

    this.subscribe('setting/reset', function(data) {
        this.hideSetting();
        this.updateState(['layout', 'pages'], {});
        this.showSpinner();
        this.send('extension/module/d_quickcheckout/reset', {}, function(json) {
            this.setState(json);
            this.render();
        }.bind(this));
    });

    this.subscribe('setting/changeLayout', function(data) {
        this.hideSetting();
        this.updateState(['layout', 'pages'], {});
        this.showSpinner();
        this.send('extension/module/d_quickcheckout/change_layout', { layout_codename: data.layout_codename }, function(json) {
            this.setState(json);
            this.render();
        }.bind(this));
    });

    this.subscribe('setting/changeSkin', function(data) {
        this.updateState(['layout', 'skin'], data.skin_codename);
    });

    this.subscribe('setting/changeLanguage', function(data) {
        var state = this.getState();
        state.session.language = data.language_id;
        this.setState(state);

        var difference = this.getChange();
        this.showLoader();
        this.send('extension/module/d_quickcheckout/change_language', { language_id: data.language_id }, function(json) {
            this.showLoader();
            this.send('extension/module/d_quickcheckout/get_language', { session: { language: data.language_id } }, function(json) {
                this.setState(json);
            }.bind(this));
        }.bind(this));
    });


    this.showSetting = function(setting_id) {
        this.hideSetting();
        $('body').addClass('show-setting');
        $('#' + setting_id).addClass('show');
    }

    this.hideSetting = function() {
        $('body').removeClass('show-setting');
        $('.qc-setting').removeClass('show');
    }

    this.toggleSetting = function() {
        if ($('body').hasClass('popup')) {
            $('body').removeClass('popup');
        } else {
            $('body').addClass('popup');
        }
    }

    this.buildStyleBySelector = function(selector, styles) {
        var styleContainer = selector + ' { ';
        for (key in styles) {
            styleContainer += key + ':' + styles[key] + ';'
        }
        styleContainer += ' } ';
        return styleContainer;
    }

    this.updateLayoutStyle = function() {
        $.when($.get('catalog/view/theme/default/stylesheet/d_quickcheckout/skin/' + this.getLayout().skin + '/' + this.getLayout().skin + '.css?' + this.rand()))
            .done(function(response) {
                $('html > head').find('[title="d_quickcheckout"]').remove();

                var style = '<style title="d_quickcheckout">';
                // style += response;

                style += this.buildStyleBySelector('body > div, body > nav, body > header, body > footer, body > section, body > article, body > table, body > span, body > pre, body > template', {
                    'display': (this.getLayout().header_footer == 1) ? 'block' : 'none'
                })

                style += this.buildStyleBySelector('body > d_quickcheckout', {
                    'padding': (this.getLayout().header_footer == 1) ? '0px' : '40px',
                    'display': (this.getLayout().header_footer == 1) ? 'block' : 'block'
                })

                style += '</style>';

                $('html > head').append($(style));

                if (this.getLayout().header_footer != 1) {
                    $('body').prepend($('#d_quickcheckout'));
                } else {
                    $('.qc-spinner').after($('#d_quickcheckout'));
                }

            }.bind(this));
    }

    alertify.getPro || alertify.dialog('getPro', function() {
        return {
            main: function(content) {
                this.setContent(content);
            },
            setup: function() {
                return {
                    focus: {
                        element: function() {
                            return this.elements.body.querySelector(this.get('selector'));
                        },
                        select: true
                    },
                    options: {
                        basic: true,
                        maximizable: false,
                        resizable: false,
                        padding: false
                    }
                };
            },
            settings: {
                selector: undefined
            }
        };
    });

    this.subscribe('setting/updateCommon', function(json) {
        if (json['text_account_login']) {
            $('#top-links > ul > li:nth-child(2)').html(json['text_account_login']);
        }
        if (json['cart_total_text']) {
            $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['cart_total_text'] + '</span>');
            $('#cart > ul').load('index.php?route=common/cart/info ul li');
        }
    });

})(qc);

$(document).on('click', '.qc-tab', function() {
    $('.qc-tab').removeClass('active');
    $(this).addClass('active');
    var tab_id = $(this).find('a').attr('href');
    $(tab_id).parents('.qc-setting-tab-content').find('.qc-setting-tab-pane').removeClass('in').removeClass('active').hide();
    $(tab_id).addClass('in').addClass('active').show();
    return false;
})