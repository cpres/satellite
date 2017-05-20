  /*
   * jQuery Orbit Plugin 1.3.2
   * www.ZURB.com/playground
   * Copyright 2010, ZURB
   * Free to use under the MIT license.
   * http://www.opensource.org/licenses/mit-license.php
  */


  (function($) {

    var SATLORBIT = {

      defaults: {  
        animation: 'horizontal-push', 	// fade, horizontal-slide, vertical-slide, horizontal-push, vertical-push
        animationSpeed: 600, 		// how fast animtions are
        timer: true, 			// true or false to have the timer
        advanceSpeed: 4000, 		// if timer is enabled, time between transitions 
        pauseOnHover: false, 		// if you hover pauses the slider
        startClockOnMouseOut: false, 	// if clock should start on MouseOut
        startClockOnMouseOutAfter: 1000, 	// how long after MouseOut should the timer start again
        directionalNav: true, 		// manual advancing directional navs
        captions: true, 			// do you want captions?
        captionAnimation: 'fade', 	// fade, slideOpen, none
        captionAnimationSpeed: 600, 	// if so how quickly should they animate in
        captionHover: false,
        bullets: false,			// true or false to activate the bullet navigation
        bulletThumbs: false,		// thumbnails for the bullets
        bulletThumbLocation: '',		// location from this file where thumbs will be
        afterSlideChange: $.noop,		// empty function 
        centerBullets: true,              // center bullet nav with js, turn this off if you want to position the bullet nav manually
        navOpacity: .2,
        sideThumbs: false,
        preloader: 5,
        thumbWidth: 80,
        respExtra: 0,
        alwaysPlayBtn: false
      },

      activeSlide: 0,
      numberSlides: 0,
      orbitWidth: null,
      orbitHeight: null,
      locked: null,
      timerRunning: null,
      degrees: 0,
      wrapperHTML: '<div class="satl-wrapper" />',
      wrapThumbHTML: '<div class="thumbholder" />',
      timerHTML: '<div class="timer"><span class="mask"><span class="rotator"></span></span><span class="pause"></span></div>',
      captionHTML: '<div class="orbit-caption"></div>',
      directionalNavHTML: '<div class="satl-nav"><span class="right">Right</span><span class="left">Left</span></div>',
      directionalThumbHTML: '<span id="slideleft">Left</span><span id="slideright">Right</span>',
      bulletHTML: '<ul class="orbit-bullets"></ul>',
      thumbHTML: '<ul class="orbit-thumbnails"></ul>',

      init: function (element, options) {
        var $imageSlides,
            imagesLoadedCount = 0,
            self = this;

        // Bind functions to correct context
        this.clickTimer = $.proxy(this.clickTimer, this);
        this.addBullet = $.proxy(this.addBullet, this);
        this.resetAndUnlock = $.proxy(this.resetAndUnlock, this);
        this.stopClock = $.proxy(this.stopClock, this);
        this.startTimerAfterMouseLeave = $.proxy(this.startTimerAfterMouseLeave, this);
        this.clearCaptionAfterMouseLeave = $.proxy(this.clearCaptionAfterMouseLeave, this);
        this.setCaptionAfterMouseHover = $.proxy(this.setCaptionAfterMouseHover, this);
        this.clearClockMouseLeaveTimer = $.proxy(this.clearClockMouseLeaveTimer, this);
        this.rotateTimer = $.proxy(this.rotateTimer, this);

        this.options = $.extend({}, this.defaults, options);
        if (this.options.timer === 'false') this.options.timer = false;
        if (this.options.captions === 'false') this.options.captions = false;
        if (this.options.directionalNav === 'false') this.options.directionalNav = false;

        this.$element = $(element);
        this.$wrapper = this.$element.wrap(this.wrapperHTML).parent();
        this.$slides = this.$element.children('img, a, div');

        var imageSlides = new Array();
        var reqPreload = new Array();
        this.$slides.each(function() {
          imageSlides.push($(this).find('img').attr('src'));
        });

        if (imageSlides.length === 0) {
          self.loaded();
        } else {
          if (this.options.bulletThumbs && this.options.bullets) {
            this.$slides.each(function() {
              reqPreload.push($(this).attr('data-thumb'));
              if (reqPreload.length == 20) {
                return false;
              }
            });
          }
          var loadNumber = this.options.preloader + reqPreload.length;
          reqPreload.push.apply(reqPreload, imageSlides);
          self.preload(reqPreload, loadNumber, true);
        }
      },

      loaded: function () {
        this.$element
          .addClass('orbit')
          .css('background','none');
          //.width('1px')

        this.setDimensionsFromLargestSlide(this.options.bullets, this.options.thumbWidth);
        this.updateOptionsIfOnlyOneSlide();
        this.setupFirstSlide();
        this.setupClicks();

        if (this.options.timer) {
          this.setupTimer();
          this.startClock();        
        } else if (this.options.alwaysPlayBtn) {
          this.setupTimer();
          this.stopClock();        
        }

        this.$wrapper.hover(function() {
          $(this).toggleClass('hover')
        });

        if (this.options.captions) {
            this.setupCaptions();
        }

        if (this.options.directionalNav) {
          this.setupDirectionalNav();
        }

        if (this.options.bullets) {
          this.setupBulletNav();
          this.setActiveBullet();
        }
        if (this.options.bulletThumbs && this.options.bullets) {
            this.setupDirectionalThumb(this.options.thumbWidth);
        }
        //if (this.options.animation == "fade") {
          this.$slides.css('opacity',0);
        //}

      },

      currentSlide: function () {
        return this.$slides.eq(this.activeSlide);
      },

      setSideThumbSize: function(width,containWidth) {
        var self = this;
        if (!width) { width = self.$element.width(); }
        if (!containWidth) { containWidth = self.$wrapper.parent().parent().width(); }
        this.extraWidth = containWidth - width;
        if (this.extraWidth < this.options.thumbWidth) {
          self.$wrapper.find('.thumbholder').css('width',this.options.thumbWidth+10);
          self.$wrapper.find('.thumbholder').css('margin-left',width);
          this.extraWidth = this.options.thumbWidth;
          return;
        }
        else if (this.extraWidth < self.options.respExtra) {
          self.$wrapper.find('.thumbholder').css('width',this.extraWidth+10);
          self.$wrapper.find('.thumbholder').css('margin-left',width);
          this.$element.css('margin-left',this.extraWidth);
          this.setLeftMargin(this.extraWidth);
          return;
        }
        this.extraWidth = self.options.respExtra;
      },

      setDimensionsFromLargestSlide: function (bullet, twidth) {
        //Collect all slides and set slider size of largest image
        var self = this;
        var lastWidth = 0;
        var lastHeight = 0;
        this.$slides.each(function () {
          var slide = $(this),
              slideWidth = slide.width(),
              slideHeight = slide.height();

          if (slideWidth > lastWidth) {
              self.$element.add(self.$wrapper).width(slideWidth);
              self.$element.add(self.$wrapper).css('max-width', slideWidth);
              self.orbitWidth = self.$element.width();
              lastWidth = self.orbitWidth;
          }
          if (slideHeight > lastHeight) {
              self.$element.add(self.$wrapper).height(slideHeight);
              self.orbitHeight = self.$element.height();
              lastHeight = self.orbitHeight;
          }
          self.numberSlides += 1;
        });
        this.beResponsive(lastWidth,lastHeight);
      },

      beResponsive: function (width,height) {
        var self = this;
        var percent = 1;
        this.extraWidth = self.options.respExtra;
        var containWidth = self.$wrapper.parent().parent().width();
        var maxWidth = parseInt(self.$wrapper.css('max-width'));
        var newWidth = false;
        if (this.options.sideThumbs) {
          this.setSideThumbSize(width,containWidth);
        }
        // Zero out extra width so slideshow can start to be resized
        if (width >= containWidth || containWidth <= maxWidth) {
          this.extraWidth = 0;
        }
        if (width + this.extraWidth > containWidth || (width < maxWidth && containWidth < maxWidth)) {
            if (this.options.sideThumbs) {
              newWidth = containWidth - (self.options.thumbWidth + 5);              
            } else if (!this.extraWidth) {
              newWidth = containWidth;
            }
            if(!self.$wrapper.parent().hasClass('shrunk')) {
                self.$wrapper.parent().addClass('shrunk');
                self.$wrapper.parent().parent().parent().addClass('shrunk');
            }
        } else if (width <= maxWidth && width + this.extraWidth < containWidth) {
            newWidth = maxWidth
            if(self.$wrapper.parent().hasClass('shrunk')) {
                self.$wrapper.parent().removeClass('shrunk');
                self.$wrapper.parent().parent().parent().removeClass('shrunk');
            }
        }
        if (newWidth) {
            percent = (newWidth / width);
            self.$element.add(self.$wrapper).width(newWidth);
            self.$element.add(self.$wrapper).height(height * (percent));
            self.$wrapper.find('.thumbholder').css('padding-top',height * (percent)+'px');
        } 
      },

      //Animation locking functions
      lock: function () {
        this.locked = true;
      },

      unlock: function () { 
        this.locked = false;
      },

      updateOptionsIfOnlyOneSlide: function () {
        if(this.$slides.length === 1) {
          this.options.directionalNav = false;
          this.options.timer = false;
          this.options.bullets = false;
        }
      },

      setupFirstSlide: function () {
        //Set initial front photo z-index and fades it in
        var self = this;
        this.$slides.first()
          .css({"z-index" : 3})
          .fadeIn(function() {
            //brings in all other slides IF css declares a display: none
            self.$slides.css({"display":"block"})
        });
      },

      setupClicks: function () {
          var self = this;
          var slide = this.currentSlide();
          slide.click(function () { 
              self.stopClock();
          });
      },

      handleResize: function(element, options) {
          this.options = $.extend({}, this.defaults, options);
          this.$element = $(element);
          this.$wrapper = this.$element.parent();
          if(this.$element.hasClass('processing'))
                return;
          this.$element.addClass('processing');
          var w = parseInt(this.$wrapper.css('width'));
          var h = parseInt(this.$wrapper.css('height'));
          this.beResponsive(w,h);
          this.$element.removeClass('processing');

      },

      toggleTimer: function(element, options) {
        this.options = $.extend({}, this.defaults, options);
        this.timerRunning = true;
        this.$element = $(element);
        this.$timer = this.$element.parent().find('.timer');
        this.$pause = this.$timer.find('.pause');
        if (this.$pause.hasClass('active')) {
          this.timerRunning = false;
        }
        if(this.options.focus && !this.timerRunning) {
            this.$timer.click();
        } else if (!this.options.focus) { 
            this.$timer.click();
        }
      },

      startClock: function () {
        var self = this;

        if (this.$timer.is(':hidden')) {
          this.clock = setInterval(function () {
            self.shift("next");  
          }, this.options.advanceSpeed);            		
        } else {
          this.timerRunning = true;
          this.$pause.removeClass('active')
          this.clock = setInterval(this.rotateTimer, this.options.advanceSpeed / 180);
        }
      },

      rotateTimer: function () {
        var degreeCSS = "rotate(" + this.degrees + "deg)"
        this.degrees += 2;
        if(this.degrees > 180) {
          this.$mask.addClass('move');
        }
        if(this.degrees > 360) {
          this.degrees = 0;
          this.shift("next");
        }
      },

      stopClock: function () {
          this.timerRunning = false;
          clearInterval(this.clock);
          if (this.$pause)
              this.$pause.addClass('active');
      },

      setupTimer: function () {
        this.$timer = $(this.timerHTML);
        this.$wrapper.append(this.$timer);

        this.$rotator = this.$timer.find('.rotator');
        this.$mask = this.$timer.find('.mask');
        this.$pause = this.$timer.find('.pause');

        this.$timer.click(this.clickTimer);

        if (this.options.startClockOnMouseOut) {
          this.$wrapper.mouseleave(this.startTimerAfterMouseLeave);
          this.$wrapper.mouseenter(this.clearClockMouseLeaveTimer);
        }

        if (this.options.pauseOnHover) {
          this.$wrapper.mouseenter(this.stopClock);
        }
      },

      startTimerAfterMouseLeave: function () {
        var self = this;

        this.outTimer = setTimeout(function() {
          if(!self.timerRunning){
            self.startClock();
          }
        }, this.options.startClockOnMouseOutAfter)
      },

      clearClockMouseLeaveTimer: function () {
        clearTimeout(this.outTimer);
      },

      clickTimer: function () {
        if(!this.timerRunning) {
            this.startClock();
        } else { 
            this.stopClock();
        }
      },

      setupCaptions: function () {
          this.$caption = $(this.captionHTML);
          this.$wrapper.append(this.$caption);
          this.setCaption(true);
      },

      clearCaptionAfterMouseLeave: function() {
          jQuery(this.$caption).fadeOut(this.options.captionAnimationSpeed);
      },

      setCaptionAfterMouseHover: function() {
          jQuery(this.$caption).fadeIn(this.options.captionAnimationSpeed);
      },

      setCaption: function (toggle) {

        var captionLocation = this.currentSlide().attr('data-caption'),
          captionHTML;     
        if (!this.options.captions) {
          return false; 
        } 

        //Set HTML for the caption if it exists
        if (captionLocation && toggle) {
              captionHTML = $(captionLocation).html(); //get HTML from the matching HTML entity
              this.$caption
              .attr('id', captionLocation) // Add ID caption TODO why is the id being set?
              .html(captionHTML); // Change HTML in Caption 
              //
              captionClass = $(captionLocation).attr('class');
              
              this.$caption.attr('class', captionClass); // Add class caption TODO why is the id being set?
              if (this.options.sideThumbs) {
                // captions disappear so this needs to be done every time
                this.setCaptionLeft();
              }
              //Animations for Caption entrances
              if (this.options.captionHover) {
                  this.$wrapper.mouseleave(this.clearCaptionAfterMouseLeave);
                  this.$wrapper.mouseenter(this.setCaptionAfterMouseHover);
                  if (!this.$wrapper.hasClass('hover')){
                      return;
                  }

              }

              switch (this.options.captionAnimation) {
                case 'none':
                  this.$caption.show();
                  break;
                case 'fade':
                  this.$caption.fadeIn(this.options.captionAnimationSpeed);
                  break;
                case 'slideOpen':
                  this.$caption.slideDown(this.options.captionAnimationSpeed);
                  break;
              }
        } else {
              //Animations for Caption exits
              switch (this.options.captionAnimation) {
                case 'none':
                  this.$caption.hide().remove();
                  break;
                case 'fade':
                  this.$caption.fadeOut(this.options.captionAnimationSpeed, function() {
                      $(this).remove();
                  });
                  break;
                case 'slideOpen':
                  this.$caption.slideUp(this.options.captionAnimationSpeed, function() {
                      $(this).remove();
                  });
                  break;
              }
            }

      },

      setupDirectionalNav: function () {
          var self = this;
          this.$wrapper.append(this.directionalNavHTML);
          if ( this.options.captionHover ) {
              this.$wrapper.find('.left').css('display','none');
              this.$wrapper.find('.right').css('display','none');
              this.$wrapper.hover(function () {
                self.$wrapper.find('.left').css({'opacity':self.options.navOpacity,'display':'block'});
                self.$wrapper.find('.right').css({'opacity':self.options.navOpacity,'display':'block'});
              },function() {
                self.$wrapper.find('.left').css('display','none');
                self.$wrapper.find('.right').css('display','none');
              });
          } else {
            this.$wrapper.find('.left').css('opacity',self.options.navOpacity);
            this.$wrapper.find('.right').css('opacity',self.options.navOpacity);
          }
          if (this.options.sideThumbs) {
              this.setLeftMargin(self.$wrapper.find('.thumbholder').width());
          }

          self.$wrapper.find('.left').hover(function () {
            jQuery('.satl-nav .left').fadeTo("fast",0.75);
          },function(){
            jQuery('.satl-nav .left').fadeTo("fast",self.options.navOpacity);
          });
          self.$wrapper.find('.right').hover(function () {
            jQuery('.satl-nav .right').fadeTo("fast",0.75);
          },function(){
            jQuery('.satl-nav .right').fadeTo("fast",self.options.navOpacity);
          });


        this.$wrapper.find('.left').click(function () { 
          self.stopClock();
          self.shift("prev");
        });

        this.$wrapper.find('.right').click(function () {
          self.stopClock();
          self.shift("next")
        });          

      },

      setLeftMargin: function(eW) {
          this.extraWidth = (eW) ? eW : this.extraWidth;
          var self = this;
          // Navigation
          self.$wrapper.find('.left').css('left',this.extraWidth);
          var distance = parseInt(self.$wrapper.find('.left').css('left'))+self.$element.width();
          self.$wrapper.find('.right').css('left',distance - self.$wrapper.find('.right').width());
          // Caption
          self.setCaptionLeft(this.extraWidth);
          // Timer
          self.$wrapper.find('.timer').css('left',distance - self.$wrapper.find('.timer').width());
      },
      
      setCaptionLeft: function() {
        console.log('settings the extrawidth:'+ this.extraWidth);
        this.$wrapper.find('.orbit-caption').css('left',this.extraWidth);
      },

      setupDirectionalThumb: function (thumbHeight) {
          var self = this;
          var scrollsize = thumbHeight * 3;

          this.$wrapper.append(this.directionalThumbHTML);

          if (this.$holdermin) {
            this.$wrapper.find('#slideleft').hide();
            this.$wrapper.find('#slideright').hide();
          }

          this.$wrapper.find('#slideleft').click(function () { 
              self.$wrapper.find('.thumbholder').animate({scrollLeft: "-="+scrollsize}, 'slow'); 
          });

          this.$wrapper.find('#slideright').click(function () {
              self.$wrapper.find('.thumbholder').animate({scrollLeft: "+="+scrollsize}, 'slow'); 
          });
      },

      setupBulletNav: function () {
        if (this.options.bulletThumbs) {
          this.$bullets = $(this.thumbHTML);
          if (!this.options.sideThumbs) {
              this.$thumbwidth = (this.$slides.length * this.options.thumbWidth);
              // 40 because of 20px padding on orbit-thumbnails
              this.$bullets.css('width', this.$thumbwidth + 40);

          } else {
              this.$bullets.css('min-width', this.options.thumbWidth);
          }
        this.$wrapper.append(this.$bullets);
          this.$bullets.wrap(this.wrapThumbHTML);
          this.$holder = this.$wrapper.find('.thumbholder')
          this.$holder.css('padding-top',this.$wrapper.height()+'px');
          // If small amount of thumbs - minify stuff!
          if (this.$thumbwidth < this.$holder.width() && !this.options.sideThumbs) {
            this.$bullets.css('margin', '0 auto');
            this.$holdermin = true;
          }
          if (this.options.sideThumbs) {
              this.setSideThumbSize(null,null);
              var b = $('html');
              this.$wrapper.find('.thumbholder').hover(function() {
                var s = b.scrollTop();    
                b.css('overflow', 'hidden');
//                b.css('margin-right', 15);
                b.scrollTop(s);
              }, function(){
                var s = b.scrollTop();   
                b.css('overflow', 'auto');
//                b.css('margin-right', 0);
                b.scrollTop(s);
              });
          }



        } else {
          this.$bullets = $(this.bulletHTML);
        this.$wrapper.append(this.$bullets);
        }
        this.$slides.each(this.addBullet);

        if (this.options.centerBullets && this.options.bulletThumbs == false) {
              $bwidth = this.$bullets.width();
              this.$bullets.css('margin-left', -$bwidth / 2);
              this.$bullets.css('margin-right', $bwidth / 2);
          }
      },

      addBullet: function (index, slide) {
        var $li = $('<li></li>'),
            thumbName,
            self = this;

              if (this.options.bulletThumbs) {
                  thumbName = $(slide).attr('data-thumb');
                  if (thumbName) {
                      //Changed this to insert an image so you can resize thumbnails easily
                      $li.append("<img class='orbit-thumb' src='"+this.options.bulletThumbLocation + thumbName+"' />");

                  }
              }
              this.$bullets.append($li);
              $li.data('index', index);
              $li.click(function () {
                  self.stopClock();
                  self.shift($li.data('index'));
              });
      },

      setActiveBullet: function () {
        if(!this.options.bullets) {return false;} else {
          this.$bullets.find('li')
            .removeClass('active')
            .eq(this.activeSlide)
            .addClass('active');
        }
      },

      resetAndUnlock: function () {
        this.$slides
          .eq(this.prevActiveSlide)
          .css({"z-index" : 1})
          .animate({"opacity": 0}, this.options.animationSpeed);
        this.unlock();
        this.setupClicks();
        if (this.options.captions) {
          this.setupCaptions();
        }
        this.options.afterSlideChange.call(this, this.$slides.eq(this.prevActiveSlide), this.$slides.eq(this.activeSlide));
      },
      // load is weather to load plugin right away
      preload: function (imageList, max, load) {
        var self = this;

        var pic = [], i, total, loader = 0;
        if (typeof imageList != 'undefined') {
            if ($.isArray(imageList)) {
                total = imageList.length; // used later
                if (total > max)
                  total = max;
                for (i=0; i < total; i++) {
                  pic[i] = new Image();
                  pic[i].onload = function() {
                    loader++; // should never hit a race condition due to JS's non-threaded nature
                    if (loader == total && load) {
                      self.loaded();
                    }
                  };
                  pic[i].src = imageList[i];
                }
            } else {
                pic[0] = new Image();
                pic[0].src = imageList;
                self.loaded();
            }
        }
      },

      shift: function (direction) {
        var slideDirection = direction;
        //remember previous activeSlide
        this.prevActiveSlide = this.activeSlide;

        //exit function if bullet clicked is same as the current image
        if (this.prevActiveSlide == slideDirection) {return false;}

        if (this.$slides.length == "1") {return false;}
        if (!this.locked) {
          this.lock();
          //deduce the proper activeImage
          if (direction == "next") {
            this.activeSlide++;
            if (this.activeSlide == this.numberSlides) {
                this.activeSlide = 0;
            }
          } else if (direction == "prev") {
            this.activeSlide--
            if (this.activeSlide < 0) {
              this.activeSlide = this.numberSlides - 1;
            }
          } else {
            this.activeSlide = direction;
            if (this.prevActiveSlide < this.activeSlide) { 
              slideDirection = "next";
            } else if (this.prevActiveSlide > this.activeSlide) {
              slideDirection = "prev"
            }
          }

          //set to correct bullet
          this.setActiveBullet();  

          //set previous slide z-index to one below what new activeSlide will be
          this.$slides
            .eq(this.prevActiveSlide)
            .css({"z-index" : 2});    

          //no transition
          if (this.options.animation == "none") {
            this.$slides
              .eq(this.prevActiveSlide)
              .animate({"opacity" : 0}, 3);
            this.$slides
              .eq(this.activeSlide)
              .css({"z-index" : 3})
              .animate({"opacity" : 1}, 3, this.resetAndUnlock);
          }
          //fade empty
          if (this.options.animation == "fade-empty") {
            this.$slides
              .eq(this.prevActiveSlide)
              .animate({"opacity" : 0}, this.options.animationSpeed);
            this.$slides
              .eq(this.activeSlide)
              .css({"opacity" : 0, "z-index" : 3})
              .delay('500')
              .animate({"opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
          }

          //fade blend
          else if (this.options.animation == "fade-blend") {
            this.$slides
              .eq(this.activeSlide)
              .css({"opacity" : 0, "z-index" : 3})
              .animate({"opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
          }

          //pull out - transition effects
          else if (this.options.animation == "pullout") {
            //this.$slides
              //.eq(this.activeSlide)
              //.css({"opacity" : 0, "z-index" : 3})
              //.animate({"opacity" : 1}, this.options.animationSpeed, this.resetAndUnlock);
              this.$slides.eq(this.activeSlide).transition({
                  opacity: 0.4,
                  scale: 1.6,
                  rotate:'-5deg'
              });

          }

          //horizontal-slide
          else if (this.options.animation == "horizontal-slide") {
            if (slideDirection == "next") {
              this.$slides
                .eq(this.activeSlide)
                .css({"opacity": 1, "left": this.orbitWidth, "z-index" : 3})
                .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
            }

            if (slideDirection == "prev") {
              this.$slides
                .eq(this.activeSlide)
                .css({"opacity" : 1, "left": -this.orbitWidth, "z-index" : 3})
                .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
            }
          }

          //vertical-slide
          else if (this.options.animation == "vertical-slide") { 
            if (slideDirection == "prev") {
              this.$slides
                .eq(this.activeSlide)
                .css({"top": this.orbitHeight, "z-index" : 3, "opacity":1})
                .animate({"top" : 0}, this.options.animationSpeed, this.resetAndUnlock);
            }
            if (slideDirection == "next") {
              this.$slides
                .eq(this.activeSlide)
                .css({"top": -this.orbitHeight, "z-index" : 3, "opacity":1})
                .animate({"top" : 0}, this.options.animationSpeed, this.resetAndUnlock);
            }
          }

          //horizontal-push
          else if (this.options.animation == "horizontal-push") {
            if (slideDirection == "next") {
              this.$slides
                .eq(this.activeSlide)
                .css({"left": this.orbitWidth, "z-index" : 3, "opacity":1})
                .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
              this.$slides
                .eq(this.prevActiveSlide)
                .animate({"left" : -this.orbitWidth*2}, this.options.animationSpeed);
            }
            if (slideDirection == "prev") {
              this.$slides
                .eq(this.activeSlide)
                .css({"left": -this.orbitWidth*4, "z-index" : 3, "opacity":1})
                .animate({"left" : 0}, this.options.animationSpeed, this.resetAndUnlock);
              this.$slides
                .eq(this.prevActiveSlide)
                .animate({"left" : this.orbitWidth}, this.options.animationSpeed);
            }
          }

          //vertical-push
          else if (this.options.animation == "vertical-push") {
            if (slideDirection == "next") {
              this.$slides
                .eq(this.activeSlide)
                .css({top: -this.orbitHeight, "z-index" : 3, "opacity":1})
                .animate({top : 0}, this.options.animationSpeed, this.resetAndUnlock);
              this.$slides
                .eq(this.prevActiveSlide)
                .animate({top : this.orbitHeight}, this.options.animationSpeed);
            }
            if (slideDirection == "prev") {
              this.$slides
                .eq(this.activeSlide)
                .css({top: this.orbitHeight, "z-index" : 3, "opacity":1})
                .animate({top : 0}, this.options.animationSpeed, this.resetAndUnlock);
              this.$slides
                .eq(this.prevActiveSlide)
                .animate({top : -this.orbitHeight}, this.options.animationSpeed);
            }
          }

          this.setCaption();
        }
      }
    };
    $.fn.wait = function (MiliSeconds) {
        jQuery(this).animate({opacity: '+=0'}, MiliSeconds);
        return this;
    }

    $.fn.satlorbit = function (options) {
      return this.each(function () {
        var satlorbit = $.extend({}, SATLORBIT);
        satlorbit.init(this, options);
      });
    };

    $.fn.satlresponse = function(options) {
      return this.each(function() {
        var satlorbit = $.extend({},SATLORBIT);
        satlorbit.handleResize(this, options);
      });
    };
    $.fn.satlfocus = function(options) {
      return this.each(function() {
        var satlorbit = $.extend({},SATLORBIT);
        satlorbit.toggleTimer(this,options);
      });
    };

  })(jQuery);