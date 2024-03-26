@extends('layouts.main')

@section('title', 'Start a new trip')

@section('head')
    {{-- <script src="{{ asset('js/jquery-clock-timepicker.min.js') }}"></script> --}}
    <script src="{{ asset('js/start_trip_in_place.js') }}"></script>
    <script src="{{ asset('js/moment-with-locales.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}" />
    <style>
        .container, .row, .col-md-12 {
            display: block;
        }
        html, body {
            height: 100%;
        }
    </style>
    <script>
        $(function() {
            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });
            $('#end-time').datetimepicker({
                format: 'HH:mm'
            });
        });
    </script>
@endsection

@section('body')
<form method="POST" action="{{ action('TripController@create') }}" accept-charset="UTF-8" id="form" role="form" class="bg-light container" style="display: block;overflow: auto;margin-bottom:30px;padding-bottom:15px;">
    {{ csrf_field() }}
    <div class="form-group">
        <div class="col-md-12 bg-light">
            <div class="card-body text-center">
                <h2 class="form-heading">CREATE TRIP</h2>
            </div>
        </div>
    </div>

    <div class="form-group">
        <input size="40" name="address" type="text" id="address" class="form-control input-md" placeholder="Destination">
    </div>
    <div id="map" style="width:100%;height:500px;"></div>
    <input id="lat" name="lat" type="hidden" value="35">
    <input id="lon" name="lon" type="hidden" value="-120.5">
    <input id="form-address" name="formatted_address" type="hidden" value="Unnamed Road, Nipomo, CA 93444, USA">
    <div class="form-group" style="margin-bottom: 0;margin-top:10px;">
        <label>Which contacts should be notified?</label>
        <div id='contacts'>
            <ul style='list-style-type:none;margin-bottom:0;'>
                @foreach ($contacts as $key=>$c)
                <li>
                    <input type="checkbox" name="contacts[{{ ++$key }}]" id="{{ $c->id }}" value="{{ $c->id }}" checked>
                    <label for="{{ $c->id }}">{{ $c->firstname }} {{ $c->lastname }}</label>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="form-group" style="margin:0">
        <label for="end-date">Date of Return: </label>
        <div style="position: relative">
            <input class="starttrip-fields form-control" id="datepicker" name="end-date" type="text" data-toggle="datetimepicker" data-target="#datepicker" id="end-date" required>
        </div>
        <br>
        <label for="end-time">Time of Return: </label>
        <div style="position:relative">
            <input class="starttrip-fields form-control" name="end-time" type="text" id="end-time" required>
        </div>
        <br>
        <input type="checkbox" name="email" id="email-checkbox">
        <label for="email-checkbox">My contacts will be notified that I started this trip</label>
        <br>
        <button class='btn btn-primary d-flex justify-content-center' style='width:100%;margin-top:10px'>Start Trip</button>
    </div>
</form>
{{-- <div class="row profile">
    <div class="col-md-12 bg-light px-2">
        <div class="py-5 bg-primary text-white text-center">
            <div class="card-body">
                <img src='{{asset("image/trip.png")}}' style="width:auto;">
                <h2 class="form-heading">CREATE TRIP</h2>
                <hr class="colorgraph">
            </div>
        </div>
        <form method="POST" action="{{ action('TripController@create') }}" accept-charset="UTF-8" id="form" role="form">
                {{ csrf_field() }}
    
                <div class="col-xs-12">
                    <div class="form-group">
    
    
                        <input size="40" name="address" type="text" id="address" class="form-control input-md" placeholder="Destination">
                    </div>
                </div>
                <div id="map" style="width:1110px;height:500px;"></div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <label>Which contacts should be notified?</label>
                        <div id='contacts'>
                            <ul style='list-style-type:none'>
                                @foreach ($contacts as $key=>$c)
                                <li>
                                    <input type="checkbox" name="contacts[{{ ++$key }}]" id="{{ $c->id }}" value="{{ $c->id }}"
                                        checked>
                                    <label for="{{ $c->id }}">{{ $c->firstname }} {{ $c->lastname }}</label>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <input id="active" name="active" type="checkbox" value="1" />
                        <label for="active">I would like to start the trip now</label>
                        <br><br>
                        <div id="starttrip-block" style="display:none">
                            @include('includes.start_trip')
                            <br>
                        </div>
                        <input id="lat" name="lat" type="hidden" value="35">
                        <input id="lon" name="lon" type="hidden" value="-120.5">
    
                        <input id="form-address" name="formatted_address" type="hidden" value="Unnamed Road, Nipomo, CA 93444, USA">
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 col-sm-offset-3"><input id="save-trip" type="submit" value="Save" class="btn btn-primary btn-block btn-lg"
                        tabindex="7"></div>
            </form>
    </div>
</div> --}}
{{-- <form method="POST" action="{{ action('TripController@create') }}" accept-charset="UTF-8" id="form">
    {{ csrf_field() }}
    <label for="address">Destination: </label>
    <input size="40" name="address" type="text" id="address">
    <div id="map" style="width:500px;height:500px;"></div>
    <br>
    <label>Which contacts should be notified?</label>
    <div id='contacts'>
        <ul style='list-style-type:none'>
            @foreach ($contacts as $key=>$c)
            <li>
                <input type="checkbox" name="contacts[{{ ++$key }}]" id="{{ $c->id }}" value="{{ $c->id }}" checked>
                <label for="{{ $c->id }}">{{ $c->firstname }} {{ $c->lastname }}</label>
            </li>
            @endforeach
        </ul>
    </div>
    <label for="end-date">Date of Return: </label>
    <input class="starttrip-fields" name="end-date" type="date" value="{{ substr(\Carbon\Carbon::now($timezone), 0, 10)}}" id="end-date" required>
    <br>
    <label for="end-time">Time of Return: </label>
    <input class="starttrip-fields" name="end-time" type="text" id="end-time" required>
    <br>
    <input type="checkbox" name="email" id="email-checkbox">
    <label for="email-checkbox">My contacts will be notified that I started this trip</label>
    <p id='error'></p>
    <input id="lat" name="lat" type="hidden" value="35">
    <input id="lon" name="lon" type="hidden" value="-120.5">
    <input id="form-address" name="formatted_address" type="hidden" value="Unnamed Road, Nipomo, CA 93444, USA">
    <input id="save-trip" type="submit" value="Save">
</form> --}}
<script src='{{asset("js/map.js")}}'></script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}&callback=myMap'></script>
@endsection
