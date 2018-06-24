/**
*   Account Model
*/

(function(){

    this.pageCount = 0;

    this.subscribe('setting/save', function(data) {
        var self = this;
        var state = this.getState();
        this.showLoader();
        var difference = this.getChange();
        difference.layout = state.layout;

        this.send('extension/module/d_quickcheckout/update', difference, function(json) {
            //no need to update state.
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
        this.send('extension/module/d_quickcheckout/change_layout', { layout_codename: data.layout_codename}, function(json) {
            this.setState(json);
            this.render();
        }.bind(this));
    });

    this.subscribe('setting/changeSkin', function(data) {
        this.updateState(['session', 'skin'], data.skin_codename);
    });

    

    this.subscribe('setting/changeLanguage', function(data) {
        var state = this.getState();
        state.session.language = data.language_id;
        this.setState(state);

        var difference = this.getChange();
        this.showLoader();
        this.send('extension/module/d_quickcheckout/change_language', {language_id: data.language_id}, function(json) {
            this.showLoader();
            this.send('extension/module/d_quickcheckout/get_language', {session: { language : data.language_id } }, function(json) {
                this.setState(json);
            }.bind(this));
        }.bind(this));
    });


    this.showSetting = function(setting_id){
        this.hideSetting();
        $('body').addClass('show-setting');
        $('#'+ setting_id).addClass('show');
    }

    this.hideSetting = function(){

        $('body').removeClass('show-setting');
        $('.setting').removeClass('show');
    }

    this.toggleSetting = function(){
        if($('body').hasClass('popup')){
            $('body').removeClass('popup');
        }else{
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

})(qc);