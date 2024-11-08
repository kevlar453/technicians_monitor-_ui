
    <script src="/assets_style/lucy/js/jquery-1.11.2.min.js"></script>
    <script src="/assets_style/lucy/js/wow.min.js"></script>
    <script src="/assets_style/lucy/js/owl-carousel.js"></script>
    <script src="/assets_style/lucy/js/nivo-lightbox.min.js"></script>
    <script src="/assets_style/lucy/js/smoothscroll.js"></script>
    <script src="/assets_style/lucy/js/bootstrap.min.js"></script>
    <script src="/assets_style/lucy/js/classie.js"></script>
    <script src="/assets_style/lucy/js/script.js"></script>
    <script>
        new WOW().init();
    </script>
    <script>
        $(document).ready(function(){
            $(".hideit").click(function(){
                $(".overlay").hide();
            });
            $("#trigger-overlay").click(function(){
                $(".overlay").show();
            });
        });
    </script>
    <script>
        $(document).ready(function(){

          var kawa = $('.top-bar');
          var back = $('#back-to-top');
          function scroll() {
             if ($(window).scrollTop() > 700) {
                kawa.addClass('fixed');
                back.addClass('show-top');

             } else {
                kawa.removeClass('fixed');
                back.removeClass('show-top');
             }
          }

          document.onscroll = scroll;
        });
    </script>
    <!--HHHHHHHHHHHH        Smooth Scrooling     HHHHHHHHHHHHHHHH-->
    <script>
        $(function() {
          $('a[href*=#]:not([href=#])').click(function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
              if (target.length) {
                $('html,body').animate({
                  scrollTop: target.offset().top
                }, 1000);
                return false;
              }
            }
          });
        });
    </script>
    <script>
        // Hide preloader when the page is loaded
        window.onload = function() {
            document.getElementById('preloader').style.display = 'none';
        };
    </script>
</body>
</html>
