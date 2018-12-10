 $(document).ready(function(){


  $('#slider').slick({
    dots: true,
    infinite: true,
    slidesToShow: 5,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 3,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });



  // BIG MENU


  $(".break-slide2, .break-slide").click(function(){
    $("#mycontent-blure").removeClass('blure-page');
  });

/*
  $(".campus").click(function(){
      $(".panels").slideToggle();
      $(".panels2").slideUp();
  });
  */
  $(".campus").each(function(){
      $(this).click(function(){
          //$(".classe_"+ $(this).attr('id')).slideToggle();
          //$(".classe_"+ $(this).attr('id')).slideUp();
          console.log(".panels "+$(this).attr('id'));
          $("#panel_"+ $(this).attr('id')).slideToggle();
          $(".panels2").slideUp();
      });
  });


   $(".departement").click(function(){
      $(".panels2").slideToggle();
      $(".panels").slideUp();
  });
  $("button.close, .break-slide").click(function(){
      $(".panels").slideUp();
      $(".panels2").slideUp();
  });



  // FIN BIG MENU

  $("#search").fadeOut();
  $(".btn-search").click(function(){
      $("#search").slideToggle().css("opacity","1");
  });


  // MENU
  $(".mycustume-toggle").click(function(){
    $("#menu-resp .navbar-nav").toggleClass('menu-back');
  })
 

     // declare variable
    var scrollTop = $(".scrollTop");

    $(window).scroll(function() {
      // declare variable
      var topPos = $(this).scrollTop();

      // if user scrolls down - show scroll to top button
      if (topPos > 100) {
        $(scrollTop).css("opacity", "1");

      } else {
        $(scrollTop).css("opacity", "0");
      }

    }); // scroll END

    //Click event to scroll to top
    $(scrollTop).click(function() {
      $('html, body').animate({
        scrollTop: 0
      }, 800);
      return false;

    }); // click() scroll top EMD

     // STYCKY 

      window.onscroll = function() {myFunction()};

      var header = document.getElementById("myHeader");
       var sticky = header.offsetTop;

      function myFunction() {
        if (window.pageYOffset >= sticky) {
          header.classList.add("scroll");
        } else {
          header.classList.remove("scroll");
        }
      }


      // ANNIMATION
       wow = new WOW(
            {
              animateClass: 'animated',
              offset:       100,
              callback:     function(box) {
                console.log("WOW: animating <" + box.tagName.toLowerCase() + ">")
              }
            }
          );
          wow.init();
          document.getElementById('moar').onclick = function() {
            var section = document.createElement('section');
            section.className = 'section--purple wow fadeInDown';
            this.parentNode.insertBefore(section, this);
          };



            window.onscroll = function() {myFunction()};

            var header = document.getElementById("myHeader");
             var sticky = header.offsetTop;

            function myFunction() {
              if (window.pageYOffset >= sticky) {
                header.classList.add("scroll");
              } else {
                header.classList.remove("scroll");
              }
            }


// POP HOVER
  
});
