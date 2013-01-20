(function() {

    $.extend(Radial.prototype, EventEmitter);
    function Radial(options) {
        this.container = options.container;
        this.value      = options.value      || 32;
        this.min        = options.min        || 0;
        this.max        = options.max        || 100;
        this.width      = options.width      || 64;
        this.height     = options.height     || 64;
        this.fill       = options.fill       || '#eee';
        this.background = options.background || '#555';

        this._canvas = null;

        this.render = bind(this, this.render);
    }

    Radial.prototype.init = function() {
        this._canvas = document.createElement('canvas');
        this._canvas.width  = this.width;
        this._canvas.height = this.height;
        this.container.appendChild(this._canvas);
    };

    Radial.prototype.render = function() {
        var ctx = this._canvas.getContext('2d'),
            r   = Math.min(this.width / 2, this.height / 2);

        ctx.clearRect(0, 0, this.width, this.height);
        ctx.globalCompositeOperation = 'source-over';

        // background
        ctx.fillStyle = this.background;
        ctx.beginPath();
        ctx.rect(0, 0, this.width, this.height);
        ctx.fill();
        
        // pie slice
        ctx.beginPath();
        ctx.fillStyle = this.fill;
        ctx.moveTo(this.width / 2, this.height / 2);
        ctx.lineTo(this.width / 2, this.height / 2 - r);
        ctx.arc(this.width / 2, this.height / 2, r, this.getAngleForValue(this.min), this.getAngleForValue(this.value), false);
        ctx.lineTo(this.width / 2, this.height / 2);
        ctx.closePath();
        ctx.fill();

        // border
        ctx.strokeStyle = 'black';
        ctx.strokeWidth = r * 0.05;

        ctx.beginPath();
        ctx.arc(this.width / 2, this.height / 2, r, 0, Math.PI * 2, true);
        ctx.stroke();
        ctx.beginPath();
        ctx.arc(this.width / 2, this.height / 2, r * 0.75, 0, Math.PI * 2, true);
        ctx.stroke();
        
    };

    Radial.prototype.getAngleForValue = function(val) {
        return (clamp(val, this.min, this.max) - this.min) / (this.max - this.min) * Math.PI * 2 - Math.PI / 2;
    };

    this.Radial = Radial;
})();