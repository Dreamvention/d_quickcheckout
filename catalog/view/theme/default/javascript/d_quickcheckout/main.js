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

    //allows for the qc object to trigger and listen to custom events.
    riot.observable(this);

    this.action_pending = [];

    this.layout = [];

    /**
     *   createStore. Initialize your app. This will add the default value to the
     * state. Refer to Redux http://redux.js.org/docs/api/Store.html
     */
    this.createStore = function(state) {
        //console.log('Welcome to Ajax Quick Checkout.');

        this.state = Immutable.fromJS(state, function(key, value, path) {
            return Immutable.isIndexed(value) ? value.toList() : value.toOrderedMap()
        });

        //caching the state inscrease render speed.
        this.stateCached = this.state.toJS();

        //initial state
        this.stateOld = this.stateCached;

        this.beforeLeave();
        //allows the qc.init(store) to be passed into the mixins model value.
        return this;
    }

    /**
     *   UpdateState. A wrapper function to update the state and call riot update.
     */
    this.updateState = function(key, data) {
        this.state = this.state.setIn(key, data);

        //update state cache.
        this.stateCached = this.state.toJS();
        this.stateCached.edited = true;
        setTimeout(function() {
            riot.update(); //will start a full update of all tags
        }, 10);
    }

    this.loading = function(state) {
        var edited = this.stateCached.edited;
        if (!state) {
            this.state = this.state.setIn(['loading'], state);
        }
        //REFACTOR - change to loader
        this.state = this.state.setIn(['session', 'confirm', 'loading'], state);
        this.stateCached = this.state.toJS();
        this.stateCached.edited = edited;
        setTimeout(function() {
            riot.update(); //will start a full update of all tags
        }, 10);
    }

    this.render = function() {
        riot.update(); //will start a full update of all tags
    }

    this.setState = function(data) {

        this.state = this.state.mergeDeep(data);

        //update state cache.
        this.stateCached = this.state.toJS();
        this.stateCached.edited = true;
        setTimeout(function() {
            riot.update(); //will start a full update of all tags
        }, 10);
    }

    /**
     *   GetState. Returns the state.
     */
    this.getState = function() {
        return this.stateCached;
    }

    /**
     *   Config. Ajax Quick Checkout shortcut to the config for the current
     *   Account option.
     */
    this.getConfig = function() {
        return this.stateCached.config[this.getAccount()];
    }

    /**
     *   Session. Ajax Quick Checkout shortcut to the session for the current
     *   Account option.
     */
    this.getSession = function() {
        return this.stateCached.session;
    }

    /**
     *   Layout. Ajax Quick Checkout shortcut to the session for the current
     *   Account option.
     */
    this.getLayout = function() {

        return this.stateCached.layout;
    }


    this.getAccount = function() {
        return this.stateCached.session.account;
    }

    this.getLanguage = function() {
        return this.stateCached.language;
    }



    /**
     *   Error. Ajax Quick Checkout shortcut to the error
     */
    this.getError = function() {
        if (!this.stateCached.errors) {
            this.stateCached.errors = {};
        }
        return this.stateCached.errors;
    }

    this.getChange = function() {
        return getDiff(this.stateOld, this.stateCached);
    }

    this.setChange = function(state) {
            this.stateOld = state;
        }
        /**
         *   Redux:dispatch function. A wrapper function for triggering a custom event with a
         * updated state value.
         */
    this.dispatch = function(action, state) {
        this.trigger(action, state);
    }

    /**
     *   Redux:subscribe function. A wrapper function for catching a custom event with a
     * callback function.
     */
    this.subscribe = function(action, callback) {
        this.on(action, callback);
    }

    this.rand = function() {
        return Math.random().toString(36).substring(2, 9);
    }

    this.stripTags = function(text) {
        if (text) {
            return text.replace(/<\/?[^>]+(>|$)/g, "");
        }
    }

    this.isEmpty = function(obj) {
        if (typeof(obj) == 'undefined') {
            return true;
        }
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop))
                return false;
        }
        return JSON.stringify(obj) === JSON.stringify({});
    }

    this.sortItems = function(items) {
        var items_sorted = [];

        for (var key in items) items_sorted.push(items[key]);
        items_sorted.sort(function(a, b) { return a.sort_order - b.sort_order });

        items_sorted = items_sorted.map(function(item) {
            return item.id;
        });
        return items_sorted;
    }

    this.countItems = function(items) {
        return Object.keys(items).length;
    }

    this.setLayoutAction = function(action, data) {
        var state = this.getState();
        data.page_id = state.session['page_id'];
        this.action_pending.push({ action: action, data: data });
    }

    this.updateLayout = function() {
        var state = this.getState();
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
    }

    // recursive collection function
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
    }


    this.unflattenLayout = function(arr, parent_id) {
        var nodes = [];
        nodes = $.map(arr, function(value, index) {
            if (value.type != 'item') {
                value.children = [];
            }
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

        var result = $.map(nodes, function(value, index) {
            if (value.parent == parent_id) {
                return value;
            }

        });

        return this.toObject(result);
    }

    this.toObject = function(array) {
        var new_array = {};
        if (Array.isArray(array)) {
            for (var i = 0, len = array.length; i < len; i++) {
                new_array[array[i].id] = $.extend(true, {}, array[i]);
                if (new_array[array[i].id].children != 'undefined') {
                    new_array[array[i].id].children = this.toObject(new_array[array[i].id].children)
                }
            }
        }
        return new_array;
    }

    this.raw = function(text) {
        var txt = document.createElement("textarea");
        txt.innerHTML = text;
        return txt.value;
    }

    /**
     *   Call to server for update
     *
     */

    this.beforeLeave = function() {
        if (this.stateCached.edit) {
            $(window).bind('beforeunload', function() {
                if (this.stateCached.edited) {
                    return true;
                }
            }.bind(this));
        }

        $(window).bind('beforeunload', function() {
            this.loading(true);
        }.bind(this));
    }

    this.send = function(route, data, callback) {
        this.showLoader();
        //clear notifications
        this.updateState(['notifications'], {});
        $.post('index.php?route=' + route, data, function(json, status) {
            try {
                callback(json);
            } catch (err) {
                console.error("Ajax Quick Checkout: POST /route=" + route + " returned error: \n" + json);
            }
            this.hideLoader();
        }.bind(this));
    }

    this.showLoader = function() {
        this.loading(true);
        var that = this;
        setTimeout(function() {
            $('.qc-loader').show();
            setTimeout(function() {
                that.loading(false);
                $('.qc-loader').hide();
            }, 10000);
        }, 10);

    }

    this.hideLoader = function() {
        this.loading(false);
        $('.qc-loader').hide();
    }

    this.showSpinner = function() {
        setTimeout(function() {
            $('.qc-spinner').show();
        }, 10);
    }

    this.hideSpinner = function() {
        $('.qc-spinner').hide();
    }

    this.isMobile = function() {
        if (screen.width <= 480) {
            return true;
        } else false;
    }


    // this returns the object that can therefore be extended
    return this;
})();


/**
 *  Alias for d_quickcheckout
 */
var d_quickcheckout = qc;

$.fn.btnBootstrap = $.fn.button.noConflict();