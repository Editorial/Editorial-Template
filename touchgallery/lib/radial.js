(function() {

    $.extend(Radial.prototype, EventEmitter);
    function Radial(options) {
        this.container   = options.container;
        this.value       = options.value       || 32;
        this.min         = options.min         || 0;
        this.max         = options.max         || 100;
        this.width       = options.width       || 64;
        this.height      = options.height      || 64;
        this.fill        = options.fill        || '#fff';
        this.barWidth    = options.barWidth    || 4;

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
        var ctx    = this._canvas.getContext('2d'),
            r      = Math.min(this.width / 2, this.height / 2) * 0.95,
            zero   = this.getAngleForValue(this.min),
            target = this.getAngleForValue(this.value),
            w      = this.width / 2,
            h      = this.height / 2;

        ctx.clearRect(0, 0, this.width, this.height);
        
        // pie slice
        ctx.beginPath();
        ctx.fillStyle = this.fill;
        ctx.moveTo(Math.cos(zero) * r + w, Math.sin(target) * r + h);
        ctx.arc(w, h, r, zero, target, false);
        ctx.lineTo(Math.cos(target) * (r - this.barWidth) + w, Math.sin(target) * (r - this.barWidth) + h);
        ctx.arc(w, h, (r - this.barWidth), target, zero, true);
        ctx.closePath();
        ctx.fill();

    };

    Radial.prototype.getAngleForValue = function(val) {
        return (clamp(val, this.min, this.max - 0.001) - this.min) / (this.max - this.min) * Math.PI * 2 - Math.PI / 2;
    };

    this.Radial = Radial;
})();