(function() {

    $.extend(Radial.prototype, EventEmitter);
    function Radial(options) {
        this.container   = options.container;
        this.value       = options.value       || 32;
        this.min         = options.min         || 0;
        this.max         = options.max         || 100;
        this.width       = options.width       || 64;
        this.height      = options.height      || 64;
        this.fill        = options.fill        || '#eee';
        this.background  = options.background  || '#555';
        this.barWidth    = options.barWidth    || 0.75;
        this.borderRatio = options.borderRatio || 0.05;

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

        // background
        ctx.fillStyle = this.background;
        ctx.beginPath();
        ctx.arc(w, h, r, 0, Math.PI * 2, true);
        ctx.fill();

        ctx.save();
        ctx.beginPath();
        ctx.arc(w, h, r * 0.75, 0, Math.PI * 2, true);
        ctx.clip();
        ctx.clearRect(0, 0, this.width, this.height);
        ctx.restore();
        
        // pie slice
        ctx.beginPath();
        ctx.fillStyle = this.fill;

        ctx.moveTo(Math.cos(zero) * r + w, Math.sin(target) * r + h);
        ctx.arc(w, h, r, zero, target, false);
        ctx.lineTo(Math.cos(target) * r * this.barWidth + w, Math.sin(target) * r * this.barWidth + h);
        ctx.arc(w, h, r * this.barWidth, target, zero, true);
        ctx.closePath();
        ctx.fill();

        // border
        ctx.strokeStyle = 'rgba(0,0,0,0.8)';
        ctx.lineWidth = r * this.borderRatio;

        ctx.beginPath();
        ctx.arc(w, h, r, 0, Math.PI * 2, true);
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(w, h, r * this.barWidth, 0, Math.PI * 2, true);
        ctx.stroke();

    };

    Radial.prototype.getAngleForValue = function(val) {
        return (clamp(val, this.min, this.max - 0.001) - this.min) / (this.max - this.min) * Math.PI * 2 - Math.PI / 2;
    };

    this.Radial = Radial;
})();