$(document).ready(function() {
    var menu_data = {};
    var guest_reservation = false;
    var current_user_id = $.cookie('login_user_id'),
        current_user_email = $.cookie('login_user_email'),
        current_user_phone = $.cookie('login_user_phone'),
        guest_message, guest_fullname;

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
        $peopleSizePicker.selectpicker('setStyle', 'btn-default btn-sm');
        $datePiker.selectpicker('setStyle', 'btn-default btn-sm');
        $timePiker.selectpicker('setStyle', 'btn-default btn-sm');
    }
    addRestaurantIds();

    function addRestaurantIds() {
        $('button[title="What date?"]').each(function(key, button) {
            // console.log($(button).parent().siblings('select'));
            $(button).attr('data-restaurant-id', $(button).parent().siblings('select').data('restaurant-id'));
            $(button).on('click', function(e) {
                var restaurant_id = $(this).data('restaurant-id');
                // console.log($('select[name=people-size][data-restaurant-id="' + restaurant_id + '"]'));
                if ($('select[name=people-size][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose how many people first :)", restaurant_id, 1500);
                    return false;
                } else {
                    if (menu_data['restaurant_' + restaurant_id] === undefined) {
                        showLoading(this, restaurant_id);
                        ajaxGetmenu_data(restaurant_id);
                        return false;
                    }
                }
            });
        });
        $('button[title="What time?"]').each(function(key, button) {
            $(button).attr('data-restaurant-id', $(button).parent().siblings('select').data('restaurant-id'));
            $(button).on('click', function() {
                var restaurant_id = $(this).data('restaurant-id');
                if ($('select[name=people-size][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose how many people first :)", restaurant_id, 1500);
                    return false;
                } else if ($('select[name="date"][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose what date first :)", restaurant_id, 1500);
                    return false;
                }
            });
        });
        $('button[title="How many people?"]').each(function(key, button) {
            $(button).attr('data-restaurant-id', $(button).parent().siblings('select').data('restaurant-id'));
            $(button).on('click', function() {

            });
        });
        $('input[name="reservation-submit"]').each(function(key, submit) {
            $(submit).on('click', function (e) {
                e.preventDefault();
                var _this = this;
                var restaurant_id = $(this).data('restaurant-id');

                if ($('select[name=people-size][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose how many people first :)", restaurant_id, 1500);
                } else if ($('select[name=people-size][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose what date first :)", restaurant_id, 1500);
                    return false;
                } else if ($('select[name="time"][data-restaurant-id="' + restaurant_id + '"]').val() == 'default') {
                    showWarning(this, "Please choose what time first :)", restaurant_id, 1500);
                    return false;
                } else if (current_user_id === undefined || current_user_email === undefined || current_user_phone === undefined) {
                    var modal_message = '<div class="container" style="width: 100%;"><form><p>Unregistered user can also make reservations on FineTable!</p><p>Please provide your baisc contact infomation to the restaurant :)</p><div class="form-group"><label>Full name</label><input type="text" class="form-control" placeholder="full name" name="guest_fullname"></div><div class="form-group"><label>Email</label><input type="text" class="form-control" placeholder="email" name="guest_email"></div><div class="form-group"><label>Phone number</label><input type="text" class="form-control" placeholder="phone number" name="guest_phone"></div><button class="btn btn-primary" name="confirm-input">Reserve the table</button></form><div class="row login-alert"></div></div>';
                    showModal('Welcome guest', modal_message);
                    guest_reservation = true;
                    $('button[name="confirm-input"]').on('click', function (e) {
                        e.preventDefault();
                        if ($('input[name="guest_phone"]').val().length === 0 || $('input[name="guest_email"]').val() === 0) {
                            return false;
                        } else {
                            current_user_id = null;
                            current_user_email = $('input[name="guest_email"]').val();
                            current_user_phone = $('input[name="guest_phone"]').val();
                            guest_fullname = $('input[name="guest_fullname"]').val();
                            guest_message = '{ "fullname": "' + guest_fullname + '", "email": "' + current_user_email + '", "phone": "' + current_user_phone + '"}';
                            $('button.close').click();
                            _this.click();
                        }
                    });
                } else {
                    showLoading(this, restaurant_id);
                    console.log('going to submit reservation form.');
                    form_object = $(this).closest('.reservation-form').get(0);

                    var form_data = new FormData();
                    console.log($('select[name="people-size"][data-restaurant-id="' + restaurant_id + '"]').val(), $('select[name="date"][data-restaurant-id="' + restaurant_id + '"]').val(), $('select[name="time"][data-restaurant-id="' + restaurant_id + '"]').val());

                    form_data.append('operation', 'make_reservation');
                    form_data.append('people_size', $('select[name="people-size"][data-restaurant-id="' + restaurant_id + '"]').val());
                    form_data.append('date', $('select[name="date"][data-restaurant-id="' + restaurant_id + '"]').val());
                    form_data.append('time', $('select[name="time"][data-restaurant-id="' + restaurant_id + '"]').val());
                    form_data.append('restaurant_id', restaurant_id);
                    form_data.append('user_id', current_user_id);
                    form_data.append('user_email', current_user_email);
                    form_data.append('user_phone', current_user_phone);
                    if (guest_reservation) {
                        form_data.append('guest_info', JSON.stringify(guest_message));
                        form_data.append('guest_reservation', true);
                        form_data.append('guest_fullname', guest_fullname);
                    }
                    console.log(form_data);
                    $.ajax({
                        type: 'POST',
                        url: 'make-reservation.logic.php',
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            data = $.parseJSON(data);
                            hideLoading(restaurant_id);
                            if (data.success) {
                                var modal_message ='<div class="container"><div class="row"><p>You have made a reservation with restaurant,</p><p class="text-primary"> <b>'+data.restaurant_info.name+'</b></p><p><b>Date:</b> ' + data.restaurant_info.date + '</p><p><b>Time:</b> ' + data.restaurant_info.time + '</p><p><b>Address:</b> ' + data.restaurant_info.address + '</p><p>Thank you for using FineTable! We will send a confirmation email very shortly :)</p></div></div>';
                                showModal('Congratulations!', modal_message);
                            } else {
                                showWarning(_this, data.message, restaurant_id, 4000);
                            }
                        }
                    });
                }
            });
        });
    }

    function ajaxGetmenu_data(restaurant_id) {
        console.log(restaurant_id);
        $.ajax({
            type: 'POST',
            url: 'make-reservation.logic.php',
            data: {
                operation: 'get_menu_data',
                restaurant_id: restaurant_id
            },
            success: function(data) {
                data = $.parseJSON(data);
                if (data.success) {
                    menu_data['restaurant_' + restaurant_id] = data.menu_data;
                    hideLoading(restaurant_id);
                    // console.log(menu_data);
                    updateMenus(menu_data);
                }
            }
        });
    }

    function showLoading(element, restaurant_id) {
        $loading = $('<div class="menu-data-loading restaurant_' + restaurant_id + '"><div class="menu-data-loading-icon"><span  class="glyphicon glyphicon-refresh animation"></span>&nbsp;Loading...</div></div>');
        $loading.hide();
        $(element).closest('.result-row').prepend($loading);
        $loading.fadeIn(300);
    }

    function hideLoading(restaurant_id) {
        $loading = $('.menu-data-loading.restaurant_' + restaurant_id);
        $loading.fadeOut(300);
        setTimeout(function() {
            $loading.remove();
        }, 300);
    }

    function updateMenus(menu_data) {
        $.each(menu_data, function(key, data) {
            // console.log(data);
            var $select = $('select[data-restaurant-id="' + data.restaurant_id + '"]');
            var $people_picker = $($select.get(0)),
                $date_picker = $($select.get(1)),
                $time_picker = $($select.get(2));
            // console.log($date_picker);
            // console.log($time_picker);
            // console.log($people_picker);
            // console.log($date_picker);
            $date_picker.html('');
            $date_picker.append($('<option value="default">What date?</option>'));
            $.each(data.menu_data, function(key, data) {
                // console.log(key);
                $date_picker.append($('<option value="' + key + '">' + key + '</option>'));
            });
            $date_picker.selectpicker('refresh');
            $('button[title="What date?"][data-restaurant-id="' + data.restaurant_id + '"]').click();

            $date_picker.on('change', function(e) {
                var date = $(this).val();
                var data_for_time_picker = data.menu_data[date];
                // console.log(data_for_time_picker);
                $time_picker.html('');
                $time_picker.append($('<option value="default">What time?</option>'));
                $.each(data_for_time_picker, function(key, data) {
                    if (parseInt($people_picker.val()) < parseInt(data)) {
                        $time_picker.append($('<option value="' + key + '">' + key + '</option>'));
                    }
                });
                $time_picker.selectpicker('refresh');
            });

            $people_picker.on('change', function(e) {
                console.log($($time_picker.children().get(0)).val());
                $time_picker.val($($time_picker.children().get(0)).val());
                $time_picker.selectpicker('refresh');
                $date_picker.val($($date_picker.children().get(0)).val());
                $date_picker.selectpicker('refresh');
            });
        });
    }

    function showWarning(element, message, restaurant_id, duration) {
        $warning = $('<div class="menu-warning restaurant_' + restaurant_id + '"><div class="menu-warning-content"><p class="text-info">' + message + '</p></div></div>');
        $warning.hide();
        $(element).closest('.result-row').prepend($warning);
        $warning.fadeIn(300);
        setTimeout(function() {
            $warning.fadeOut(300);
            setTimeout(function() {
                $warning.remove();
            }, 300);
        }, duration);
    }
});
