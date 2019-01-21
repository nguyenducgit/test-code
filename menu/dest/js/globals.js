$(document).ready(function () {
  // handle header nav
  $('.js-dropdown-toggle').on('click', function () {
    $(this).toggleClass('active').siblings('.js-dropdown-menu').slideToggle();
  });
  // open and close mobile menu
  $('.js-menu-btn').on('click', function () {
    $('.js-mobile-menu, .js-mobile-filter').addClass('is-show');
    $('body').addClass('no-scroll');
  });
  $('.js-mobile-filter, .js-menu-close').on('click', function () {
    $('.js-mobile-menu, .js-mobile-filter').removeClass('is-show');
    $('body').removeClass('no-scroll');
  });
});
