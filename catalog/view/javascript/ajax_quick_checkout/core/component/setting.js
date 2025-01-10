/**
 *   Account Model
 */

(function() {

    this.pageCount = 0;

    this.subscribe('setting/save', function(data) {
        this.hideSetting();
        this.updateLayout();
        var state = this.getState();

        if (state.opencart_version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.update';
        } else {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|update';
        }

        this.send(url, state, function(json) {
            this.setState(json);
            this.render();
            this.hideLoader();
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
        var state = this.getState();

        if (state.opencart_version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.reset';
        } else {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|reset';
        }
        this.send(url, {}, function(json) {
            this.setState(json);
            this.render();
        }.bind(this));
    });

    this.subscribe('setting/changeLayout', function(data) {
        this.hideSetting();
        this.updateState(['layout', 'pages'], {});
        this.showSpinner();
        var state = this.getState();

        if (state.opencart_version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.change_layout';
        } else {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|change_layout';
        }
        this.send(url, { layout_codename: data.layout_codename }, function(json) {
            this.setState(json);
            this.render();
        }.bind(this));
    });

    this.subscribe('setting/changeSkin', function(data) {
        this.updateState(['layout', 'skin'], data.skin_codename);
    });

    this.subscribe('setting/changeLanguage', function(data) {
        var state = this.getState();
        state.session.language = data.language;
        this.setState(state);

        var difference = this.getChange();
        this.showLoader();
        if (state.opencart_version > '4.0.1.1') {
            var url1 = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.change_language';
        } else {
            var url1 = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|change_language';
        }
        this.send(url1, { language: data.language }, function(json) {
            this.showLoader();
            if (state.opencart_version > '4.0.1.1') {
                var url2 = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.get_language';
            } else {
                var url2 = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|get_language';
            }
            this.send(url2, { language: data.language }, function(json) {
                this.setState(json);
            }.bind(this));
        }.bind(this));
    });

    this.subscribe('setting/changeStore', function(data) {
        var state = this.getState();

        this.showLoader();
        if (state.opencart_version > '4.0.1.1') {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout.get_store_setting';
        } else {
            var url = 'extension/ajax_quick_checkout/module/ajax_quick_checkout|get_store_setting';
        }
        this.send(url, { setting_id: data.setting_id }, function(json) {
            this.updateState(['layout'], {});
            this.setState(json);
            this.render();
        }.bind(this));
    });


    this.showSetting = function(setting_id) {
        this.hideSetting();
        document.getElementsByTagName('body')[0].classList.add('show-setting');
        document.getElementById(setting_id).classList.add('show');
    }

    this.hideSetting = function() {
        document.getElementsByTagName('body')[0].classList.remove('show-setting');
        Array.from(document.getElementsByClassName('qc-setting')).forEach(function(el) {
            el.classList.remove('show');
        });
    }

    this.toggleSetting = function() {

        if (document.getElementsByTagName('body')[0].classList.contains('popup')) {
            document.getElementsByTagName('body')[0].classList.remove('popup');
        } else {
            document.getElementsByTagName('body')[0].classList.add('popup');
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
        var pro = '';
        if (this.getState().pro) {
            pro = '_pro';
        }
        var that = this;
        axios.get('extension/ajax_quick_checkout' + pro + '/catalog/view/stylesheet/ajax_quick_checkout/skin/' + this.getLayout().skin + '/' + this.getLayout().skin + '.css?' + this.rand()).then(function(response){
            document.querySelector('html > head').querySelector('[title="ajax_quick_checkout"]')?.remove();
                var style = '<style title="ajax_quick_checkout">';
                // style += response;

                style += that.buildStyleBySelector('body > div, body > nav, body > header, body > footer, body > section, body > article, body > table, body > span, body > pre, body > template', {
                    'display': (that.getLayout().header_footer == 1) ? 'block' : 'none'
                })

                style += this.buildStyleBySelector('body > ajax_quick_checkout', {
                    'padding': (that.getLayout().header_footer == 1) ? '0px' : '40px',
                    'display': (that.getLayout().header_footer == 1) ? 'block' : 'block'
                })

                style += '</style>';

                document.querySelector('html > head').insertAdjacentHTML('afterend', style);

                if (this.getLayout().header_footer != 1) {
                    document.getElementsByTagName('body')[0].insertAdjacentElement('afterbegin', document.getElementById('ajax_quick_checkout'));
                } else {
                    document.getElementsByClassName('qc-spinner')[0].insertAdjacentElement('afterend', document.getElementById('ajax_quick_checkout'));
                }
        });
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
            Array.from(document.querySelectorAll('#top-links > ul > li:nth-child(2)')).forEach(function(el) {
                el.innerHTML = json['text_account_login'];
            });
        }

        if (json['cart_total_text']) {
            var state = this.getState();

            if (state.opencart_version > '4.0.1.1') {
                this.get('common/cart.info', function (data) {
                    dv_cash('#header-cart').html(data);
                });
            } else {
                this.get('common/cart|info', function (data) {
                    dv_cash('#header-cart').html(data);
                });
            }
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
