/*
*   Unicircuit Onepager
*   «script.js»
*   Version 1.0, 28.09.2015
*   Verfasser Claudio Schäpper & Luca Signoroni
*/
(function($){

    var winSize= $(window).width();
    
    /* ---------------------------------------------- /*
     * Preloader
    /* ---------------------------------------------- */
    $(window).load(function(){
        $('#status').fadeOut();
        $('#preloader').delay(300).fadeOut('slow');
    });

    $(document).ready(function(){
        
        /* ---------------------------------------------- /*
         * Smooth scroll / Scroll To Top
        /* ---------------------------------------------- */
        $('a[href*=#]').bind("click", function(e){
            var anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $(anchor.attr('href')).offset().top
            }, 1000);
            e.preventDefault();
        });

        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                $('.scroll-up').fadeIn();
            } else {
                $('.scroll-up').fadeOut();
            }
        });


        /* ---------------------------------------------- /*
         * Navigation
        /* ---------------------------------------------- */
        $('.header').sticky({
                topSpacing: 0
        });

        $('body').scrollspy({
                target: '.navbar-custom',
                offset: 70
        })

        $(function(){ 
            var navMain = $("#custom-collapse");
            navMain.on("click", "a", null, function (){
                navMain.collapse('hide');
            });
        });


        /* ---------------------------------------------- /*
         * Module
        /* ---------------------------------------------- */  
        window.onresize= dynamicResizer;
        window.onload= dynamicResizerStart;

        function dynamicResizerStart(){
            var cw = $('.col-sm-3').width();
            $('.modulinhalt, .modulcontainer').css({'height':cw+'px'});
            showDesktop();
        }

        function dynamicResizer(){
            var cw = $('.col-sm-3').width();
            $('.modulinhalt, .modulcontainer').css({'height':cw+'px'});
            winSize= $(window).width();
        }


        /* ---------------------------------------------- /*
         * Galerie
        /* ---------------------------------------------- */ 
        if(winSize < 768){
            $('.slider').slick({
                dots: true,
                infinite: true,
                arrows: false,
                autoplay: true,
                autoplaySpeed: 4000,
                lazyLoad: 'ondemand'
            });
        }else{
            $('.slider').slick({
                dots: true,
                infinite: true,
                arrows: true,
                autoplay: true,
                autoplaySpeed: 4000,
                lazyLoad: 'ondemand'
            });       		
        }

        //Regelt die Sichtbarkeit von Mobile und Desktop Slider Imitation
        function showDesktop(){
            document.getElementById('sliderMobile').style.display='none';
            document.getElementById('sliderDesktop').style.display='block';
            $('.slider').slick('setPosition'); 
            $('#btn-mobile').removeClass('active');
            $('#btn-desktop').toggleClass('active');
        }

        function showMobile(){
            document.getElementById('sliderDesktop').style.display='none';
            document.getElementById('sliderMobile').style.display='block';
            $('.slider').slick('setPosition'); 
            $('#btn-desktop').removeClass('active');
            $('#btn-mobile').toggleClass('active');
        }

        document.getElementById('btn-desktop').onclick = showDesktop;
        document.getElementById('btn-mobile').onclick = showMobile;


        /* ---------------------------------------------- /*
         * Touchevents für iOS
        /* ---------------------------------------------- */
        $('.modulcontainer').on('touchstart', function(){
            $(this).addClass('select');
        }).on('.modulcontainer', function(){
            $(this).removeClass('select');
        });


        /* ---------------------------------------------- /*
         * Landing Page Hintergrundbild
        /* ---------------------------------------------- */
        $(".screen-height").height($(window).height());

        $(window).resize(function(){
            $(".screen-height").height($(window).height());
        });

        if(/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)){
            $('#home').css({'background-attachment': 'scroll'});
        }else{
            $('#home').parallax('50%', 0.3);
        }


        /* ---------------------------------------------- /*
         * WOW Animation (beim Scrollen)
        /* ---------------------------------------------- */
        wow = new WOW({
            mobile: false
        });
        wow.init();


        /* ---------------------------------------------- /*
         * E-mail validation
        /* ---------------------------------------------- */
        function isValidEmailAddress(emailAddress) {
            var pattern = new RegExp(/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i);
            return pattern.test(emailAddress);
        };


        /* ---------------------------------------------- /*
         * Kontaktformular über Ajax
        /* ---------------------------------------------- */
        $('#contact-form').submit(function(e) {
            //Verhindert absenden des Formulars
            e.preventDefault();
            
            //Honeypot Felder
            var honeypot = $('#h_email').val();
            var posted= $('#h_time').val();
            
            //Kontakt-Felder
            var c_name = $('#c_name').val();
            var c_email = $('#c_email').val();
            var c_message = $('#c_message ').val();
            var response = $('#contact-form .ajax-response');

            var d = new Date();
            var curTime=Math.floor(d.getTime() / 1000);
            var timeDiff = (curTime - posted);

            if(timeDiff<10){
                response.fadeIn(500);
                response.html('<i class="fa fa-warning"></i> Ihre Nachricht wurde als SPAM erkannt!');
            }else if(honeypot.trim()){
                response.fadeIn(500);
                response.html('<i class="fa fa-warning"></i> Ihre Nachricht wurde als SPAM erkannt!');
            }else{
                var formData = {
                    'name'       : c_name,
                    'email'      : c_email,
                    'message'    : c_message
                };

                if (( c_name== '' || c_email == '' || c_message == '') || (!isValidEmailAddress(c_email) )) {
                    response.fadeIn(500);
                    response.html('<i class="fa fa-warning"></i> Bitte Eingaben überprüfen und erneut versuchen.');
                }else{
                    $.ajax({
                        type        : 'POST',
                        url         : './php/contact.php',
                        data        : formData,
                        dataType    : 'json',
                        encode      : true,
                        success	:   function(res){
                                        var ret = $.parseJSON(JSON.stringify(res));
                                        response.html(ret.message).fadeIn(500);
                                    }
                    });
                }  
            }
            return false;
        });
    });

})(jQuery);