
    <script src="/assets_style/arsetontong/js/jquery-3.6.0.min.js"></script>
<script src="https://www.google.com/recaptcha/enterprise.js?render=6Le8TJ0qAAAAAIhDdQJKzAsThYwKCBGBDONXbst4"></script>
    <script src="/assets_style/arsetontong/js/parallax.min.js"></script>
    <script src="/assets_style/arsetontong/js/jquery.singlePageNav.min.js"></script>
    <script>
      function onSubmit(token) {
        document.getElementById("hubung").submit();
      }
      function setLanguage(lang) {
        // Get all elements with language data attributes
        const elements = document.querySelectorAll('[data-en][data-id]');
        elements.forEach(el => {
          el.textContent = el.getAttribute(`data-${lang}`);
        });
      }
      // Set default language to English
      setLanguage('en');

        function checkAndShowHideMenu() {
            if(window.innerWidth < 768) {
                $('#tm-nav ul').addClass('hidden');
            } else {
                $('#tm-nav ul').removeClass('hidden');
            }
        }

        $(function(){
            var tmNav = $('#tm-nav');
            tmNav.singlePageNav();

            checkAndShowHideMenu();
            window.addEventListener('resize', checkAndShowHideMenu);

            $('#menu-toggle').click(function(){
                $('#tm-nav ul').toggleClass('hidden');
            });

            $('#tm-nav ul li').click(function(){
                if(window.innerWidth < 768) {
                    $('#tm-nav ul').addClass('hidden');
                }
            });

            $(document).scroll(function() {
                var distanceFromTop = $(document).scrollTop();

                if(distanceFromTop > 100) {
                    tmNav.addClass('scroll');
                } else {
                    tmNav.removeClass('scroll');
                }
            });

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>
