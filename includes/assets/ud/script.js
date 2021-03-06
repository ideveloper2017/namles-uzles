(function (a) {
    a.fn.countDown = function (b) {
        config = {};
        a.extend(config, b);
        diffSecs = this.setCountDown(config);
        a("#" + a(this).attr("id") + " .digit").html('<div class="top"></div><div class="bottom"></div>');
        a(this).doCountDown(a(this).attr("id"), diffSecs, 500);
        if (config.onComplete) {
            a.data(a(this)[0], "callback", config.onComplete)
        }
        if (config.omitWeeks) {
            a.data(a(this)[0], "omitWeeks", config.omitWeeks)
        }
        return this
    };
    a.fn.stopCountDown = function () {
        clearTimeout(a.data(this[0], "timer"))
    };
    a.fn.startCountDown = function () {
        this.doCountDown(a(this).attr("id"), a.data(this[0], "diffSecs"), 500)
    };
    a.fn.setCountDown = function (b) {
        var d = new Date();
        if (b.targetDate) {
            d.setDate(b.targetDate.day);
            d.setMonth(b.targetDate.month - 1);
            d.setFullYear(b.targetDate.year);
            d.setHours(b.targetDate.hour);
            d.setMinutes(b.targetDate.min);
            d.setSeconds(b.targetDate.sec)
        } else {
            if (b.targetOffset) {
                d.setDate(b.targetOffset.day + d.getDate());
                d.setMonth(b.targetOffset.month + d.getMonth());
                d.setFullYear(b.targetOffset.year + d.getFullYear());
                d.setHours(b.targetOffset.hour + d.getHours());
                d.setMinutes(b.targetOffset.min + d.getMinutes());
                d.setSeconds(b.targetOffset.sec + d.getSeconds())
            }
        }
        var c = new Date();
        diffSecs = Math.floor((d.valueOf() - c.valueOf()) / 1000);
        a.data(this[0], "diffSecs", diffSecs);
        return diffSecs
    };
    a.fn.doCountDown = function (d, b, c) {
        $this = a("#" + d);
        if (b <= 0) {
            b = 0;
            if (a.data($this[0], "timer")) {
                clearTimeout(a.data($this[0], "timer"))
            }
        }
        secs = b % 60;
        mins = Math.floor(b / 60) % 60;
        hours = Math.floor(b / 60 / 60) % 24;
        if (a.data($this[0], "omitWeeks") == true) {
            days = Math.floor(b / 60 / 60 / 24);
            weeks = Math.floor(b / 60 / 60 / 24 / 7)
        } else {
            days = Math.floor(b / 60 / 60 / 24) % 7;
            weeks = Math.floor(b / 60 / 60 / 24 / 7)
        }
        $this.dashChangeTo(d, "seconds_dash", secs, c ? c : 10);
        $this.dashChangeTo(d, "minutes_dash", mins, c ? c : 10);
        $this.dashChangeTo(d, "hours_dash", hours, c ? c : 10);
        $this.dashChangeTo(d, "days_dash", days, c ? c : 20);
        $this.dashChangeTo(d, "weeks_dash", weeks, c ? c : 20);
        a.data($this[0], "diffSecs", b);
        if (b > 0) {
            e = $this;
            t = setTimeout(function () {
                e.doCountDown(d, b - 1)
            }, 1000);
            a.data(e[0], "timer", t)
        } else {
            if (cb = a.data($this[0], "callback")) {
                a.data($this[0], "callback")()
            }
        }
    };
    a.fn.dashChangeTo = function (f, c, d, b) {
        $this = a("#" + f);
        d2 = d % 10;
        d1 = (d - d % 10) / 10;
        if (a("#" + $this.attr("id") + " ." + c)) {
            $this.digitChangeTo("#" + $this.attr("id") + " ." + c + " .digit:first", d1, b);
            $this.digitChangeTo("#" + $this.attr("id") + " ." + c + " .digit:last", d2, b)
        }
    };
    a.fn.digitChangeTo = function (d, c, b) {
        if (!b) {
            b = 200
        }
        if (a(d + " div.top").html() != c + "") {
            a(d + " div.top").css({display: "none"});
            a(d + " div.top").html((c ? c : "0")).slideDown(b);
            a(d + " div.bottom").animate({height: ""}, b, function () {
                a(d + " div.bottom").html(a(d + " div.top").html());
                a(d + " div.bottom").css({display: "block", height: ""});
                a(d + " div.top").hide().slideUp(10)
            })
        }
    }
})(jQuery);