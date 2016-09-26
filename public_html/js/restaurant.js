$(document).ready(function() {
  $peopleSizePicker = $('.people-size-picker');
  $datePiker = $('.date-picker');
  $timePiker = $('.time-picker');
  if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
    $peopleSizePicker.selectpicker('mobile');
    $datePiker.selectpicker('mobile');
    $timePiker.selectpicker('mobile');
  } else {
    $peopleSizePicker.selectpicker({
      size: 6,
      mobile: false
    });
    $datePiker.selectpicker({
      size: 6,
      mobile: false
    });
    $timePiker.selectpicker({
      size: 6,
      mobile: false
    });
  }

  $writeReviwe = $('.goto-review');
  $writeReviwe.on('click', function (e) {
      e.preventDefault();
      var link = $(this).attr('href');
      var restaurant_id = $(this).data('restaurant-id');
      $.get(link).done(function(data) {
          $reviewForm = $(data).filter('div').html();
          showModal('Write a review', $reviewForm);
          var stars = 0;
          var user_id = $.cookie('login_user_id');
          $reviewBlock = $('.review-block');
          $reviewForm = $('.review-form');
          $submitBtn = $('input[name="review-submit"]');
          $starts = $('input[name="starts"]');
          $starts.each(function(key, input) {
              $(input).on('click', function () {
                  stars = $(this).val();
              });
          });
          $comment = $('textarea[name="content"]');
          $submitBtn.on('click', function (e) {
              e.preventDefault();
              $('.alert').remove();
              // console.log(stars, $comment.val());
              if (stars === 0 || $comment.val().length === 0) {
                  $alert = $('<div class="alert alert-danger">Please select stars and input your comment!</div>');
                  $alert.hide();
                  $reviewBlock.prepend($alert);
                  $alert.fadeIn(300);
                  return false;
              }
              $.ajax({
                  type: 'POST',
                  url: 'review.logic.php',
                  data: {
                      user_id: user_id,
                      restaurant_id: restaurant_id,
                      rating: stars,
                      content: $comment.val()
                  },
                  success: function (data) {
                      data = $.parseJSON(data);
                      if (data.success) {
                          console.log('success');
                          $alert = $('<div class="alert alert-success"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;Your review is already submitted.</div>');
                          $reviewForm.hide();
                          $alert.hide();
                          $reviewBlock.append($alert);
                          $alert.fadeIn(300);
                          setTimeout(function () {
                              $('.close').click();
                          }, 2000);
                      } else {
                          $alert = $('<div class="alert alert-danger"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;' + data.message + '</div>');
                          $reviewForm.hide();
                          $alert.hide();
                          $reviewBlock.append($alert);
                          $alert.fadeIn(300);
                          setTimeout(function () {
                              $('.close').click();
                          }, 2000);
                      }
                  }
              });
          });
      });
  });
});
