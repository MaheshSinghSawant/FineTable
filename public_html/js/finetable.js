$(document).ready(function() {

  $('.header-search-form').hide();
  $('.search-bar-container').show();
  $(window).scrollTop(0);

  var originalTop = $('.search-bar').offset().top;
  var previousScroll = 0;
  // var headerSearchInput = $('.header-search-input');
  // var bodySearchInput = $('.body-search-input');
  var $header = $('header');

  $(window).scroll(function (e) {
    var currentScroll = $(this).scrollTop();
    var currentTop = originalTop - $(window).scrollTop();
    var searchBarOpacity = 1;
    var $headerSearch = $('.header-search-form');
    var $bodySearch = $('.search-bar-container');

    if (currentTop <= 30 && currentScroll > previousScroll) {
      $header.addClass('darker');
    //   if ($headerSearch.css('display') === 'none') {
    //     $headerSearch.fadeIn(300);
    //   }
    //   if ($bodySearch.css('display') === 'block') {
    //     $bodySearch.fadeOut(300);
    //   }
      // searchBarOpacity = (currentTop / 90);
      // $bodySearch.css('opacity', searchBarOpacity);
  } else if (currentTop >= 30 && currentScroll < previousScroll) {
      $header.removeClass('darker');
    //   if ($headerSearch.css('display') === 'block') {
    //     $headerSearch.fadeOut(300);
    //     $('.header-search-input').blur();
    //     $header.css('background-color', '');
    //   }
    //   if ($bodySearch.css('display') === 'none') {
    //     $bodySearch.fadeIn(300);
    //   }
      // searchBarOpacity = (currentTop / 90);
      // $bodySearch.css('opacity', searchBarOpacity);
    }
    previousScroll = currentScroll;
  });

  $('.header-search-input').focus(function () {
    if ($header.hasClass('darker')) {
      $header.css('background-color', 'rgba(0, 0, 0, 0.8)');
    } else {
      $header.css('background-color', 'rgba(255, 255, 255, 1.0)');
    }

  });
  $('.header-search-input').blur(function () {
    $header.css('background-color', '');
  });
  $('.body-search-input').focus(function () {
    $('.search-bar-background').css('opacity', 1);
  });
  $('.body-search-input').blur(function () {
    $('.search-bar-background').css('opacity', 0.8);
  });
});
