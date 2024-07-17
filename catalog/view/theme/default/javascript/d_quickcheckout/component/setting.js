/**
 *   Account Model
 */

(function() {

    this.pageCount = 0;

    this.subscribe('setting/save', function(data) {
        this.hideSetting();
        this.updateLayout();
        var state = this.getState();

        this.send('extension/module/d_quickcheckout/update', state, function(json) {
            this.setState(json);
            this.render();
            this.hideLoader();
            alertify.success('Settings have been saved');
            this.state.edited = false;
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
        this.updateState(['layout', 'skin'], data.skin_codename, false);
        dv_cash('#d_quickcheckout_skin_style')
        .children('link')
        .prop('href', 'catalog/view/theme/default/stylesheet/d_quickcheckout/skin/' + data.skin_codename + '/' + data.skin_codename + '.css?' + this.rand());
    });

    this.subscribe('setting/changeLanguage', function(data) {
        var state = this.getState();
        state.session.language = data.language_id;
        this.setState(state);

        this.showLoader();
        this.send('extension/module/d_quickcheckout/change_language', { language_id: data.language_id }, function(json) {
            this.showLoader();
            this.send('extension/module/d_quickcheckout/get_language', { session: { language: data.language_id } }, function(json) {
                this.setState(json);
            }.bind(this));
        }.bind(this));
    });

    this.subscribe('setting/changeStore', function(data) {
  
        this.showLoader();
        this.send('extension/module/d_quickcheckout/get_store_setting', { setting_id: data.setting_id }, function(json) {
            this.updateState(['layout'], {});
            this.setState(json);
            this.render();
        }.bind(this));
    });


    this.showSetting = function(setting_id) {
        this.hideSetting(true);
        dv_cash('body').addClass('show-setting');
        this.state.displaySetting[setting_id] = true;
    };

    this.hideSetting = function(isUpd = false) {
        dv_cash('body').removeClass('show-setting');
        
        if (isUpd && Object.values(this.state.displaySetting || {}).length) {
            this.updateState(['displaySetting'], {});
        } else {
            this.state.displaySetting = {};
        }
    };

    this.toggleSetting = function() {
        if (dv_cash('body').hasClass('popup')) {
            dv_cash('body').removeClass('popup');
        } else {
            dv_cash('body').addClass('popup');
        }
    };

    this.buildStyleBySelector = function(selector, styles) {
        var styleContainer = selector + ' { ';
        for (key in styles) {
            styleContainer += key + ':' + styles[key] + ';'
        }
        styleContainer += ' } ';
        return styleContainer;
    };

    this.updateLayoutStyle = function() {
        dv_cash('html > head').find('[title="d_quickcheckout"]').remove();

        var style = '<style title="d_quickcheckout">';
        /* style += response;*/

        style += this.buildStyleBySelector('body > div, body > nav, body > header, body > footer, body > section, body > article, body > table, body > span, body > pre, body > template', {
            'display': (this.getLayout().header_footer == 1) ? 'block' : 'none'
        })

        style += this.buildStyleBySelector('body > d_quickcheckout', {
            'padding': (this.getLayout().header_footer == 1) ? '0px' : '40px',
            'display': (this.getLayout().header_footer == 1) ? 'block' : 'block'
        })

        style += '</style>';

        dv_cash('html > head').append(dv_cash(style));

        if (this.getLayout().header_footer != 1) {
            dv_cash('body').prepend(dv_cash('#d_quickcheckout'));
        } else {
            dv_cash('.qc-spinner').after(dv_cash('#d_quickcheckout'));
        }
    };

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
            dv_cash('#top-links > ul > li:nth-child(2)').html(json['text_account_login']);
        }
        if (json['cart_total_text']) {
            dv_cash('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['cart_total_text'] + '</span>');
            axios.get('index.php?route=common/cart/info').then(function (response) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(response.data, "text/html");
                if (doc.querySelector('ul li')) {
                    dv_cash('#cart > ul').html(doc.querySelector('ul li').outerHTML);
                }
            });
        }
    });

})(qc);

dv_cash(document).on('click', '.qc-tab', function() {
    dv_cash('.qc-tab').removeClass('active');
    dv_cash(this).addClass('active');
    var tab_id = dv_cash(this).find('a').attr('href');
    dv_cash(tab_id).parents('.qc-setting-tab-content').find('.qc-setting-tab-pane').removeClass('in').removeClass('active').hide();
    dv_cash(tab_id).addClass('in').addClass('active').show();
    return false;
});