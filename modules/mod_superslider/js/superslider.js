function SuperSlider(slider_id, options){

    this.id = slider_id;
    this.options = options;
    this.slidesCount = options.slides;
    this.currentSlide = 1;
    this.isAnimatedNow = false;
    this.isAutoPlay = options.auto;
    this.isPaused = false;
    this.direction = options.direction;
    this.moveOffset = this.direction == 'left' ?  this.options.width : this.options.height;

    this.getContainer = function(){
        return $('#superslider-' + this.id);
    }

    this.start = function(){

        if (this.slidesCount <= 1){ return false; }

        var slider = this;
        
        if (this.direction == 'left'){
            var nav_prev_class = '.nav-left';
            var nav_next_class = '.nav-right';
        } else {
            var nav_prev_class = '.nav-up';
            var nav_next_class = '.nav-down';            
        }

        $(nav_prev_class, this.getContainer()).click(function(){
            if (slider.isAnimatedNow) { return false; }
            slider.isPaused = true;
            slider.prev();
            return false;
        })

        $(nav_next_class, this.getContainer()).click(function(){
            if (slider.isAnimatedNow) { return false; }
            slider.isPaused = true;
            slider.next();
            return false;
        })

        $('.dots-nav .dot', this.getContainer()).click(function(){
            if (slider.isAnimatedNow) { return false; }
            slider.isPaused = true;
            slider.showSlide($(this).data('id'), 'auto');
            return false;
        })

        if (this.isAutoPlay){
            setTimeout('slider'+this.id+'.autoSlide()', this.options.delay);
        }

    }

    this.autoSlide = function(){

        if (this.isPaused) { return false; }
        this.next();

    }

    this.markDot = function (index){
        $('.dots-nav .dot', this.getContainer()).removeClass('active');
        $('.dots-nav .dot', this.getContainer()).eq(index-1).addClass('active');
    }

    this.next = function(){
        if (this.currentSlide < this.slidesCount){
            this.showSlide(this.currentSlide + 1, 'next');
        } else {
            this.showSlide(1, 'next');
        }
    }

    this.prev = function(){
        if (this.currentSlide > 1){
            this.showSlide(this.currentSlide - 1, 'prev');
        } else {
            this.showSlide(this.slidesCount, 'prev');
        }
    }

    this.showSlide = function (index, direction){

        if (index == this.currentSlide) { return false; }

        if ((index > this.currentSlide  && direction=='auto') || direction=='next') {
            $('.slider .slide-' + index, this.getContainer())
                .insertAfter($('.slider .slide', this.getContainer()).eq(0));
            this.showNextSlide();
        }

        if ((index < this.currentSlide  && direction=='auto') || direction=='prev') {
            $('.slider .slide-' + index, this.getContainer())
                .prependTo('.slider', this.getContainer())
                .css(this.direction, -this.moveOffset);
            this.showPrevSlide();
            this.currentSlide = index;
        }

        this.currentSlide = index;
        this.markDot(this.currentSlide);
        return;

    }

    this.showNextSlide = function(){

        if (this.isAnimatedNow) { return false; }

        var slider = this;

        var anim_opts = {};
        anim_opts[this.direction] = '-=' + this.moveOffset;

        this.isAnimatedNow = true;

        $('.slider .slide', this.getContainer()).eq(1).animate(anim_opts, this.options.speed);

        $('.slider .slide', this.getContainer()).eq(0).animate(anim_opts, this.options.speed+5, function(){

            slider.isAnimatedNow = false;

            $('.slider .slide', slider.getContainer()).eq(0).appendTo('.slider', slider.getContainer());
            $('.slider .slide', slider.getContainer()).css(slider.direction, 'auto');

            if (slider.isAutoPlay){
                setTimeout('slider'+slider.id+'.autoSlide()', slider.options.delay);
            }

        });

    }

    this.showPrevSlide = function(){

        if (this.isAnimatedNow) { return false; }

        var slider = this;

        var anim_opts = {};
        anim_opts[this.direction] = '+=' + this.moveOffset;

        this.isAnimatedNow = true;

        $('.slider .slide', this.getContainer()).eq(1)
            .css(slider.direction, -this.moveOffset);

        $('.slider .slide', this.getContainer()).eq(0).animate(anim_opts, this.options.speed);

        $('.slider .slide', this.getContainer()).eq(1).animate(anim_opts, this.options.speed+5, function(){

            $('.slider .slide', slider.getContainer()).css(slider.direction, 'auto');

            slider.isAnimatedNow = false;

        });

    }

    this.start();

}
