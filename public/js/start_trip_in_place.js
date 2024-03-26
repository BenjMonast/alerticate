// This file is for use in edit_trip or create_trip
$(function() {
    $('#end-time').prop('required', $('#active').prop('checked'));

    window.sameDay = function(d1, d2) {
        return d1.getFullYear() === d2.getFullYear() &&
            d1.getMonth() === d2.getMonth() &&
            d1.getDate() === d2.getDate();
    }
    window.start_date = $('#start-date').val()

    $('#end-time').clockTimePicker();
    $('#active').prop('checked', false);
    $('#active').change(function() {
        if (this.checked) {
            $('#starttrip-block').show();
            $('.starttrip-fields').prop('required', true);
        } else {
            $('#starttrip-block').hide();
            $('.starttrip-fields').prop('required', false);
        }
    });

    $('#none-checkbox').on('input', function() {
        if (this.checked) {
            window.start_date = $('#start-date').val()
            $('#start-date').val("");
        } else {
            $('#start-date').val(window.start_date);
        }
    });

    $('#start-date').on('change', function() {
        $('#none-checkbox').prop('checked', false);
    });

    $('#form').submit(function() {
        var error = $('#error')
        error.text('');

        if ($('#active')[0].checked) {// Adding [0] turns it into a standard JS object
            var now = new Date();
            var current_date = new Date().setHours(0,0,0,0);
            var start_date = $('#start-date').val().replace(/-/g, "/");
            start_date = new Date(start_date);
            var end_date = $('#end-date').val().replace(/-/g, "/");
            var end, hour;
            [hour, minute] = $('#end-time').val().split(':');
            end_date = new Date(end_date);
            end_date.setHours(hour, minute);

            if (start_date < current_date) {// If the start_date is before today
                error.text('The start date must be after today');
                return false;
            }
            
            if (start_date > end_date) {
                error.text('The start date can\'t come after the end date');
                return false;
            }

            if (end_date < now) {
                error.text('The end date must be after the current time');
                return false;
            }
        }
        
        return true;
    });
});