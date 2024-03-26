@extends('layouts.main')

@section('title', 'View Your Trips!')

@section('head')
    <script src='https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}'></script>
    <script>
        $(document).ready(function() {
            $('.fa-pencil-alt').each(function(i, icon) {
                icon = $(icon);
                icon.on('click', function() {
                    let id = icon.parent().siblings('.id').val();
                    document.location = '{{url("/trips/edit_trip")}}?id=' + id;
                });
            });

            $('.fa-times').each(function(i, icon) {
                icon = $(icon);
                icon.on('click', function() {
                    let id = icon.parent().siblings('.id').val();
                    document.location = '{{url("/trips/remove_trip")}}?id=' + id;
                });
            });
        });
    </script>
    <style>
        a {
            color: blue;
        }
        table {
            margin-top: 5px;
            margin-bottom: 15px;
        }
        table, td, tr, th {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 5px;
        }
        td.action {
            text-align: center;
        }
        h1 {
            margin-top: 0px;
        }
    </style>
@endsection

@section('body')
    <h1>{{ $firstname }}'s Trips</h1>
    <table>
        <tr>
            <th>Action</th>
            <th>Name</th>
            <th>Note</th>
            <th>Destination</th>
            <th></th>
        </tr>
@foreach ($trips as $trip)
        <tr>
            <form action='{{ action('TripController@showStartTripForm') }}' method='GET'>
                <td class="action">
                    <i class="fas fa-pencil-alt"></i>
                    <i class="fas fa-times"></i>
                </td>
                <td>{{ $trip['name'] }}</td>
                <td>{{ $trip['note'] }}</td>
                <td class='address'>{{ $trip['destination_address'] }}</td>
                <input type="hidden" class="id" name='id' value="{{ $trip['trip_id'] }}">
                <td><button>Start Trip</button></td>
            </form>
            <input type="hidden" class="lat" name='lat' value="{{ $trip['destination_lat'] }}">
            <input type="hidden" class="lon" name='lon' value="{{ $trip['destination_lon'] }}">
        </tr>
@endforeach
    </table>
    <a href="trips/create_trip">Create A New Trip</a>
@endsection