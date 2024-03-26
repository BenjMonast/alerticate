$(document).ready(function () {
    $('#submit').attr('disabled', false);

    var reg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    window.now = new Date();
    now.setHours(0,0,0,0);

    //Confirm Email
    $('#email').on('change', function() {
        if (!reg.test($('#email').val().toLowerCase())){
            $('#emailErr').text('Invalid EMail Address');
        } else {
            // Check if email is used using AJAX
            let action = $(this).data('action');
            
            $.post(action, {
                _token: $('[name="_token"]').val(),
                email: $(this).val()
            }, function(data, status) {
                emailUsed = (data == 'true');

                if (emailUsed) {
                    $('#emailErr').text('An account already exists with this email');
                } else {
                    $('#emailErr').text('');
                }
            });

        }
    });
    
    //Confirm password
    $('#password, #cpassword').on('change', function() {
        $('#cPasswordErr').text('');
        var password = $('#password').val();
        if(password == '' || $('#cpassword').val() == '') {
            return;
        }
        if($('#cpassword').val() != $('#password').val()) {
            $('#cPasswordErr').append('<li>Passwords do not match</li>');
        } else {
            $('#cPasswordErr').text('');
        }
        if(!(password.match(/[a-z]/g) && password.match(/[A-Z]/g) && password.match(/[0-9]/g) && password.length >= 8)) {
            $('#cPasswordErr').append('<li>Password must contain one uppercase letter, one lowercase letter, a number, and it must be at least 8 characters</li>');
        }
    });

    $('#birthdate').on('change', function() {
        var birthdate = new Date(this.value.replace(/-/g, '/'));
        if (now < birthdate) {
            $('#bdateErr').text('Invalid birthday');
        } else {
            $('#bdateErr').text('');
        }
    });

    function onSubmit() {
        return (
            $('#cPasswordErr').text() == '' &&
            $('#emailErr').text() == '' &&
            $('#bdateErr').text() == ''
        );
    };
});
