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

    this.createOneShotFunction = function(fn) {
        var fired = false;
        return function() {
            if (!fired) fn.apply(this, arguments);
            fired = true;
        };
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

    var ua = window.navigator.userAgent; // just so it's overridable by tests
    
    // Usage:
    //  - platform()
    //  - platform(min, max)    -- min/max are inclusive, null for unbounded
    //  - platform(exact)       -- same as platform(exact, exact)
    // args are strings
    
    function ios() {
        var m = ua.match(/iP(ad|hone|od).*OS ([0-9_]+)/);
        return !!m && versionMatches(m[2].replace(/_/g, '.'), arguments);
    }

    function android() {
        var m = ua.match(/Android ([0-9.]+)/);
        return !!m && versionMatches(m[1], arguments);
    }
    
    function versionMatches(ver, bounds) {
        var min, max;
        if (bounds.length == 0) {
            min = null;
            max = null;
        } else if (bounds.length == 1) {
            min = bounds[0];
            max = bounds[0];
        } else if (bounds.length == 2) {
            min = bounds[0];
            max = bounds[1];
        } else {
            throw 'Invalid number of arguments'
        }
        
        // Parse strings
        ver = ver.split('.');
        min = min ? min.split('.') : [];
        max = max ? max.split('.') : [];
        
        // Version should have at least as many places as min/max. If not, add zeroes.
        // (e.g. iOS 3.0 is also 3.0.0)
        for (var i=Math.max(min.length, max.length)-ver.length; i >= 0; i--)
            ver.push(0);
        
        function cmp(a, b) {
            for(var i = 0; i < Math.min(a.length, b.length); i++) {
                if(a[i] < b[i]) return -1;
                if(a[i] > b[i]) return 1;
            }
            return 0;
        }
    
        return !!(cmp(ver, min) != -1 && cmp(ver, max) != 1);
    }

    this.Browsers = {
        ios     : ios,
        android : android
    };

})();