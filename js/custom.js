(function ($) {
    "use strict";


    // Initialisiere wow.js
    $(function () {
        new WOW().init();
        });
        
    // nach oben Button
    $(function() {
        var nachObenBtn = $('.nachOben');
        $(window).scroll(function() {
          if ($(this).scrollTop() > 300) {
            nachObenBtn.fadeIn('slow');
          } else {
            nachObenBtn.fadeOut('slow');
          }
        });
        nachObenBtn.click(function(event) {
          event.preventDefault();
          $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        });
      });


    // Fahrradübersicht
    $(".fahrrad-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 900,
        margin: 25,
        loop: true,
        center: true,
        nav: true,
        navText : [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>'
        ],
        responsive: {
            0:{
                items:1
            },
            576:{
                items:1
            },
            760:{
                items:2
            },
            980:{
                items:3
            }
        }
    });
    
})(jQuery);