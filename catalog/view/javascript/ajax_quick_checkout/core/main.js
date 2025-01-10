/**
 * d_quickcheckout Store object
 * Other models simply extend this Store to add extra functions.
 * In some ways it is similer the redux pattern.
 *
 * #How to extend it?
 * Create a file in /step or /component and add (function(){ this.func = function(){...} })(qc)
 * All function can then be accessed inside a riot .tag file as this.store.func()
 * The proper way is to use this.store.dispatch(action, data) inside a tag file
 * and then catch it with this.subscribe(action, callback) inside your model file
 *
 * #Store Object is avalible in every tag
 * We use riot.mixin to add default properties to every riot tag, so you can
 * implement your own riot tags and they will by default have access to the store
 */
 var qc = (function() {

    /*allows for the qc object to trigger and listen to custom events.*/
    riot.observable(this);

    this.action_pending = [];

    this.layout = [];

    /**
     *   createStore. Initialize your app. This will add the default value to the
     * state. Refer to Redux http://redux.js.org/docs/api/Store.html
     */
    this.createStore = function(state) {
        /*console.log('Welcome to Ajax Quick Checkout.');*/

        this.state = state

        /*initial state*/
        this.stateOld = JSON.parse(JSON.stringify(state));

        this.beforeLeave();
        /*allows the qc.init(store) to be passed into the mixins model value.*/
        return this;
    };

    /**
     *   UpdateState. A wrapper function to update the state and call riot update.
     */
    this.updateState = function(key, data, is_upd = true) {
        d_quickcheckout_lodash.set(this.state, key, data);

        this.state.edited = true;

        /*avoid flicker page*/
        if (is_upd) {
            setTimeout(function() {
                riot.update(); /*will start a full update of all tags*/
            }, 10);
        }
    };

    this.loading = function(state) {
        var edited = this.state.edited;
        if (!state) {
            d_quickcheckout_lodash.set(this.state, 'loading', state);
        }
        /*REFACTOR - change to loader*/
        d_quickcheckout_lodash.set(this.state, 'session.confirm.loading', state);

        this.state.edited = edited;
        setTimeout(function() {
            riot.update(); /*will start a full update of all tags*/
        }, 10);
    };

    this.render = function() {
        riot.update(); /*will start a full update of all tags*/
    };

    this.setState = function(data, is_upd = true) {

        this.state = d_quickcheckout_lodash.merge(this.state, data);

        
        this.state.edited = true;
        if (is_upd) {
            setTimeout(function() {
                riot.update(); /*will start a full update of all tags*/
            }, 10);
        }
    };

    /**
     *   GetState. Returns the state.
     */
    this.getState = function() {
        return this.state;
    };


    this.in_array = function(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (typeof haystack[i] == 'object') {
                if (arrayCompare(haystack[i], needle)) return true;
            } else {
                if (haystack[i] == needle) return true;
            }
        }
        return false;
    };

    this.decodeHTML = function(html) {
        var txt = document.createElement('textarea');
        txt.innerHTML = html;
        return txt.value;
    };

    /**
     *   Config. Ajax Quick Checkout shortcut to the config for the current
     *   Account option.
     */
    this.getConfig = function() {
        return this.state.config[this.getAccount()];
    };

    /**
     *   Session. Ajax Quick Checkout shortcut to the session for the current
     *   Account option.
     */
    this.getSession = function() {
        return this.state.session;
    };

    /**
     *   Layout. Ajax Quick Checkout shortcut to the session for the current
     *   Account option.
     */
    this.getLayout = function() {
        return this.state.layout;
    };


    this.getAccount = function() {
        return this.state.session.account;
    };

    this.getLanguage = function() {
        return this.state.language;
    };



    /**
     *   Error. Ajax Quick Checkout shortcut to the error
     */
    this.getError = function() {
        if (!this.state.errors) {
            this.state.errors = {};
        }
        return this.state.errors;
    };

    this.getChange = function() {
        return getDiff(this.stateOld, this.state);
    };

    this.setChange = function(state) {
        this.stateOld = JSON.parse(JSON.stringify(state));
    };
    /**
     *   Redux:dispatch function. A wrapper function for triggering a custom event with a
     * updated state value.
     */
    this.dispatch = function(action, state) {
        this.trigger(action, state);
    };

    /**
     *   Redux:subscribe function. A wrapper function for catching a custom event with a
     * callback function.
     */
    this.subscribe = function(action, callback) {
        this.on(action, callback);
    };

    this.rand = function() {
        return Math.random().toString(36).substring(2, 9);
    };

    this.stripTags = function(text) {
        if (text) {
            return text.replace(/<\/?[^>]+(>|$)/g, "");
        }
    };

    this.isEmpty = function(obj) {
        if (typeof(obj) == 'undefined') {
            return true;
        }
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop))
                return false;
        }
        return JSON.stringify(obj) === JSON.stringify({});
    };

    this.sortItems = function(items) {
        var items_sorted = [];

        for (var key in items) items_sorted.push(items[key]);
        items_sorted.sort(function(a, b) { return a.sort_order - b.sort_order });

        items_sorted = items_sorted.map(function(item) {
            return item.id;
        });
        return items_sorted;
    };

    this.countItems = function(items) {
        return Object.keys(items).length;
    };

    this.setLayoutAction = function(action, data) {
        var state = this.getState();
        data.page_id = state.session['page_id'];
        this.action_pending.push({ action: action, data: data });
    };

    this.updateLayout = function() {
        var state = JSON.parse(JSON.stringify(this.getState()));
        if (this.action_pending.length > 0) {
            var layout = {};

            for (page_id in state.layout.pages) {
                this.flattenLayout(state.layout.pages[page_id], 'children', layout);
            }


            for (i in this.action_pending) {
                var action = this.action_pending[i];
                layout = this[action.action](action.data, layout);
            }

            for (page_id in state.layout.pages) {
                state.layout.pages[page_id].children = this.unflattenLayout(layout, page_id);
            }


            this.action_pending = [];

            this.updateState(['layout', 'pages'], {});
            this.render();
            this.updateState(['layout', 'pages'], state.layout.pages);
        }
    };

    /* recursive collection function*/
    this.flattenLayout = function(tree, key, collection) {
        if (!tree[key] || tree[key].length === 0) return;
        var i = 0;
        for (child_id in tree[key]) {
            var child = tree[key][child_id];

            collection[child.id] = child;
            this.flattenLayout(child, key, collection);
            if (tree.id) {
                collection[child.id].parent = tree.id;
            } else {
                collection[child.id].parent = 0;
            }

            collection[child.id].sort_order = i;
            i++;

            delete collection[child.id].children;
        }
        return;
    };


    this.unflattenLayout = function(arr, parent_id) {
        var nodes = [];

        d_quickcheckout_lodash.mapValues(arr, function(value) {
            if (value.type != 'item') {
                value.children = [];
            }
            nodes.push(value);
            return value;
        });

        var map = {};
        var node;
        var roots = [];
        for (var i = 0; i < nodes.length; i += 1) {
            node = nodes[i];
            map[node.id] = i;
        }

        for (var i = 0; i < nodes.length; i += 1) {
            node = nodes[i];
            if (node.parent !== "0") {
                if (typeof map[node.parent] !== 'undefined') {
                    nodes[map[node.parent]].children.push(node);
                    nodes[map[node.parent]].children.sort(function(a, b) {
                        return a.sort_order == b.sort_order ? 0 : +(a.sort_order > b.sort_order) || -1;
                    })
                }
            } else {
                roots.push(node);
            }
        }

        var result = nodes.map(function(value){
            if (value.parent == parent_id) {
                return value;
            }
        }).filter(function(value) {
            return value !== undefined;
        });

        return this.toObject(result);
    };

    this.toObject = function(array) {
        var new_array = {};
        if (Array.isArray(array)) {
            for (var i = 0, len = array.length; i < len; i++) {
                new_array[array[i].id] = d_quickcheckout_lodash.merge({}, array[i]);
                if (new_array[array[i].id].children != 'undefined') {
                    new_array[array[i].id].children = this.toObject(new_array[array[i].id].children)
                }
            }
        }
        return new_array;
    };

    this.raw = function(text) {
        var txt = document.createElement("textarea");
        dv_cash(txt).html(text);
        return txt.value;
    };

    /**
     *   Call to server for update
     *
     */

    this.beforeLeave = function() {
        var that = this;
        if (this.state.edit) { 
            window.addEventListener('beforeunload', function(e) {
                if (that.state.edited) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });
        }
        window.addEventListener('beforeunload', function(e) {
            that.loading(true);
        });
    };

    this.send = function(route, data, callback) {
        this.showLoader();
        /*clear notifications*/
        this.updateState(['notifications'], {});
        var that = this;
        
        axios.post('index.php?route=' + route + '&language=' + this.getState().language_code, data).then(function(response){
            var json = response.data;

            try {
                if (typeof response.data == 'string') {
                    console.error("Ajax Quick Checkout: POST /route=" + route + " returned error: \n" + response.data);
                    this.number_requests = 0;
                    this.hideLoader();
                    return ;
                }
                callback(json);
            } catch (err) {
                console.error("Ajax Quick Checkout: POST /route=" + route + " returned error: \n" + json);
            }
            that.hideLoader();
        });
    };

    this.get = function(route, callback) {
        this.showLoader();
        var that = this;
        axios.get('index.php?route=' + route + '&language=' + this.getState().language_code).then(function(response){
            var json = response.data;
            try {
                callback(json);
            } catch (err) {
                console.error("Ajax Quick Checkout: POST /route=" + route + " returned error: \n" + json);
            }
            that.hideLoader();
        });
    }

    this.showLoader = function() {
        this.loading(true);
        var that = this;
        setTimeout(function() {
            document.querySelector('.qc-loader').style.display = 'block';
            setTimeout(function() {
                that.loading(false);
                document.querySelector('.qc-loader').style.display = 'none';
            }, 10000);
        }, 10);

    };

    this.hideLoader = function() {
        this.loading(false);
        document.querySelector('.qc-loader').style.display = 'none';
    };

    this.showSpinner = function() {
        setTimeout(function() {
           document.querySelector('.qc-spinner').style.display = 'none';
        }, 10);
    };

    this.hideSpinner = function() {
        document.querySelector('.qc-spinner').style.display = 'none';
    };

    this.isMobile = function() {
        if (screen.width <= 480) {
            return true;
        } else false;
    };

    this.getProSuffix = function() {
        if(this.getState().pro) {
            return '_pro';
        } else {
            return '';
        }
    };

    /* this returns the object that can therefore be extended*/
    return this;
})();


/**
 *  Alias for d_quickcheckout
 */
var ajax_quick_checkout = qc;