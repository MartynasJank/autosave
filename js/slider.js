$(function(){
    $.fn.popup = function () {
        function popup(elements, speed){

            this.ANIMATION_SPEED = speed;

            this.$global = $('html body');

            //MAIN GALLERY
            this.$pictureContainer = $('.bigpic');
            this.$picture = $('.picture img');

            //POPUP
            this.$popupPicture = $('.popup .container .main');
            this.$popupContainer = $('.popup .container');
            this.$popupElement = $('.popup');
            this.$pictureNumber = $('.popup .number')

            //EVENTS
            this.$right = $('.right');
            this.$left = $('.left');
            this.$close = $('.close');
            this.$number = $('.number');

            this.currentIndex;
            this.src;

            //WIDTH AFTER IMAGE GETS BIGGER
            this.width;
            //POSITION OF SELECTED ELEMENT
            this.position;
            //HEIGHT OF SMALL IMAGE
            this.height;
            //CHECKS IF POPUP IS OPEN
            this.$open = 0;

            //FUNCTIONS
            this.checkRightIndex = function(index){
                if(index > this.$pictureContainer.length-1){
                    this.currentIndex = 0;
                }
            }

            this.checkLeftIndex = function(index){
                if (index < 0){
                    this.currentIndex = this.$pictureContainer.length-1;
                }
            }

            //DISPLAY WHICH PICTURE OF ALL PICTURES IS DISPLAYED
            this.setPicNumber = function(){
                this.$pictureNumber.html(this.currentIndex+1 + " of " + this.$pictureContainer.length);
            }

            //ENABLES SCROLL
            this.enableScroll = function(){
                this.$global.css('overflow', 'auto').css('height', 'auto');
            }

            //DISABLES SCROLL
            this.disableScroll = function(){
                this.$global.css('overflow', 'hidden').css('height', '100%');
            }

            this.closePopup = function(){
                this.$open = 0;
                // this.$close.hide();
                // this.$number.hide();
                // this.position = this.$picture.eq(this.currentIndex).position();
                // this.height = this.$picture.eq(this.currentIndex).height();
                // this.$popupContainer.css({
                //     "left": this.position.left+"px",
                //     "top": this.position.top+"px",
                //     "height": this.height+"px"
                // });
            }

            //OPENS POPOUT IMAGE
            this.openImage = function(event){
                this.$open = 1;
                this.currentIndex = $(event).parent().parent().index();
                this.setPicNumber();
                this.disableScroll();
                this.src = this.$picture.eq(this.currentIndex).attr('src');
                this.$popupPicture.attr('src', this.src);
                // this.position = this.$picture.eq(this.currentIndex).position();
                // this.height = this.$picture.eq(this.currentIndex).height();
                // this.$popupContainer.css('left', this.position.left).css("top", this.position.top).css("height", this.height);
                this.$popupElement.show();
                // this.$popupContainer.css({
                //     "top": ($(window).height() / 2) - ($(window).height()*0.9 / 2 ),
                //     "left": ($(window).width() / 2) - ($(window).height()*0.9 / 2 ),
                //     "height": "90%",
                // });
            }

            //OPEN POPUT IMAGE (IN TIMEOUT)
            this.openImageTO = function(){
                // this.width = this.$popupPicture.width();
                // this.$popupContainer.css("width", this.width);
                this.$close.show();
                this.$number.show();
            }

            this.move = function(){
                this.setPicNumber();
                this.src = this.$picture.eq(this.currentIndex).attr('src');
                this.$popupPicture.attr('src', this.src);
            }

            this.moveRight = function(){
                this.currentIndex++;
                this.checkRightIndex(this.currentIndex);
                this.move();
            }

            this.moveLeft = function(){
                this.currentIndex--;
                this.checkLeftIndex(this.currentIndex);
                this.move();
            }

            this.scrollToImg = function(){
                // popupObject.$global.animate({
                //     scrollTop: popupObject.$picture.eq(popupObject.currentIndex).position().top
                // }, 0);
            }
        }
        /////////////////
        //END OF OBJECT//
        /////////////////
        var popupObject = new popup(this, 400);

        //MAIN GALLERY
        popupObject.$picture.click(function(){
            popupObject.openImage(this);
            // setTimeout(function(){ popupObject.openImageTO(); }, 400);
            popupObject.openImageTO();
        });

        //MOVE RIGHT
        popupObject.$right.click(function(){
            popupObject.moveRight();
        });

        //MOVE LEFT
        popupObject.$left.click(function(){
            popupObject.moveLeft();
        });

        //CLOSE POPUP WITH X BUTTON
        popupObject.$close.click(function(){
            popupObject.closePopup();
            // setTimeout(function(){popupObject.$popupElement.hide();}, 400);
            popupObject.$popupElement.hide();
            popupObject.enableScroll();
            popupObject.scrollToImg();
        });

        //CLOSE POPUP BY PRESSING NOT ON PICTURE OR BUTTONS
        popupObject.$popupElement.click(function(event){
            if($(event.target).is('.popup')){
                popupObject.closePopup();
                // setTimeout(function(){popupObject.$popupElement.hide();}, 400);
                popupObject.$popupElement.hide();
                popupObject.enableScroll();
                popupObject.scrollToImg();
            }
        });

        $(window).on('keydown', function(e) {
            console.log(e.key);
            if(popupObject.$open == 1 && e.key == "ArrowLeft"){
                popupObject.moveLeft();
            }
            if(popupObject.$open == 1 && e.key == "ArrowRight"){
                popupObject.moveRight();
            }
            if(popupObject.$open == 1 && e.key == "Escape"){
                popupObject.closePopup();
                popupObject.$popupElement.hide();
                popupObject.enableScroll();
                popupObject.scrollToImg();
            }
        });

        return this;
    }
    $('#gallery').popup();

    // OTHER SHIT
    var carsLength = $('.piclink').length;
    var cars = $('.piclink');

    //OPEN ALL DOWNLOAD LINKS ONE BY ONE
    $('.martynasdownload').click(function(){
        (function theLoop (i) {
          setTimeout(function () {
            cars[i-1].click()
            if (--i) {
              theLoop(i);
            }
          }, 500);
        })(carsLength);
    });

    // LOADER
    $('.search').click(function(){
        $('html, body').css({
            overflow: 'hidden',
            height: '100%'
        });
        $('.loader-wrapper').fadeIn("slow");

    });

    $(window).on("load", function(){
        $('html, body').css({
            overflow: 'auto',
            height: 'auto'
        });
        $(".loader-wrapper").fadeOut("slow");
    });

    //RICH TEXT EDITOR
    tinymce.init({
        selector:'textarea#body',
        menubar: false,
        statusbar: false,
        toolbar: false,
        height: 400
    });
});
