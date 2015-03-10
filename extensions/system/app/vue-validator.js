(function () {

    var install = function (Vue) {

        var _ = Vue.util;

        Vue.prototype.$validator = {

            elements: [],

            validators: {},

            bind: function (directive) {

                var self = this;

                if (!this.vm) {
                    this.vm = directive.vm;
                }

                if (!this.validators[directive.form]) {
                    this.validators[directive.form] = [];
                    _.nextTick(function () { self.validate(directive.form); });
                }

                if (this.elements.indexOf(directive.el) == -1) {
                    this.elements.push(directive.el);
                    _.on(directive.el, 'blur', directive.listener);
                    _.on(directive.el, 'input', directive.listener);
                }

                this.validators[directive.form].push(directive);
            },

            unbind: function (directive) {

                var validators = this.validators[directive.form];

                validators = validators.splice(validators.indexOf(this), 1);
            },

            validate: function (form) {

                var results = {}, result, name, keys;

                this.validators[form].forEach(function (directive) {

                    name   = directive.attr('name');
                    result = directive.validate();

                    if (!directive.touched) {
                        return;
                    }

                    if (!results[name]) {
                        results[name] = {
                            valid: true,
                            invalid: false,
                            touched: directive.touched,
                            dirty: directive.dirty
                        };
                    }

                    results[name][directive.type] = result;

                    if (results[name].valid && result) {
                        results[name].valid = results.valid = false;
                        results[name].invalid = results.invalid = true;
                    }

                });

                keys = Object.keys(results);

                if (keys.length && keys.indexOf('valid') == -1) {
                    results.valid = true;
                    results.invalid = false;
                }

                this.vm.$set(form, results);
            }

        };

        Vue.directive('valid', {

            bind: function () {

                var self = this, form = this.el.form;

                if (!form) {
                    return;
                }

                this.form    = form.getAttribute('name');
                this.type    = this.arg || this.expression;
                this.args    = this.arg ? this.expression : '';
                this.value   = this.el.value;
                this.dirty   = false;
                this.touched = false;

                this.listener = function (e) {

                    if (e.type == 'blur') {
                        self.touched = true;
                    }

                    if (self.el.value != self.value) {
                        self.dirty = true;
                    }

                    self.vm.$validator.validate(self.form);
                };

                this.vm.$validator.bind(this);
            },

            unbind: function () {
                if (this.form) {
                    this.vm.$validator.unbind(this);
                }
            },

            attr: function (name) {
                return this.el.getAttribute(name);
            },

            validate: function () {

                var validator = Vue.validators[this.type];

                return validator ? !validator(this.el.value, this.args) : undefined;
            }

        });

        Vue.validators = {
            required: function (value) {
                if(typeof value == 'boolean') return value;
                return !((value == null) || (value.length == 0));
            },
            numeric: function (value) {
                return (/^\s*(\-|\+)?(\d+|(\d*(\.\d*)))\s*$/).test(value);
            },
            integer: function (value) {
                return (/^(-?[1-9]\d*|0)$/).test(value);
            },
            digits: function (value) {
                return (/^[\d() \.\:\-\+#]+$/).test(value);
            },
            alpha: function (value) {
                return (/^[a-zA-Z]+$/).test(value);
            },
            alphaNum: function (value) {
                return !(/\W/).test(value);
            },
            email: function (value) {
                return (/^[a-z0-9!#$%&'*+\/=?^_`{|}~.-]+@[a-z0-9]([a-z0-9-]*[a-z0-9])?(\.[a-z0-9]([a-z0-9-]*[a-z0-9])?)*$/i).test(value);
            },
            url: function (value) {
                return (/^(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?$/).test(value);
            },
            minLength: function (value, arg) {
                return value && value.length && value.length >= +arg;
            },
            maxLength: function (value, arg) {
                return value && value.length && value.length <= +arg;
            },
            length: function (value) {
                return value && value.length == +arg;
            },
            min: function (value, arg) {
                return value >= +arg;
            },
            max: function (value, arg) {
                return value <= +arg;
            },
            pattern: function (value, arg) {
                var match = arg.match(new RegExp('^/(.*?)/([gimy]*)$'));
                var regex = new RegExp(match[1], match[2]);
                return regex.test(value);
            }
        };

    };

    if (typeof exports == 'object') {
        module.exports = install;
    } else if (typeof define == 'function' && define.amd) {
        define([], function (){ return install; });
    } else if (window.Vue) {
        Vue.use(install);
    }

})();