(function() {
    
    /**
     * Binds a function to the supplied context
     * @param  {Object}   ctx The context object
     * @param  {Function} fn  The function to bind
     * @return {Function}     The bound function
     */
    this.bind = function(ctx, fn) {
        return function() {
            return fn.apply(ctx, arguments);
        };
    };

    this.clamp = function(n, a, b) {
        return Math.max(a, Math.min(b, n));
    };

    // requestAnimationFrame implementation
    (function() {
        var lastTime = 0;
        var vendors = ['ms', 'moz', 'webkit', 'o'];
        for(var x = 0; x < vendors.length && !window.requestAnimationFrame; ++x) {
            window.requestAnimationFrame = window[vendors[x]+'RequestAnimationFrame'];
            window.cancelAnimationFrame = 
                window[vendors[x]+'CancelAnimationFrame'] || window[vendors[x]+'CancelRequestAnimationFrame'];
        }
     
        if (!window.requestAnimationFrame)
            window.requestAnimationFrame = function(callback, element) {
                var currTime = new Date().getTime();
                var timeToCall = Math.max(0, 16 - (currTime - lastTime));
                var id = window.setTimeout(function() { callback(currTime + timeToCall); }, timeToCall);
                lastTime = currTime + timeToCall;
                return id;
            };
     
        if (!window.cancelAnimationFrame)
            window.cancelAnimationFrame = function(id) {
                clearTimeout(id);
            };
    }());

    // The EventEmitter API
    this.EventEmitter = {
        on : function(type, handler) {
            this._ensureEvent(type);
            this._events[type].push(handler);
        },
        off : function(type, handler) {
            if (this._events && this._events[type]) {
                this._events[type] = this._events[type].filter(function(h) {
                    return h !== handler;
                });
            }
        },
        emit: function(type) {
            var args = Array.prototype.slice.call(arguments, 1);
            this._ensureEvent(type);
            this._events[type].forEach(function(handler) {
                handler.apply(this, args);
            });
        },
        _ensureEvent: function(type) {
            if (!this._events) this._events = {};
            if (!this._events[type]) this._events[type] = [];
        }
    };

    // Template compiler for components supporting customisable templates
    // taken from JavaScript Ninja Manual, p. 248, modified a bit.
    // Use `<% ... %>` to execute blocks of JavaScript, `<%= ... %>` to write
    // out the result of the embedded expression.
    (function(){
        var cache = {};

        this.tmpl = function tmpl(str, data) {
            var fn = !/\W/.test(str) ?
                cache[str] = cache[str] :

                // Generate a reusable function that will serve as a template
                // generator (and which will be cached).
                new Function("obj",
                    "var p=[],print=function(){p.push.apply(p,arguments);};" +

                    // Introduce the data as local variables using with(){}
                    "with(obj){p.push('" +

                    // Convert the template into pure JavaScript
                    str
                        .replace(/[\r\t\n]/g, " ")
                        .split("<%").join("\t")
                        .replace(/((^|%>)[^\t]*)'/g, "$1\r")
                        .replace(/\t=(.*?)%>/g, "',$1,'")
                        .split("\t").join("');")
                        .split("%>").join("p.push('")
                        .split("\r").join("\\'")
                    + "');}return p.join('');");

            // Provide some basic currying to the user
            return data ? fn( data ) : fn;
        };
    })();

})();