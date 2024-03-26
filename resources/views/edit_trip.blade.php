@extends('layouts.main')

@section('title', 'Edit your trip')

@section('head')
    <script src="{{ asset('js/jquery-clock-timepicker.min.js') }}"></script>
    <script src="{{ asset('js/start_trip_in_place.js') }}"></script>
    <style>
        .error {
            color: red;
        }
    </style>
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-offset-2 col-md-offset-3 bg-light px-4" style='padding-bottom:15px;margin-bottom:15px'>
            <div class="text-black text-center ">
                <div class=" ">
                    <div class="card-body">
                        <h2 class="form-heading">SIGN UP</h2>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ action('Auth\RegisterController@register') }}" accept-charset="UTF-8" id='form'
                role="form">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">

                            <input required="required" name="firstname" type="text" id="firstname" class="form-control input-md"
                            value="{{ $name }}" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input required="required" name="lastname" type="text" id="lastname" class="form-control input-md"
                                placeholder="Last Name">
                        </div>
                    </div>
                </div>

                <div class="form-group" style='margin-bottom:0'>
                    <input required="required" name="email" type="text" id="email" data-action='{{ action("Auth\RegisterController@emailUsed") }}'
                        class="form-control input-md" placeholder="Email Address" />
                    <label class='error' id='emailErr'></label>
                    @if (isset($error))
                    <p class='error'>{{ $error }}</p>
                    @else
                    <br>
                    @endif
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group" style='margin-bottom:0'>
                            <input required="required" name="password" type="password" value="" id="password" class="form-control input-md"
                                placeholder="Password" />
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group" style='margin-bottom:0'>
                            <input required="required" name="cpassword" type="password" value="" id="cpassword" class="form-control input-md error"
                                placeholder="Confirm Password" />
                            <label class='error' id='cPasswordErr'></label></div>
                    </div>


                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <input required="required" name="birthdate" type="date" value="{{ substr(\Carbon\Carbon::now(), 0, 10) }}"
                                id="birthdate" class="form-control input-md" placeholder="Date of Birth" />
                            <label class="error" id="bdateErr" style="display:none;"></label>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="form-group">
                            <select required="required" id="gender" name="gender" class="form-control input-md">
                                <option value="" selected="selected">Choose</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                                <option value="N">Non Binary</option>
                            </select>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <label for="phone">Phone Number:</label><br />
                        <div class="form-group">
                            {!! countrySelector('1', 'country-select', 'country', 'form-control form-control-sm input-md','style="height:32px;width:70px;display:inline"') !!}

                            <input style="display:inline;width:calc(100% - 91px)" id='phone' name='phone' placeholder="8888888888" title="8888888888" pattern="[0-9]{10}" maxlength="10" required class="form-control input-md">
                        </div>

                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label for="address">Address (WIP): </label>
                            <input name="address" type="text" id="address" class="form-control input-md"></div>
                    </div>
                    <div id="map" style="width:100%;height:500px;margin:0px 17px 10px;"></div>
                    <input id="lat" name="lat" type="hidden" value='38'>
                    <input id="lon" name="lon" type="hidden" value='-122'>


                    <input id="submit" type="submit" value="Register" class="btn btn-primary btn-block btn-lg" style="width:100%;margin:0 17px"/>

            </form>
        </div>
    </div>
</div>
{{-- <form method="POST" action="{{action('TripController@edit')}}" accept-charset="UTF-8" id="form">
    {{ csrf_field() }}
    <label for="trip_name">Name: </label>
    <input required="required" name="trip_name" type="text" value="{{ $name }}" id="trip_name">
    <br>
    <label for="note">Note: </label>
    <br>
    <textarea name="note" cols="50" rows="10" id="note">{{ $note }}</textarea>
    <br>
    <label for="address">Destination: </label>
    <input size="40" name="address" type="text" id="address" value="{{ $destination_address }}">
    <button type="button" id="show">Show</button>
    <div id="map" style="width:500px;height:500px;"></div>
    <div id='contacts'>
        <ul style='list-style-type:none'>
            @foreach ($contacts as $key=>$c)
            <li>
                @if (in_array($c->id, $selectedContactIds))
                    <input type="checkbox" name="contacts[{{ ++$key }}]" id="{{ $c->id }}" value="{{ $c->id }}" checked> 
                @else
                    <input type="checkbox" name="contacts[{{ ++$key }}]" id="{{ $c->id }}" value="{{ $c->id }}">
                @endif
                <label for="{{ $c->id }}">{{ $c->firstname }} {{ $c->lastname }}</label>
            </li>
            @endforeach
        </ul>
    </div>
    <input id="lat" name="lat" type="hidden" value="{{ $destination_lat }}">
    <input id="lon" name="lon" type="hidden" value="{{ $destination_lon }}">
    <input id="form-address" name="formatted_address" type="hidden" value="Unnamed Road, Nipomo, CA 93444, USA">
    <input name="id" type="hidden" value="{{ $id }}">

    <input id="active" name="active" type="checkbox" value="1">
    <label for="active">I would like to start the trip now</label>
    <br><br>
    <div id="starttrip-block" style="display:none">
        @include('includes.start_trip')
        <br><br>
    </div>
    <input type="submit" value="Update">
</form> --}}

<script src='{{asset("js/map.js")}}'></script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}&callback=myMap'></script>
@endsection
