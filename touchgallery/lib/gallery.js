(function($) {

    /**
     * TouchGallery constructor
     * @param Object options A hash map of options passed to initialise the component
     */
    function TouchGallery(options) {
        this.container = $(options.container);
        this.items     = options.items;

        this.readyHandler = (options.readyHandler) ? options.readyHandler : function() {};
        
        if (options.template) this.template = options.template;

        // set the initial values
        this.position        = null;
        this.initialPosition = null;
        this.targetPosition  = null;
        this.currentItem     = null;
        this.list            = null;
        this.lastTickTime    = null;
        this.easeFactor      = 0.15; // arbitrary easing factor (0-1], higher to make things snap faster
        this.interacting     = false;
        this.touchCoords     = null;
        this.touchId         = null;
        this.tapCandidate    = null;
        this.videoCounter    = 0;
        this.autoplayTimer   = null;

        // configuration
        this.swipeLength      = 0.15; // swipe length must cross at least 15% of minimum screen dimension
        this.autoplayInterval = 3000; // the autoadvance interval, in milliseconds

        // bind event handlers' context to this component instance
        this.constructor.boundHandlers.forEach(function(name) {
            this[name] = bind(this, this[name]);
        }, this);

        // hook up events
        window.addEventListener('resize', function() { viewporter.refresh(); });
        window.addEventListener('orientationchange', function() { viewporter.refresh(); });
        window.addEventListener('viewportchange', this.handleResize);

        // start preloading images before initialising structure
        // to allow measuring images sizes before initial display
        this.preloadImages(this.init);
    }

    TouchGallery.boundHandlers = [
        'handleTouchStart', 'handleTouchMove', 'handleTouchEnd',
        'handleControlButtonClick',
        'handleResize', 'handleTap',
        'repositionImages',
        'init', 'tick'
    ];

    TouchGallery.prototype.template =
        '<div class="touch-gallery">' +
            '<ul class="items"></ul>' +
            '<div class="top-bar">' +
                '<a class="logo" href="#">Logo</a>' +
                '<div class="controls">' +
                    '<a class="prev">&laquo;</a>' +
                    '<a class="togglePlay">&#9658;</a>' +
                    '<a class="next">&raquo;</a>' +
                '</div>' +
            '</div>' +
            '<div class="bottom-bar"></div>' +
        '</div>';

    TouchGallery.prototype.preloadImages = function(callback) {
        var waitingToLoad = 0;

        this.items = this.items.filter(function(item) {
            switch(item.type) {
                case 'image':
                    item.img        = new Image;
                    item.img.onload = done;
                    item.img.src    = item.src;
                    waitingToLoad++;
                    return true;
                case 'youtube':
                    item.img        = new Image;
                    item.img.onload = done;
                    item.img.src    = 'http://img.youtube.com/vi/' + item.id + '/hqdefault.jpg';
                    waitingToLoad++;
                    return true;
                case 'vimeo':
                    $.getJSON('http://vimeo.com/api/v2/video/' + item.id + '.json?callback=?', function(data) {
                        item.img        = new Image;
                        item.img.onload = done;
                        item.img.src    = data[0].thumbnail_large;
                    });
                    waitingToLoad++;    
                    return true;
                case 'video':
                    item.img = new Image;
                    item.img.onload = done;
                    item.img.src = item.poster;
                    waitingToLoad++;
                    return true;
            }
            return false;
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
            var listItem = $('<li></li>');
            if (item.type == 'image')
                listItem.append(this.createImage(item));
            if (item.type == 'youtube')
                listItem.append(this.createYouTubeVideo(item)).addClass('video');
            if (item.type == 'vimeo')
                listItem.append(this.createVimeoVideo(item)).addClass('video');
            if (item.type == 'video')
                listItem.append(this.createVideoPlayer(item)).addClass('video');
            item.listItem = listItem;
            this.list.append(listItem);
        }, this);

        this.list.get(0).addEventListener('touchstart', this.handleTouchStart);
        this.list.get(0).addEventListener('touchmove', this.handleTouchMove);
        this.list.get(0).addEventListener('touchend', this.handleTouchEnd);

        var controls = this.container.find('.top-bar .controls');
        controls.on('click', 'a', this.handleControlButtonClick);

        this.repositionImages();
        this.moveTo(0, true);

        this.readyHandler();
    };


    /**
     * Creates a simple image in the gallery
     * @param  {Object} item 
     * @return {jQuery}      A jQuery-wrapped DOM element
     */
    TouchGallery.prototype.createImage = function(item) {
        return $('<img src="' + item.src + '" alt=""/>');
    };

    TouchGallery.prototype.createSlidingImage = function(src) {
        return $(
            '<div class="top"><img src="' + src + '" alt=""/></div>' +
            '<div class="bottom"><img src="' + src + '" alt=""/></div>'
        );
    };

    /**
     * Creates a YouTube item in the gallery
     * @param  {Object} item 
     * @return {jQuery}      A jQuery-wrapped DOM element
     */
    TouchGallery.prototype.createYouTubeVideo = function(item) {
        var id = 'youtube-' + (this.videoCounter++),
            playerContainer = $('<div></div>').attr('id', id);

        playerContainer.append(this.createSlidingImage(item.img.src)).append('<div class="play-icon"></div>');
        item.playerContainer = playerContainer;

        return playerContainer;
    };

    /**
     * Creates a Vimeo item in the gallery
     * @param  {Object} item 
     * @return {jQuery}      A jQuery-wrapped DOM element
     */
    TouchGallery.prototype.createVimeoVideo = function(item) {
        var id = 'vimeo-' + (this.videoCounter++),
            playerContainer = $('<div></div>').attr('id', id);

        playerContainer.append(this.createSlidingImage(item.img.src)).append('<div class="play-icon"></div>');
        item.playerContainer = playerContainer;

        return playerContainer;
    };

    /**
     * Creates a generic video player item in the gallery
     * @param  {Object} item 
     * @return {jQuery}      A jQuery-wrapped DOM element
     */
    TouchGallery.prototype.createVideoPlayer = function(item) {
        var id = 'video-' + (this.videoCounter++),
            playerContainer = $('<div></div>').attr('id', id);

        playerContainer.append(this.createSlidingImage(item.img.src)).append('<div class="play-icon"></div>');
        item.playerContainer = playerContainer;

        return playerContainer;
    };

    /**
     * Loads the player for YouTube items
     * @param  {Object} item The item being activated
     */
    TouchGallery.prototype.activateYouTubePlayer = function(item) {
        this.stopAutoplay();
        this.hideBars();
        item.player = new YT.Player(item.playerContainer.get(0), {
            width   : this.list.width(),
            height  : this.list.height(),
            videoId : item.id,
            events  : {
                onReady: function() { console.log(arguments); },
                onStateChange: function() { console.log(arguments); }
            }
        });

        var self = this;
        $('<div class="close-button">×</div>').appendTo(this.container.find('.touch-gallery')).click(function(ev) {
            ev.preventDefault();
            self.destroyYouTubePlayer(item);
        });
    };

    /**
     * Destroys the selected YouTube player instance
     * @param  {Object} item
     */
    TouchGallery.prototype.destroyYouTubePlayer = function(item) {
        item.player.destroy();
        item.listItem.children().remove().end().append(this.createYouTubeVideo(item));
        this.container.find('.close-button').remove();
        this.repositionImages();
        this.showBars();
    };

    /**
     * Loads up the Vimeo player for the item
     * @param  {Object} item The item being activated
     */
    TouchGallery.prototype.activateVimeoPlayer = function(item) {
        this.stopAutoplay();
        this.hideBars();
        item.playerContainer.children().remove();
        var iframe = $('<iframe></iframe>').attr({
            width                 : this.list.width(),
            height                : this.list.height(),
            src                   : 'http://player.vimeo.com/video/' + item.id + '?api=1&player_id=' + item.playerContainer.attr('id'),
            frameborder           : '0',
            webkitAllowFullScreen : 'yes',
            mozallowfullscreen    : 'yes',
            allowFullScreen       : 'yes'
        }).appendTo(item.playerContainer);
        item.player = new VimeoCommunicator(iframe[0]);
        item.player.on('received', function(data) { console.warn(data); });

        var self = this;
        $('<div class="close-button">×</div>').appendTo(this.container.find('.touch-gallery')).click(function(ev) {
            ev.preventDefault();
            self.destroyVimeoPlayer(item);
        });
    };

    TouchGallery.prototype.destroyVimeoPlayer = function(item) {
        item.player = null;
        item.listItem.children().remove().end().append(this.createVimeoVideo(item));
        this.container.find('.close-button').remove();
        this.repositionImages();
        this.showBars();
    };

    /**
     * Repositions image after viewport parameters change (used for horizontal and vertical alignment)
     */
    TouchGallery.prototype.repositionImages = function() {
        var self   = this,
            width  = this.list.width(),
            height = this.list.height();
            ratio  = width / height;
        this.list.children().each(function(idx) {
            var $li = $(this);

            // position the list element correctly
            $li.css('margin-left', idx * 100 + '%');

            var img      = $li.find('img'),
                imgRatio = img[0].naturalWidth / img[0].naturalHeight;

            if ($li.is('.video')) {

                img.css({
                    width      : '100%',
                    height     : 'auto',
                    marginLeft : 0
                })

                img.first().css({
                    bottom: -width / imgRatio / 2 + 'px'
                });

                img.last().css({
                    top: -width / imgRatio / 2 + 'px'
                });

            } else {

                // size the image to fit the viewport, upscaling if necessary
                if (imgRatio > ratio) {
                    img.css({
                        marginLeft : 0,
                        marginTop  : (height - (width / imgRatio)) / 2 + 'px',
                        width      : '100%',
                        height     : 'auto'
                    });
                } else {
                    img.css({
                        marginLeft : (width - (height * imgRatio)) / 2 + 'px',
                        marginTop  : 0,
                        width      : 'auto',
                        height     : '100%'
                    });
                }
            }

            /*
            var iframe = $(this).find('iframe');
            if (iframe.length) {
                iframe.attr({
                    width  : self.list.width(),
                    height : self.list.height()
                });
            }
            */
        });
        this.moveTo(this.currentItem, true);

        // restore visibility after layout is complete (assuming it was hidden during orientationChange)
        setTimeout(function() { self.list.css('opacity', 1); }, 0);
    };

    /**
     * (Internal) Snaps to the nearest image
     */
    TouchGallery.prototype.snap = function() {
        var width = this.list.width(),
            left  = width * Math.floor(this.position / width),
            right = width * Math.ceil(this.position / width);

        if (this.position - left < right - this.position)
            this.targetPosition = left;
        else
            this.targetPosition = right;

        this.targetPosition = clamp(this.targetPosition, 0, width * (this.items.length - 1));
        this.currentItem = Math.floor(this.targetPosition / width);
        this.tick();
    };

    /**
     * Moves the gallery to the specified item (zero-indexed)
     * @param  {Number} idx               The index of the item to move to
     * @param  {bool}   withoutTransition If it evaluates to true, does not transition
     */
    TouchGallery.prototype.moveTo = function(idx, withoutTransition) {
        this.currentItem = clamp(idx, 0, this.items.length - 1);
        if (withoutTransition) {
            this.targetPosition = this.position = this.getPositionForIndex(this.currentItem);
            this.draw();
        } else {
            this.targetPosition = this.getPositionForIndex(this.currentItem);
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

    /**
     * Returns a position for the given item index
     * @param  {Number} idx The item index (zero-based)
     * @return {Number}
     */
    TouchGallery.prototype.getPositionForIndex = function(idx) {
        return idx * this.list.width();
    };



    /**
     * The main animation loop (runs every time requestAnimationFrame is fired)
     */
    TouchGallery.prototype.tick = function() {
        // only register for the next rAF event if there's anything to animate
        if (Math.abs(this.position - this.targetPosition) > 1 || this.interacting)
            requestAnimationFrame(this.tick);

        if (!this.lastTickTime) this.lastTickTime = +new Date;
        var elapsed = new Date - this.lastTickTime;

        if (!this.interacting) this.update(elapsed);
        this.draw();
        this.lastTickTime = +new Date;
    };

    /**
     * Update the parameters inside the animation loop
     * @param  {Number} elapsed The number of milliseconds elapsed since last update
     */
    TouchGallery.prototype.update = function(elapsed) {
        this.position += (this.targetPosition - this.position) * this.easeFactor;

        // snap into position if close enough to final position
        if (Math.abs(this.position - this.targetPosition) <= 1)
            this.position = this.targetPosition;
    };

    TouchGallery.prototype.draw = function() {
        this.list.get(0).style.webkitTransform = 'translate(' + (-this.position) + 'px, 0)';
        this.list.get(0).style.transform = 'translate(' + (-this.position) + 'px, 0)';
    };


    TouchGallery.prototype.handleTouchStart = function(ev) {
        this.stopAutoplay();
        var touch = this.interacting ? this._findTouch(ev.changedTouches) : ev.changedTouches[0];
        if (touch) {
            ev.preventDefault();
            if (!this.interacting) {
                this.touchId         = touch.identifier;
                this.touchCoords     = { x: touch.pageX, y: touch.pageY };
                this.touchStartTime  = new Date;
                this.initialPosition = this.position;
                this.tapCandidate    = true;
            }
            this.interacting = true;
            this.tick();
        }
    };

    TouchGallery.prototype.handleTouchMove = function(ev) {
        var touch = this._findTouch(ev.changedTouches);
        if (touch) {
            ev.preventDefault();
            var width = this.list.width() * (this.items.length - 1);
            this.position = this.initialPosition + this.touchCoords.x - touch.pageX;

            // this creates a rubber-banding effect when reaching the edges of the gallery
            if (this.position < 0)
                this.position /= 6;
            if (this.position > width)
                this.position = width + (this.position % width) / 6;

            // if the touch moves more than 30px (30*30=900) or lasts more than 400ms don't
            // trigger a tap event
            if (this.tapCandidate) {
                var d = Math.pow(this.touchCoords.x - touch.pageX, 2) + Math.pow(this.touchCoords.y - touch.pageY, 2);
                this.tapCandidate = d < 900 && (new Date - this.touchStartTime) < 400;
            }

            this.targetPosition = this.position;
        }
    };

    TouchGallery.prototype.handleTouchEnd = function(ev) {
        ev.preventDefault();
        this.touchId      = null;
        this.touchCoords  = null;
        this.interacting  = false;
        var diff = this.targetPosition - this.initialPosition;
        if (Math.abs(diff) / Math.min(this.list.width(), this.list.height()) > this.swipeLength) {
            if (diff > 0) this.goToNext(); else this.goToPrevious();
        } else {
            this.snap();
            if (this.tapCandidate) {
                this.handleTap();
                this.tapCandidate = false;
            }
        }
    };

    TouchGallery.prototype.handleControlButtonClick = function(ev) {
        ev.preventDefault();
        if ($(ev.target).hasClass('prev'))
            this.goToPrevious();
        if ($(ev.target).hasClass('next'))
            this.goToNext();
        if ($(ev.target).hasClass('togglePlay')) {
            if (this.autoplayTimer) this.stopAutoplay(); else this.startAutoplay();
        }
    };

    TouchGallery.prototype.handleTap = function() {
        var item = this.items[this.currentItem];
        if (item.type == 'youtube')
            this.activateYouTubePlayer(item);
        else if (item.type == 'vimeo')
            this.activateVimeoPlayer(item);
        else if (item.type == 'video') {
            item.playerContainer.toggleClass('slide-out');
        }
    };

    /**
     * (Internal) Extracts the relevant touch from the TouchEvent object
     * @param  {TouchList} touchList
     * @return {Touch}               The matching touch object or null if not found
     */
    TouchGallery.prototype._findTouch = function(touchList) {
        for (var i = 0; i < touchList.length; i++)
            if (touchList[i].identifier == this.touchId)
                return touchList[i];
        return null;
    };

    /**
     * Handles viewport resize events
     */
    TouchGallery.prototype.handleResize = function() {
        // when the phone is turned we need to stop autoplay to avoid edge cases where
        // resizing the container breaks the scrolling
        this.stopAutoplay();
        this.repositionImages();
    };

    /**
     * Fades out the top and bottom bars
     */
    TouchGallery.prototype.hideBars = function() {
        this.container.find('.top-bar,.bottom-bar').addClass('fade-out');
    };

    /**
     * Shows the top and bottom bars
     */
    TouchGallery.prototype.showBars = function() {
        this.container.find('.top-bar,.bottom-bar').removeClass('fade-out');
    };

    TouchGallery.prototype.startAutoplay = function() {
        if (this.autoplayTimer) return;
        this.autoplayTimer = setInterval(bind(this, advance), this.autoplayInterval);

        this.container.find('.controls a.togglePlay').addClass('active');

        function advance() {
            if (this.currentItem == this.items.length - 1)
                this.stopAutoplay();
            else
                this.goToNext();
        }
    };

    TouchGallery.prototype.stopAutoplay = function() {
        if (this.autoplayTimer) clearInterval(this.autoplayTimer);
        this.autoplayTimer = null;
        this.container.find('.controls a.togglePlay').removeClass('active');
    };




    function VimeoCommunicator(iframe) {
        $.extend(this, EventEmitter);
        var self = this;
        this.ready = false;
        this.iframe = iframe;
        this.handleMessage = bind(this, this.handleMessage);
        this.handleReady = bind(this, this.handleReady);
        window.addEventListener('message', this.handleMessage, false);
        this.on('received', this.handleReady);
    }

    VimeoCommunicator.prototype.send = function(data) {
        this.iframe.contentWindow.postMessage(JSON.stringify(data), this.iframe.src.split('?')[0]);
    };

    VimeoCommunicator.prototype.handleMessage = function(ev) {
        this.emit('received', JSON.parse(ev.data));
    };

    VimeoCommunicator.prototype.handleReady = function(data) {
        if (!this.ready && data.event == 'ready') {
            this.ready = true;
            this.send({method: 'addEventListener', value: 'play' });
            this.send({method: 'addEventListener', value: 'pause' });
            this.send({method: 'addEventListener', value: 'playProgress' });
        }
        this.off('received', this.handleReady);
    };


    // export the class to the global namespace
    this.TouchGallery = TouchGallery;

})(Zepto);

