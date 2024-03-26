@extends('layouts.main')

@section('head')
    <script src="{{ asset('js/jquery-clock-timepicker.min.js') }}"></script>
    <script>
        $(function() {
            $('#end-time').clockTimePicker();
            $('#cancel').click(function() {
                document.location = '{{ url('/trips') }}';
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

                var now = new Date();
                var current_date = new Date().setHours(0,0,0,0);
                var start_date = $('#start-date').val().replace(/-/g, "/");
                start_date = new Date(start_date);
                var end_date = $('#end-date').val().replace(/-/g, "/");
                var end, hour;
                [hour, minute] = $('#end-time').val().split(':');
                end_date = new Date(end_date);
                end_date.setHours(hour, minute);

                if (start_date < current_date) {// If the start_date is after today
                    error.text('The start date must be after today');
                    return false;
                }
                
                if (start_date > end_date) {
                    error.text('The start date can\'t come after the end date');
                    return false;
                }
                
                if (end_date < now) {
                    error.text('The end date/time must be after today');
                    return false;
                }
                
                return true;
            });
        });
    </script>
    <style>
        #error {
            color: red;
        }
    </style>
@endsection

@section('body')
    <form action='{{ action('TripController@startTrip') }}' method="POST" id='form'>
        {{ csrf_field() }}
        <input type="hidden" name='id' value='{{ $id }}'>
        @include('includes.start_trip')
        <ul style='margin-bottom: 0px;'>
            @foreach ($contacts as $c)
                <li>{{ $c['firstname'] . $c['lastname']}}</li>
            @endforeach
        </ul>
        <br>
        <button>Start Trip</button>
        <button id='cancel' type='button'>Cancel</button>
    </form>
@endsection