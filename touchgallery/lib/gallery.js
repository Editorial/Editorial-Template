(function($) {

    /**
     * TouchGallery constructor
     * @param Object options A hash map of options passed to initialise the component
     */
    function TouchGallery(options) {
        this.container = $(options.container);
        this.items     = options.items;

        // set the initial values
        this.position       = null;
        this.targetPosition = null;
        this.currentItem    = null;
        this.list           = null;
        this.lastTickTime   = null;
        this.easeFactor     = 0.15; // arbitrary easing factor (0-1], higher to make things snap faster
        this.interacting    = false;
        this.touchX         = null;
        this.touchId        = null;

        // bind event handlers' context to this component instance
        this.constructor.boundHandlers.forEach(function(name) {
            this[name] = bind(this, this[name]);
        }, this);

        // hook up events
        $(window).resize(this.handleResize);

        // start preloading images before initialising structure
        // to allow measuring images sizes before initial display
        this.preloadImages(this.init);
    }

    TouchGallery.boundHandlers = [
        'handleTouchStart', 'handleTouchMove', 'handleTouchEnd', 'handleTouchCancel', 'handleClick',
        'handleResize',
        'init', 'tick'
    ];

    TouchGallery.prototype.template =
        '<div class="touch-gallery">' +
            '<ul class="items"></ul>' +
            '<div class="top-bar"></div>' +
            '<div class="bottom-bar"></div>' +
        '</div>';

    TouchGallery.prototype.preloadImages = function(callback) {
        var waitingToLoad = this.items.length;

        this.items.forEach(function(item) {
            item.img = new Image;
            item.img.onload = done;
            item.img.src = item.src;
        });

        function done() { if (!--waitingToLoad) callback(); }
    };

    /**
     * Initialises the component structure and positions the images
     */
    TouchGallery.prototype.init = function() {
        this.container.append(this.template);
        this.list = this.container.find('.items');
        this.items.forEach(function(item) {
            var el = $('<li class="' + item.type + '"><img src="' + item.src + '"/></li>').appendTo(this.list);
        }, this);
        this.repositionImages();
        this.moveTo(0, true);
    };

    TouchGallery.prototype.repositionImages = function() {
        var self = this;
        this.list.children().each(function(idx) {
            var img = $(this).find('img');
            // if the image has a higher aspect ratio than the list container
            // we have to align it vertically
            if (img.height() < self.list.height())
                img.css('margin-top', (self.list.height() - img.height()) / 2 + 'px');
            $(this).css('margin-left', idx * self.list.width() + 'px');
        });
    };

    /**
     * (Internal) Snaps to the nearest image
     */
    TouchGallery.prototype.snap = function() {
        
    };

    /**
     * Moves the gallery to the specified item (zero-indexed)
     * @param  {Number} idx               The index of the item to move to
     * @param  {bool}   withoutTransition If it evaluates to true, does not transition
     */
    TouchGallery.prototype.moveTo = function(idx, withoutTransition) {
        this.currentItem = idx;
        if (withoutTransition) {
            this.position = this.getPositionForIndex(idx);
            this.draw();
        } else {
            this.targetPosition = this.getPositionForIndex(idx);
            this.tick();
        }
    };

    /**
     * Advances to the next item. When there are no more items, the function
     * silently ignores the command.
     */
    TouchGallery.prototype.goToNext = function() {
        if (this.currentItem < this.items.length - 1) this.moveTo(this.currentItem + 1);
    };

    /**
     * Moves the gallery back one item. If already at the first item, the
     * function silently ignores the command.
     */
    TouchGallery.prototype.goToPrevious = function() {
        if (this.currentItem > 0) this.moveTo(this.currentItem - 1);
    };

    TouchGallery.prototype.getPositionForIndex = function(idx) {
        return idx * this.list.width();
    };



    /**
     * The main animation loop 
     * @return {[type]} [description]
     */
    TouchGallery.prototype.tick = function() {
        if (Math.abs(this.position - this.targetPosition) > 1 || this.interacting)
            requestAnimationFrame(this.tick);

        if (!this.lastTickTime) this.lastTickTime = +new Date;
        var elapsed = new Date - this.lastTickTime;

        if (!this.interacting) this.update(elapsed);
        this.draw();
        this.lastTickTime = +new Date;
    };

    TouchGallery.prototype.update = function(elapsed) {
        this.position += (this.targetPosition - this.position) * this.easeFactor;

        if (Math.abs(this.position - this.targetPosition) < 1)
            this.position = this.targetPosition;
    };

    TouchGallery.prototype.draw = function() {
        this.list.get(0).style.webkitTransform = 'translate(-' + this.position + 'px, 0)';
        this.list.get(0).style.transform = 'translate(-' + this.position + 'px, 0)';
    };


    // interaction handlers
    TouchGallery.prototype.handleTouchStart = function(ev) {
        var touch = this.interacting ? this._findTouch(ev.changedTouches) : ev.changedTouches[0];
        if (touch) {
            if (!this.interacting) {
                this.touchId = touch.identifier;
                this.touchX  = touch.pageX;
            }
        }
    };

    TouchGallery.prototype.handleTouchMove = function(ev) {};
    TouchGallery.prototype.handleTouchEnd = function(ev) {};
    TouchGallery.prototype.handleTouchCancel = function(ev) {};

    TouchGallery.prototype._findTouch = function(touchList) {
        for (var i = 0; i < touchList.length; i++)
            if (touchList[i].identifier == this.touchId)
                return touchList[i];
        return null;
    };

    TouchGallery.prototype.handleResize = function() {
        this.repositionImages();
    };


    
    /**
     * Binds a function to the supplied context
     * @param  {Object}   ctx The context object
     * @param  {Function} fn  The function to bind
     * @return {Function}     The bound function
     */
    function bind(ctx, fn) {
        return function() {
            return fn.apply(ctx, arguments);
        };
    }

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
    window.EventEmitter = {
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

    $.extend(TouchGallery.prototype, EventEmitter);


    // export the class to the global namespace
    this.TouchGallery = TouchGallery;

})(jQuery);

