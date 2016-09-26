$(document).ready(function() {
    var i, state = '',
        cuisine = '',
        $statesDropdown = $('.states-dropdown'),
        $cuisinesDropdown = $('.cuisines-dropdown'),
        option;
    for (i = 0; i < states.length; i++) {
        state = states[i];
        option = $('<option value="' + state + '">' + state + '</option>');
        $statesDropdown.append(option);
    }

    for (i = 0; i < cuisines.length; i++) {
        cuisine = cuisines[i];
        option = $('<option value="' + cuisine + '">' + cuisine + '</option>');
        $cuisinesDropdown.append(option);
    }

    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)) {
        $statesDropdown.selectpicker('mobile');
        $cuisinesDropdown.selectpicker('mobile');
    } else {
        $statesDropdown.selectpicker({
            size: 6,
            mobile: false
        });
        $cuisinesDropdown.selectpicker({
            size: 6,
            mobile: false
        });
    }

    if ($('.capacity2').prop('checked') === true) {
        $('.number-picker-2').removeClass('hidden');
    }
    if ($('.capacity4').prop('checked') === true) {
        $('.number-picker-4').removeClass('hidden');
    }
    if ($('.capacity6').prop('checked') === true) {
        $('.number-picker-6').removeClass('hidden');
    }
    if ($('.capacity8').prop('checked') === true) {
        $('.number-picker-8').removeClass('hidden');
    }
    $('.capacity2').change(function() {
        $('.number-picker-2').toggleClass('hidden');
        if ($('.capacity2').prop('checked') === false) {
            $('input[name="numberCapacity2"]').val(0);
        } else {
            $('input[name="numberCapacity2"]').val(1);
        }
    });
    $('.capacity4').change(function() {
        $('.number-picker-4').toggleClass('hidden');
        if ($('.capacity4').prop('checked') === false) {
            $('input[name="numberCapacity4"]').val(0);
        } else {
            $('input[name="numberCapacity4"]').val(1);
        }
    });
    $('.capacity6').change(function() {
        $('.number-picker-6').toggleClass('hidden');
        if ($('.capacity6').prop('checked') === false) {
            $('input[name="numberCapacity6"]').val(0);
        } else {
            $('input[name="numberCapacity6"]').val(1);
        }
    });
    $('.capacity8').change(function() {
        $('.number-picker-8').toggleClass('hidden');
        if ($('.capacity8').prop('checked') === false) {
            $('input[name="numberCapacity8"]').val(0);
        } else {
            $('input[name="numberCapacity8"]').val(1);
        }
    });

    $('.require-one').each(function (key, input) {
        $(input).on('click', function (){
          console.log(this);
            if ($(this).prop('checked')) {
              $(this).val('true');
            } else {
              $(this).val('false');
            }
        });
    });

    var userId = $.cookie('login_user_id');
    var $signupRestaurantSubmit = $('.sign-up-restaurant-submit');
    var $signupForm = $('.sign-up-restaurant-form');
    $signupForm.append($('<input type="hidden" name="userId" value="' + userId + '">'));
    $.validator.setDefaults({
        debug: true,
        success: "valid"
    });
    $.validator.addMethod("uploadFile", function (val, element) {
        var size = element.files[0].size;
        if (size > 1048576) {
            // console.log("returning false");
            return false;
        } else {
            // console.log("returning true");
            return true;
        }
    }, "File type error");
    $.validator.addMethod('require-one', function(value) {
        // console.log('here');
        return $('.require-one:checked').size() > 0;
    }, 'Select at least 1 option!');
    var checkboxes = $('.require-one');
    var checkbox_names = $.map(checkboxes, function(e, i) {
  		  return $(e).attr("name");
  	}).join(" ");
    console.log(checkbox_names);
    var validator = $signupForm.validate({
        ignore: [],
        groups: {
    			  checks: checkbox_names
    		},
        rules: {
            name: 'required',
            address: {
              required: true,
              minlength: 6
            },
            city: 'required',
            state: 'required',
            zipcode: {
              required: true,
              zipcodeUS: true
            },
            phone: {
              required: true,
              phoneUS: true
            },
            cuisine: 'required',
            description: {
              required: true,
              minlength: 10
            },
            'capacityCheckbox[]': 'required',
            numberCapacity2: {
              required: '.capacity2:checked',
            },
            numberCapacity4: {
              required: '.capacity4:checked',
            },
            numberCapacity6: {
              required: '.capacity6:checked',
            },
            numberCapacity8: {
              required: '.capacity8:checked',
            },
            image: {
              required: true,
              extension:'jpe?g',
              uploadFile:true
            }
        },
        messages: {
          name: 'Restaurant name is required!',
          address: {
            required: 'Restaurant address is required!',
            minlength: 'Address is too short, please enter correct address!'
          },
          city: 'City is required!',
          state: 'Please select state from the drompdown menu!',
          zipcode: {
            required: 'Zipcode is required!',
            zipcodeUS: 'Please enter correct US zipcode!'
          },
          phone: {
            required: 'Phone number is required!',
            phoneUS: 'Please enter correct US phone number!'
          },
          cuisine: 'Please select cuisine type from the dropdown menu!',
          description: {
            required: 'Description is required!',
            minlength: 'Description is too short!'
          },
          'capacityCheckbox[]': 'Please chose at least one table capacity!',
          image: {
            required: 'Please choose and upload an image of your restaurant!',
            extension:'Please upload an image in jpg or jpeg format!',
            uploadFile: 'Image size should be less than 1MB!'
          }
        },
        highlight: function(element) {
            $(element).closest('.has-feedback').addClass('has-error').children('div').children('.glyphicon').addClass('glyphicon-remove');
        },
        unhighlight: function(element) {
            $(element).closest('.has-feedback').removeClass('has-error').children('.glyphicon').removeClass('glyphicon-remove');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else if (element.attr("name") === "state") {
                error.insertAfter(".btn-group.states-dropdown");
                console.log('1-1');
            } else if (element.attr("name") === "cuisine") {
                error.insertAfter(".btn-group.cuisines-dropdown");
                console.log('2-1');
            } else if (element.attr('type') === "checkbox") {
                console.log('3-1');
                error.appendTo(".capacity-options");
            } else {
                if (element.attr("name") === "state" || element.attr("name") === "cuisine") {
                }
                error.insertAfter(element);
            }
        }
    });
    $signupRestaurantSubmit.on('click', function () {
        var formData = new FormData();
        formData.append('userId', $('input[name="userId"]').val());
        formData.append('name', $('input[name="name"]').val());
        formData.append('address', $('input[name="address"]').val());
        formData.append('city', $('input[name="city"]').val());
        formData.append('state', $('select[name="state"]').val());
        formData.append('zipcode', $('input[name="zipcode"]').val());
        formData.append('phone', $('input[name="phone"]').val());
        formData.append('cuisine', $('select[name="cuisine"]').val());
        formData.append('description', $('textarea[name="description"]').val());
        formData.append('hasCapacity2', $('input[name="capacityCheckbox2"]').val());
        formData.append('hasCapacity4', $('input[name="capacityCheckbox4"]').val());
        formData.append('hasCapacity6', $('input[name="capacityCheckbox6"]').val());
        formData.append('hasCapacity8', $('input[name="capacityCheckbox8"]').val());
        formData.append('numberCapacity2', $('input[name="numberCapacity2"]').val());
        formData.append('numberCapacity4', $('input[name="numberCapacity4"]').val());
        formData.append('numberCapacity6', $('input[name="numberCapacity6"]').val());
        formData.append('numberCapacity8', $('input[name="numberCapacity8"]').val());
        formData.append('file', $('input[type=file]')[0].files[0]);
        if ($signupForm.valid()) {
            $('.alert').remove();
            console.log('valid');
            $.ajax({
                type: 'POST',
                url: 'sign-up-restaurant.logic.php',
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                  console.log(data);
                  data = $.parseJSON(data);
                  if (data.success) {
                    showSuccess(data);
                  } else {
                    showFailure(data);
                  }
                }
            });
        } else {
            return false;
        }

    });

    function showSuccess(data) {
      console.log('show');
      $alert = $('<div class="alert alert-success"></div>');
      $alertContent = $('<ul><li>' + data.message + '</li><li>We will review and verify your restaurant information. After we approve your restaurant information, we will contact you shortly to provide further asssitance.</li></ul>');
      $alert.append($alertContent);
      $alert.hide();
      $('.sign-up-restaurant-form').remove();
      $('.register-container').append($alert);
      $alert.fadeIn(300);
    }

    function showFailure(data) {
      console.log('show');
      $alert = $('<div class="alert alert-danger"></div>');
      $alertContent = $('<ul><li>' + data.message + '</li><li>If you have any question, please contact us!</li></ul>');
      $alert.append($alertContent);
      $alert.hide();
      // $('.sign-up-restaurant-form').remove();
      $('.register-container').prepend($alert);
      $alert.fadeIn(300);
    }
});
