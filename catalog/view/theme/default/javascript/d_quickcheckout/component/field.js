/**
 *   Account Model
 */

(function() {
    this.initFieldSortable = function(step_id) {
        var that = this;
    
        d_quickcheckout_sortable('#' + step_id + '_fields', {
            handle: '.handle-sortable',
            placeholderClass: 'ui-sortable-placeholder'
        }).forEach(item => {
            dv_cash(item).off();
            dv_cash(item).on('sortupdate', function (event) {
                var IDs = [];
                dv_cash('#' + step_id + '_fields').find(".qc-field").each(function() { IDs.push(dv_cash(this).attr('field_id')); });
    
                var state = that.getState();
                for (var key in IDs) {
                    if (state.config['guest'][step_id].fields[IDs[key]].sort_order != parseInt(key)) {
                        that.updateState(['config', 'guest', step_id, 'fields', IDs[key], 'sort_order'], parseInt(key), false); 
                    }
                    if (state.config['register'][step_id].fields[IDs[key]].sort_order != parseInt(key)) {
                        that.updateState(['config', 'register', step_id, 'fields', IDs[key], 'sort_order'], parseInt(key), false); 
                    }
                    if (state.config['logged'][step_id].fields[IDs[key]].sort_order != parseInt(key)) {
                        that.updateState(['config', 'logged', step_id, 'fields', IDs[key], 'sort_order'], parseInt(key), false); 
                    }
                }     
            });
        });
    };

    this.getFieldIds = function(step_id) {
        var fields_sorted = [];
        for (var key in this.getConfig()[step_id].fields) {
            if (this.getConfig()[step_id].fields[key].type) {
                fields_sorted.push(this.getConfig()[step_id].fields[key]);
            }
        }

        fields_sorted.sort(function(a, b) { return a.sort_order - b.sort_order });

        return fields_sorted.map(function(field) {
            return field.id;
        });
    };

    this.getCustomField = function(data, callblack) {
        this.send('extension/module/d_quickcheckout/get_custom_fields', data, function(custom_fields) {
            this.setState({ 'custom_fields': custom_fields }, false);
            if (callblack) {
                callblack();
            }
        }.bind(this));
    };

    this.addCustomField = function(step, custom_field_id) {
        var state = this.getState();

        var custom_field = state.custom_fields.find(function(custom_field) {
            return custom_field.custom_field_id === custom_field_id;
        })

        var field_id = 'custom-' + custom_field.location + '-' + custom_field.custom_field_id;
        var sort_order = Object.keys(state.config.guest[step].fields).length;

        var format = '';
        if (custom_field.type == 'date') {
            format = 'd-m-y'
        }

        if (custom_field.type == 'time') {
            format = 'H:i'
        }

        if (custom_field.type == 'datetime') {
            format = 'd-m-y H:i'
        }

        var accounts = ['guest', 'register', 'logged'];
        for (i = 0; i < accounts.length; i++) {
            state.config[accounts[i]][step].fields[field_id] = {
                'id': field_id,
                'custom_field_id': custom_field.custom_field_id,
                'text': 'entry_' + field_id,
                'placeholder': 'placeholder_' + field_id,
                'display': 1,
                'require': custom_field.required,
                'tooltip': 'tooltip_' + field_id,
                'errors': {
                    'error0': {
                        'not_empty': true,
                        'text': 'error_' + field_id + '_not_empty'
                    }
                },
                'type': custom_field.type,
                'options': custom_field.options,
                'refresh': 0,
                'custom': 1,
                'location': custom_field.location,
                'custom_field_id': custom_field.custom_field_id,
                'sort_order': sort_order,
                'format': format,
                'class': '',
                'value': '',
                'mask': '',
            }
        }

        state.language[step]['entry_' + field_id] = custom_field.name;
        state.language[step]['placeholder_' + field_id] = custom_field.name;
        state.language[step]['tooltip_' + field_id] = '';
        state.language[step]['error_' + field_id + '_min_length'] = state.language.general['error_min_length'];
        state.language[step]['error_' + field_id + '_max_length'] = state.language.general['error_max_length'];

        this.setState(state, false);
    };

    this.deleteCustomField = function(step, custom_field_id) {
        var state = this.getState();
        var accounts = ['guest', 'register', 'logged'];

        for (i = 0; i < accounts.length; i++) {

            delete state.config[accounts[i]][step].fields[custom_field_id];

            this.updateState(['config', accounts[i], step, 'fields'], state.config[accounts[i]][step].fields, false);
        }

    };

    this.subscribe('field/addDepend', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var depend_id = data.depend_id;
        var depend_value_id = this.rand();

        var accounts = ['guest', 'register', 'logged'];

        for (i = 0; i < accounts.length; i++) {
            if (typeof state.config[accounts[i]][step_id].fields[field_id].depends == 'undefined') {
                state.config[accounts[i]][step_id].fields[field_id].depends = {};
            }
            state.config[accounts[i]][step_id].fields[field_id].depends[depend_id] = {};

            this.updateState(['config', accounts[i], step_id,'fields',field_id, 'depends', depend_id], state.config[accounts[i]][step_id].fields[field_id].depends[depend_id]);
        }

        this.setState(state);
    });

    this.subscribe('field/removeDepend', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var depend_id = data.depend_id;

        var accounts = ['guest', 'register', 'logged'];
        for (i = 0; i < accounts.length; i++) {

            if (typeof state.config[accounts[i]][step_id].fields[field_id].depends !== 'undefined' && typeof state.config[accounts[i]][step_id].fields[field_id].depends[depend_id] !== 'undefined') {
                delete state.config[accounts[i]][step_id].fields[field_id].depends[depend_id];
                if (!Object.keys(state.config[accounts[i]][step_id].fields[field_id].depends).length) {
                    delete state.config[accounts[i]][step_id].fields[field_id].depends;
                    this.updateState(['config', accounts[i], step_id, 'fields', field_id], state.config[accounts[i]][step_id].fields[field_id]);
                } else {
                    this.updateState(['config', accounts[i], step_id, 'fields', field_id, 'depends'], state.config[accounts[i]][step_id].fields[field_id].depends);
                }
            }
        }

    });

    this.subscribe('field/addDependValue', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var depend_id = data.depend_id;
        var depend_value_id = this.rand();

        var accounts = ['guest', 'register', 'logged'];
        for (i = 0; i < accounts.length; i++) {
            if (typeof state.config[accounts[i]][step_id].fields[field_id].depends[depend_id] !== 'undefined') {
                state.config[accounts[i]][step_id].fields[field_id].depends[depend_id][depend_value_id] = JSON.parse('{ "value": "", "display" : "1", "require" : "0"}');

                this.updateState(['config', accounts[i], step_id, 'fields', field_id, 'depends', depend_id, depend_value_id], state.config[accounts[i]][step_id].fields[field_id].depends[depend_id][depend_value_id]);
            }
        }
    });

    this.subscribe('field/removeDependValue', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var depend_id = data.depend_id;
        var depend_value_id = data.depend_value_id;
        
        var accounts = ['guest', 'register', 'logged'];
        for (i = 0; i < accounts.length; i++) {
            if (typeof state.config[accounts[i]][step_id].fields[field_id].depends[depend_id] !== 'undefined') {
                delete state.config[accounts[i]][step_id].fields[field_id].depends[depend_id][depend_value_id];
                this.updateState(['config', accounts[i], step_id, 'fields', field_id, 'depends', depend_id], state.config[accounts[i]][step_id].fields[field_id].depends[depend_id]);
            }
        }
    });

    this.subscribe('field/addError', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var error_id = this.rand();
        var error_type = data.error_type;


        var accounts = ['guest', 'register', 'logged'];

        state.language[step_id]['text_' + field_id + '_' + error_id] = state.language.general['error_' + error_type];
        this.setState(state);

        for (i = 0; i < accounts.length; i++) {
            if (typeof state.config[accounts[i]][step_id].fields[field_id].errors == 'undefined') {
                state.config[accounts[i]][step_id].fields[field_id].errors = {};
            }
            state.config[accounts[i]][step_id].fields[field_id].errors[error_id] = {};
            state.config[accounts[i]][step_id].fields[field_id].errors[error_id][error_type] = true;
            state.config[accounts[i]][step_id].fields[field_id].errors[error_id]['text'] = 'text_' + field_id + '_' + error_id;

            this.updateState(['config', accounts[i], step_id, 'fields', field_id, 'errors'], state.config[accounts[i]][step_id].fields[field_id].errors);
        }
    });

    this.subscribe('field/removeError', function(data) {

        var state = this.getState();
        var step_id = data.step_id;
        var field_id = data.field_id;
        var error_id = data.error_id;

        var accounts = ['guest', 'register', 'logged'];

        for (i = 0; i < accounts.length; i++) {

            delete state.config[accounts[i]][step_id].fields[field_id].errors[error_id];

            this.updateState(['config', accounts[i], step_id, 'fields', field_id, 'errors'], state.config[accounts[i]][step_id].fields[field_id].errors);
        }

    });
})(qc);