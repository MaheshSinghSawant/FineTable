var urlBase = '';

var states = ["AL", "AK", "AZ", "AR", "CA", "CO", "CT", "DE", "DC", "FL", "GA", "HI", "ID", "IL", "IN", "IA", "KS", "KY", "LA", "ME", "MD", "MA", "MI", "MN", "MS", "MO", "MT", "NE", "NV", "NH", "NJ", "NM", "NY", "NC", "ND", "OH", "OK", "OR", "PA", "RI", "SC", "SD", "TN", "TX", "UT", "VT", "VA", "WA", "WV", "WI", "WY"];

var cuisines = ["Afghan", "African", "Albanian", "Alcohol", "American", "Argentinian", "Asian", "Australian", "Austrian", "Bagels", "Bakery", "BBQ", "Belgian", "Brazilian", "Breakfast", "British", "Burmese", "Cajun", "Californian", "Calzones", "Cambodian", "Cantonese", "Caribbean", "Cheesesteaks", "Chicken", "Chili", "Chinese", "Classic", "Coffee and Tea", "Colombian", "Costa Rican", "Crepes", "Cuban", "Deli", "Dessert", "Dim Sum", "Diner", "Dinner", "Dominican", "Eclectic", "Ecuadorian", "Egyptian", "El Salvadoran", "Empanadas", "English", "Ethiopian", "Filipino", "Fine Dining", "French", "Fresh Fruits", "Frozen Yogurt", "German", "Gluten-Free", "Greek", "Grill", "Grocery Items", "Guatemalan", "Gyro", "Haitian", "Halal", "Hamburgers", "Hawaiian", "Healthy", "Hoagies", "Hot Dogs", "Ice Cream", "Indian", "Indonesian", "Irish", "Italian", "Jamaican", "Japanese", "Kids Menu", "Korean", "Kosher", "Kosher-Style", "Late Night", "Latin American", "Lebanese", "Low Carb", "Low Fat", "Lunch", "Lunch Specials", "Malaysian", "Mandarin", "Mediterranean", "Mexican", "Middle Eastern", "Mongolian", "Moroccan", "Nepalese", "New American", "Noodles", "Organic", "Pakistani", "Pasta", "Persian", "Peruvian", "Pitas", "Pizza", "Polish", "Portuguese", "Potato", "Pub Food", "Puerto Rican", "Ribs", "Russian", "Salads", "Sandwiches", "Scandinavian", "Seafood", "Senegalese", "Shakes", "Smoothies and Juices", "Soul Food", "Soup", "South African", "South American", "Southern", "Southwestern", "Spanish", "Steak", "Subs", "Sushi", "Szechwan", "Taiwanese", "Tapas", "Tex-Mex", "Thai", "Tibetan", "Turkish", "Ukrainian", "Vegan", "Vegetarian", "Venezuelan", "Vietnamese", "Wings", "Wraps"];

(function(a){a.createModal=function(b){defaults={title:"",message:"Your Message Goes Here!",closeButton:true,scrollable:false};var b=a.extend({},defaults,b);var c=(b.scrollable===true)?'style="max-height: 420px;overflow-y: auto;"':"";html='<div class="modal fade" id="myModal">';html+='<div class="modal-dialog">';html+='<div class="modal-content">';html+='<div class="modal-header">';html+='<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';if(b.title.length>0){html+='<h4 class="modal-title">'+b.title+"</h4>"}html+="</div>";html+='<div class="modal-body" '+c+">";html+=b.message;html+="</div>";html+='<div class="modal-footer">';if(b.closeButton===true){html+='<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'}html+="</div>";html+="</div>";html+="</div>";html+="</div>";a("body").prepend(html);a("#myModal").modal().on("hidden.bs.modal",function(){a(this).remove()})}})(jQuery);

function showModal(title, message) {
    $.createModal({
        title: title,
        message: message,
        closeButton: false,
        scrollable: false
    });
}

$(document).ready(function() {
    // var $urlBase = $('<base href="http://localhost:8080/" target="_blank" />');
    // var $head = $('head');
    var $login = $('.login-link');
    var $signupLink = $('.signup-link');
    var loginLink;

    // $head.append($urlBase);
    $login.on('click', function() {
        var link = urlBase + $(this).attr('href');
        $.get(link).done(function(data) {
            $loginBlock = loginLink = $(data).filter('div').html();
            showModal('Login', $loginBlock);
            var $loginForm = $('.login-form');
            var $loginSubmit = $('input[name="login-submit"]');
            var $newUserLink = $('.new-user-link');
            $newUserLink.on('click', function () {
                $('.close').click();
                setTimeout(function() {
                    $signupLink.click();
                }, 1000);
            });
            $loginSubmit.on('click', function() {
                $('.alert').remove();
                var postUrl = $loginForm.attr('action');
                var $loginAlert = $('.login-alert');
                var $usernameInput = $('input[name="username"]');
                var $passwordInput = $('input[name="password"]');
                var $homeLink = $('.home-link');
                var $loginLink = $('.login-link');
                var $signupLink = $('.signup-link');
                var $navSeparator = $('.nav-separator');
                var username = $usernameInput.val();
                var password = $passwordInput.val();
                var $alert;
                if (username.length === 0 || password.length === 0) {
                    $alert = $('<div class="alert alert-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Please enter username and password!</div>');
                    $loginAlert.append($alert);
                    $alert.hide();
                    $alert.fadeIn(300);
                } else {
                    $.ajax({
                        type: 'POST',
                        url: postUrl,
                        data: 'username=' + username + '&password=' + password,
                        success: function(data) {
                            data = $.parseJSON(data);
                            if (data.success) {
                                var userinfo = $.parseJSON(data.userinfo);
                                data.userinfo = userinfo;
                            }
                            if (data.success) {
                                $loginForm.hide();
                                $alert = $('<div class="alert alert-success"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;&nbsp;' + data.message + '</div>');
                                $loginAlert.append($alert);
                                $alert.hide();
                                $alert.fadeIn(300);
                                setTimeout(function() {
                                    $('.close').click();
                                }, 2000);
                                setTimeout(function() {
                                    if (data.userinfo.user_group == 'admin') {
                                        window.location.href = "admin.php";
                                    } else if (data.userinfo.user_group == 'host') {
                                        window.location.href = "host.php";
                                    } else {
                                        location.reload();
                                    }
                                }, 2300);
                                // $loginLink.remove();
                                // $signupLink.remove();
                                // $currentUser = $('<a class="username" href="#">Welcome, ' + data.userinfo.firstname + '</a>');
                                // $navSeparator.after($currentUser);

                            } else {
                                $alert = $('<div class="alert alert-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;Invalid username or password!</div>');
                                $usernameInput.val('');
                                $passwordInput.val('');
                                $loginAlert.append($alert);
                                $alert.hide();
                                $alert.fadeIn(300);
                            }
                        }
                    });
                }
                return false;
            });
        });
        return false;
    });

    $signupLink.on('click', function(e) {
        var link = urlBase + $(this).attr('href');
        $.get(link).done(function(data) {
            $signupBlock = $(data).filter('div').html();
            showModal('Sign up', $signupBlock);

            var $signupUserForm = $('.sign-up-user-form');
            var $signupUserSubmmit = $('input[name="sign-up-user-submit"]');
            // $('.alert').remove();
            var postUrl = $signupUserForm.attr('action'),
                $signUpUserAlert = $('.sign-up-user-alert'),
                $usernameInput = $('input[name="username"]'),
                $passwordInput = $('input[name="password"]'),
                $confirmPassword = $('input[name="confirmPassword"]'),
                $firstnameInput = $('input[name="firstName"]'),
                $lastnameInput = $('input[name="lastName"]'),
                $emailInput = $('input[name="email"]'),
                $phoneInput = $('input[name="phone"]');
            // $signupUserForm.validator();
            $.validator.setDefaults({
                debug: true,
                success: "valid"
            });
            var validator = $signupUserForm.validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 5
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirmPassword: {
                        required: true,
                        minlength: 6,
                        equalTo: "#inputPassword"
                    },
                    firstname: "required",
                    lastname: "required",
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        phoneUS: true
                    }

                },
                messages: {
                    username: {
                        required: "Username is required!",
                        minlength: "Username is too short."
                    },
                    password: {
                        required: "Password is required!",
                        minlength: "Password is too short."
                    },
                    confirmPassword: {
                        required: "Please enter your password again!",
                        minlength: "Password is too short.",
                        equalTo: "Passwords are not match!"
                    },
                    firstName: "First name is required!",
                    lastName: "Last name is required!",
                    email: {
                        required: "Email is required!",
                        email: "Please enter correct email address!"
                    },
                    phone: {
                        required: "Phone number is required!",
                        phoneUS: "Please enter correct phone number!"
                    }
                },
                highlight: function(element) {
                    $(element).closest('.has-feedback').addClass('has-error').children('.glyphicon').addClass('glyphicon-remove');
                },
                unhighlight: function(element) {
                    $(element).closest('.has-feedback').removeClass('has-error').children('.glyphicon').removeClass('glyphicon-remove');
                },
                errorElement: 'span',
                errorClass: 'help-block',
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },
            });
            $signupUserSubmmit.on('click', function() {
                if ($signupUserForm.valid()) {
                    var formData = new FormData();
                    formData = 'username=' + $usernameInput.val() + '&password=' + $passwordInput.val() + '&firstname=' + $firstnameInput.val() + '&lastname=' + $lastnameInput.val() + '&email=' + $emailInput.val() + '&phone=' + $phoneInput.val();
                    $.ajax({
                        type: 'POST',
                        url: postUrl,
                        data: formData,
                        success: function(data) {
                            data = $.parseJSON(data);
                            if (data.success) {
                                $signupUserForm.fadeOut(300);
                                $alert = $('<div class="alert alert-success"><span class="glyphicon glyphicon-ok-sign"></span>&nbsp;&nbsp;' + data.message + '<p>You will sign in now with your new account.</p></div>');
                                $signUpUserAlert.append($alert);
                                $alert.hide();
                                $alert.fadeIn(300);
                                setTimeout(function() {
                                    // $('.close').click();
                                    $.ajax({
                                        type: 'POST',
                                        url: 'login.logic.php',
                                        data: 'username=' + $usernameInput.val() + '&password=' + $passwordInput.val(),
                                        success: function(data) {
                                            data = $.parseJSON(data);
                                            if (data.success) {
                                                var userinfo = $.parseJSON(data.userinfo);
                                                data.userinfo = userinfo;
                                            }
                                            if (data.success) {
                                                $('.close').click();
                                                location.reload();
                                            } else {
                                                console.log(data);
                                            }
                                        }
                                    });
                                }, 4000);
                            } else {
                                $alert = $('<div class="alert alert-danger"><span class="glyphicon glyphicon-remove-sign"></span>&nbsp;&nbsp;' + data.message + '</div>');
                                $signUpUserAlert.append($alert);
                                $alert.hide();
                                $alert.fadeIn(300);
                            }
                        }
                    });
                }
                return false;
            });
        });
        return false;
    });

    // sign up restaurant link handler
    var $signupRestaurant = $('.sign-up-restaurant');
    $signupRestaurant.on('click', function () {
        var cookie_user = $.cookie('login_user'),
            cookie_user_id = $.cookie('login_user_id'),
            cookie_firstname = $.cookie('login_user_firstname');
        console.log(cookie_user, cookie_user_id, cookie_firstname);
        if (cookie_user === undefined || cookie_user_id === undefined  || cookie_firstname === undefined) {
            $login.click();
            return false;
        }
    });

});
