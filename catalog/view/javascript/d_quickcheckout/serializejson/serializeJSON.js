serializeJSONHandler = function (element, options = undefined) {
    this.splitInputNameIntoKeysArray = function(nameWithNoType) {
      var keys;
      keys = nameWithNoType.split('[');
      keys = keys.map(function(key) {
          return key.replace(/\]/g, '');
      });
      if (keys[0] === '') { keys.shift(); }
      return keys;
    };
    this.optionKeys = function(obj) { if (Object.keys) { return Object.keys(obj); } else { var key, keys = []; for(key in obj){ keys.push(key); } return keys;} };
    this.validateType = function(name, type, opts) {
      var validTypes;
      validTypes = this.optionKeys(opts ? opts.typeFunctions : this.defaultOptions.defaultTypes);
      if (!type || validTypes.indexOf(type) !== -1) {
        return true;
      } else {
        throw new Error("serializeJSON ERROR: Invalid type " + type + " found in input name '" + name + "', please use one of " + validTypes.join(', '));
      }
    };
    this.readCheckboxUncheckedValues = function (formAsArray, opts, form) {
      if (HTMLCollection.prototype.isPrototypeOf(form) || Array.isArray(form)) {
        Array.from(form).forEach(function(target) {
          var selector, uncheckedCheckboxes, dataUncheckedValue;
          if (opts == null) { opts = {}; }

          selector = 'input[type=checkbox][name]:not(:checked):not([disabled])';
          uncheckedCheckboxes =  target.querySelectorAll(selector);

          Array.from(uncheckedCheckboxes).forEach(function (el) {
            dataUncheckedValue = el.getAttribute('data-unchecked-value');
            if(dataUncheckedValue) {
              formAsArray.push({name: el.name, value: dataUncheckedValue});
            } else {
              if (opts.checkboxUncheckedValue !== void 0) {
                formAsArray.push({name: el.name, value: opts.checkboxUncheckedValue});
              }
            }
          });
        });
      } else {
        var selector, uncheckedCheckboxes, dataUncheckedValue;
        if (opts == null) { opts = {}; }

        selector = 'input[type=checkbox][name]:not(:checked):not([disabled])';
        uncheckedCheckboxes =  form.querySelectorAll(selector);

        Array.from(uncheckedCheckboxes).forEach(function (el) {
          dataUncheckedValue = el.getAttribute('data-unchecked-value');
          if(dataUncheckedValue) {
            formAsArray.push({name: el.name, value: dataUncheckedValue});
          } else {
            if (opts.checkboxUncheckedValue !== void 0) {
              formAsArray.push({name: el.name, value: opts.checkboxUncheckedValue});
            }
          }
        });
      }
      
    };
    this.tryToFindTypeFromDataAttr = function(name, form) {
      var escapedName, selector, input, typeFromDataAttr;
      if (HTMLCollection.prototype.isPrototypeOf(form) || Array.isArray(form)) {
        Array.from(form).forEach(function(target) {
          escapedName = name.replace(/(:|\.|\[|\]|\s)/g,'\\$1');
          selector = '[name="' + escapedName + '"]';
          input = target.querySelector(selector) ? target.querySelector(selector) : target;
          if (input && input.name == escapedName) {
            typeFromDataAttr = input.getAttribute('data-value-type');
            return true;
          }
        });
      } else {
        escapedName = name.replace(/(:|\.|\[|\]|\s)/g,'\\$1');
        selector = '[name="' + escapedName + '"]';
        input = form.querySelector(selector) ? form.querySelector(selector) : form;
        if (input && input.name == escapedName) {
          typeFromDataAttr = input.getAttribute('data-value-type');
        }
      }
      
      return typeFromDataAttr || null;
    };
    this.extractTypeAndNameWithNoType = function(name) {
      var match;
      if (match = name.match(/(.*):([^:]+)$/)) {
        return {nameWithNoType: match[1], type: match[2]};
      } else {
        return {nameWithNoType: name, type: null};
      }
    };
    this.defaultOptions = {
      checkboxUncheckedValue: undefined,

      parseNumbers: false,
      parseBooleans: false,
      parseNulls: false,
      parseAll: false,
      parseWithFunction: null,

      customTypes: {},
      defaultTypes: {
        "string":  function(str) { return String(str); },
        "number":  function(str) { return Number(str); },
        "boolean": function(str) { var falses = ["false", "null", "undefined", "", "0"]; return falses.indexOf(str) === -1; },
        "null":    function(str) { var falses = ["false", "null", "undefined", "", "0"]; return falses.indexOf(str) === -1 ? str : null; },
        "array":   function(str) { return JSON.parse(str); },
        "object":  function(str) { return JSON.parse(str); },
        "auto":    function(str) { return this.parseValue(str, null, null, {parseNumbers: true, parseBooleans: true, parseNulls: true}); },
        "skip":    null
      },

      useIntKeysAsArrayIndex: false
    };

    this.setupOpts = function(options) {
      var opt, validOpts, defaultOptions, optWithDefault, parseAll;

      if (options == null) { options = {}; }
      defaultOptions = this.defaultOptions || {};

      validOpts = ['checkboxUncheckedValue', 'parseNumbers', 'parseBooleans', 'parseNulls', 'parseAll', 'parseWithFunction', 'customTypes', 'defaultTypes', 'useIntKeysAsArrayIndex']; // re-define because the user may override the defaultOptions
      for (opt in options) {
        if (validOpts.indexOf(opt) === -1) {
          throw new  Error("serializeJSON ERROR: invalid option '" + opt + "'. Please use one of " + validOpts.join(', '));
        }
      }

      optWithDefault = function(key) { return (options[key] !== false) && (options[key] !== '') && (options[key] || defaultOptions[key]); };

      parseAll = optWithDefault('parseAll');

      var typeFunctions = {};

      var defaultTypes = optWithDefault('defaultTypes');
      var customTypes = optWithDefault('customTypes');
      if (defaultTypes && customTypes) {
        for (key in defaultTypes) {
          if (customTypes[key]) {
            typeFunctions[key] = customTypes[key];
          } else {
            typeFunctions[key] = defaultTypes[key];
          }
        }
      } else {
        typeFunctions = defaultTypes;
      }
      
      return {
        checkboxUncheckedValue:    optWithDefault('checkboxUncheckedValue'),

        parseNumbers:  parseAll || optWithDefault('parseNumbers'),
        parseBooleans: parseAll || optWithDefault('parseBooleans'),
        parseNulls:    parseAll || optWithDefault('parseNulls'),
        parseWithFunction:         optWithDefault('parseWithFunction'),

        typeFunctions: typeFunctions,

        useIntKeysAsArrayIndex: optWithDefault('useIntKeysAsArrayIndex')
      };
    };

    this.parseValue = function(valStr, inputName, type, opts) {
      var parsedVal;

      var isNumeric = function(n) {
        return !!Number(n);
      };
      
      parsedVal = valStr;

      if (opts.typeFunctions && type && opts.typeFunctions[type]) {
        parsedVal = opts.typeFunctions[type](valStr);
      } else if (opts.parseNumbers  && isNumeric(valStr)) {
        parsedVal = Number(valStr);
      } else if (opts.parseBooleans && (valStr === "true" || valStr === "false")) {
        parsedVal = (valStr === "true");
      } else if (opts.parseNulls    && valStr == "null") {
        parsedVal = null;
      }
      if (opts.parseWithFunction && !type) {
        parsedVal = opts.parseWithFunction(parsedVal, inputName);
      }

      return parsedVal;
    }

    this.getValue = function (elem) {
        if (elem.nodeName.toLowerCase() == 'select') {
            var value, option, i,
					options = elem.options,
					index = elem.selectedIndex,
					one = elem.type === "select-one",
					values = one ? null : [],
					max = one ? index + 1 : options.length;

            if ( index < 0 ) {
                i = max;
            } else {
                i = one ? index : 0;
            }

            for ( ; i < max; i++ ) {
                option = options[ i ];

                if ( option.selected &&

                        !option.disabled &&
                        ( !option.parentNode.disabled ||
                        (option.parentNode.nodeName && option.parentNode.nodeName.toLowerCase() !== "optgroup" ))) {

                    value = this.getValue(option);

                    if ( one ) {
                        return value;
                    }

                    values.push( value );
                }
            }

            return values;
        } else {
            return elem.value;
        }
    };
    
    this.serializeField = function(target, options) {
        if (Array.isArray(this.getValue(target))) {
          return this.getValue(target).map(function (v) {
            return {name: target.name, value: v};
          });
        } else {
          return {name: target.name, value: this.getValue(target)};
        }
        
    };
    this.deepSet = function (o, keys, value, opts) {
        var key, nextKey, tail, lastIdx, lastVal;
        if (o === void 0) o = {};
        if (opts == null) { opts = {}; }
        
        if (!keys || keys.length === 0) { throw new Error("ArgumentError: param 'keys' expected to be an array with least one element"); }
  
        key = keys[0];
  
        if (keys.length === 1) {
          if (key === '') {
            o.push(value);
          } else {
            o[key] = value;
          }
  
        } else {
          nextKey = keys[1];
  
          if (key === '') {
            lastIdx = o.length - 1;
            lastVal = o[lastIdx];
            if (lastVal === Object(lastVal) && (lastVal[nextKey] === void 0 || keys.length > 2)) {
              key = lastIdx;
            } else {
              key = lastIdx + 1;
            }
          }
  
          if (nextKey === '') {
            if (o[key] === void 0 || !Array.isArray(o[key])) {
              o[key] = [];
            }
          } else {
            if (opts.useIntKeysAsArrayIndex && /^[0-9]+$/.test(nextKey)) {
              if (o[key] === void 0 || !Array.isArray(o[key])) {
                o[key] = [];
              }
            } else {
              if (o[key] === void 0 || o[key] !== Object(o[key])) {
                o[key] = {};
              }
            }
          }
  
          tail = keys.slice(1);
          this.deepSet(o[key], tail, value, opts);
        }
      
  
    };

    this.toJSON = function (element, options) {
      var opts, formAsArray, serializedObject, name, value, _obj, nameWithNoType, type, keys;
      opts = this.setupOpts(options);
      
      formAsArray = this.serializeArray(element, options);
      this.readCheckboxUncheckedValues(formAsArray, opts, element);
      serializedObject = {};

      var that = this;

      formAsArray.forEach(function(obj) {
        name  = obj.name;
        value = obj.value;
        _obj = that.extractTypeAndNameWithNoType(name);
        nameWithNoType = _obj.nameWithNoType;
        type = _obj.type;
        if (!type) type = that.tryToFindTypeFromDataAttr(name, element);

        that.validateType(name, type, opts);

        if (type !== 'skip') {
          keys = that.splitInputNameIntoKeysArray(nameWithNoType);
          value = that.parseValue(value, name, type, opts);
          that.deepSet(serializedObject, keys, value, opts);
        }
      });
      return serializedObject;
    };
    
    this.serializeArray = function(target, options) {
        var that = this;
        if (HTMLCollection.prototype.isPrototypeOf(target) || Array.isArray(target)) {
          var serializedArr = [];
          Array.from(target).forEach(function(element) {
            var elements = element.elements;
            serializedArr = [...serializedArr, ...(elements ? Array.from(elements) : [element])];
          });
          serializedArr = serializedArr.filter(function(el) { 
            return !!el.name && !el.disabled && !/^(?:submit|button|image|reset|file)$/.test(el.type) && (el.checked || !/^(?:checkbox|radio)$/i.test(el.type));
          }).map(function(el) {
            return that.serializeField(el, options);
          });
          return serializedArr;
        } else {
          var elements = target.elements;

          return (elements ? Array.from(elements) : [target]).filter(function(el) {   
            return el.name && !el.disabled && !/^(?:submit|button|image|reset|file)$/.test(el.type) && (el.checked || !/^(?:checkbox|radio)$/i.test(el.type));
          }).map(function(el) {
            return that.serializeField(el, options);
          });
        }
        
    };


    return this.toJSON(element, options);
};

serializeJSON = function (element, options) {
    return new serializeJSONHandler(element, options);
};