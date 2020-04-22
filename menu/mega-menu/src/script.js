// navbar mobile toggle
$(function() {
  var $body = $('html, body');

  $('.js-navbar-open').on('click', function() {
    $('.js-navbar').addClass('is-show');
    $body.addClass('overflow-hidden');
  });

  $('.js-navbar-close').on('click', function() {
    $('.js-navbar').removeClass('is-show');
    $body.removeClass('overflow-hidden');
  });
});

// menu toggle
$(function() {
  // mobile menu toggle
  $('.menu-item-group')
    .children('.menu-link, .menu-toggle')
    .on('click', function(e) {
      if ($(window).width() > 1200) {
        return;
      }

      e.preventDefault();

      var $clickItem = $(this),
        $thisMenu = $clickItem.siblings('.menu-sub'),
        $thisMegaMenu = $clickItem.siblings('.menu-mega').children('.menu-sub'),
        $otherMenu = $clickItem
          .parent()
          .siblings('.menu-item-group')
          .children('.menu-sub'),
        $otherMegaMenu = $clickItem
          .parent()
          .siblings('.menu-item-group')
          .children('.menu-mega')
          .children('.menu-sub');

      $thisMenu.slideToggle();
      $thisMegaMenu.slideToggle();
      $thisMenu.siblings('.menu-toggle').toggleClass('active');
      $thisMegaMenu
        .parent()
        .siblings('.menu-toggle')
        .toggleClass('active');

      $otherMenu.slideUp();
      $otherMegaMenu.slideUp();
      $otherMenu.siblings('.menu-toggle').removeClass('active');
      $otherMegaMenu
        .parent()
        .siblings('.menu-toggle')
        .removeClass('active');
    });

  // desktop menu mouse over
  $('.menu-root')
    .children('.menu-item-group')
    .on('mouseenter', function() {
      if ($(window).width() < 1200) {
        returrn;
      }
      
      var $megaMenu = $(this).children('.menu-mega');
      $megaMenu.slideDown(500);

      // if ($megaMenu.is(':hidden')) {
      //   $megaMenu.slideDown(500);
      //   $(this).addClass('active');
      // }
    })
    .on('mouseleave', function() {
      if ($(window).width() < 1200) {
        returrn;
      }
    
      var $megaMenu = $(this).children('.menu-mega');
      $megaMenu.hide();

      // if ($megaMenu.is(':visible')) {
      //   $megaMenu.slideUp(500);
      //   $(this).removeClass('active');
      // }
    });
});
