<?php include 'includes/countries.php' ?>
@extends('layouts.main')

@section('title', 'Edit Profile')

@section('head')
<style>
    .phone {
        width: 25px;
        border: none;
    }
    .error {
        color: red;
        margin-left: 5px;
    }
    p {
        margin-top: 5px;
        margin-bottom: 5px;
    }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<script src="{{ asset('js/start_trip_in_place.js') }}"></script>
<script src="{{ asset('js/moment-with-locales.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/bootstrap-datetimepicker.js') }}"></script>
<link rel="stylesheet" href="{{ asset('css/bootstrap-datetimepicker-build.css') }}" />
<script>
    $(function() {
        $('#birthdate').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('input[type=file]').change(function(){
            var preview = document.querySelector('img'); //selects the query named img
            var file    = document.querySelector('input[type=file]').files[0]; //sames as here
            var reader  = new FileReader();

            reader.onloadend = function () {
                preview.src = reader.result;
            }

            if (file) {
                reader.readAsDataURL(file); //reads the data as a URL
            } else {
                preview.src = "";
            }
        });
    });
</script>
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col-sm-12 col-sm-offset-2 col-md-offset-3 bg-light px-4" style='padding-bottom:15px;margin-bottom:15px'>
            <div class="text-black text-center ">
                <div class=" ">
                    <div class="card-body">
                        <h2 class="form-heading">Edit Profile</h2>
                    </div>
                </div>
            </div>
            <form method="POST" action="{{ action('ProfileController@update') }}" enctype="multipart/form-data" accept-charset="UTF-8" id='form' role="form">
                {{ csrf_field() }}
                <div style='margin-bottom:80px'>
                    <img src="{{url('/uploads/avatars/' . $picture)}}" width='150' height='150' style="float:left;border-radius:50%;margin-right:25px;">
                    <label style='margin-top:16px;'>(Optional) Upload a Picture</label>
                    <br>
                    <label>Make sure that you can be recognized from this image.</label>
                    <br>
                    <input type="file" name="picture">
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <label for="firstname">First Name:</label>
                        <div class="form-group">

                            <input required="required" name="firstname" type="text" id="firstname" class="form-control input-md"
                                value="{{ $firstname }}" placeholder="First Name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <label for="lastname">Last Name:</label>
                        <div class="form-group">
                            <input required="required" name="lastname" type="text" id="lastname" class="form-control input-md"
                                value="{{ $lastname }}" placeholder="Last Name">
                        </div>
                    </div>
                </div>
                <div class="row">


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <label for="birthdate">Date of Birth:</label>
                        <div class="form-group">
                            <input required="required" name="birthdate" type="text" value="{{ substr(\Carbon\Carbon::now(), 0, 10) }}"
                                id="birthdate" class="form-control input-md" placeholder="Date of Birth"  value="{{ $birthdate }}" />
                            <label class="error" id="bdateErr" style="display:none;"></label>
                        </div>
                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <label for="phone">Phone Number:</label><br />
                        <div class="form-group">
                            {!! countrySelector($country, 'country-select', 'country', 'form-control form-control-sm input-md','style="height:32px;width:70px;display:inline"') !!}

                            <input style="display:inline;width:calc(100% - 91px)" id='phone' name='phone' placeholder="8888888888" title="8888888888" pattern="[0-9]{10}" maxlength="10" required class="form-control input-md" value='{{ $phone }}'>
                        </div>

                    </div>


                    <div class="col-xs-12 col-sm-12 col-md-12">
                        <div class="form-group">
                            <label for="address">Address: </label>
                            <input name="address" type="text" id="address" class="form-control input-md"></div>
                    </div>
                    <div id="map" style="width:100%;height:500px;margin:0px 17px 10px;"></div>
                    <input id="lat" name="lat" type="hidden" value='{{$lat}}'>
                    <input id="lon" name="lon" type="hidden" value='{{$lon}}'>


                    <input id="submit" type="submit" value="Update Profile" class="btn btn-primary btn-block btn-lg" style="width:100%;margin:0 17px"/>

            </form>
        </div>
    </div>
</div>
{{-- <form method="POST" action="{{ action('ProfileController@update') }}" accept-charset="UTF-8" id="form">
    {{ csrf_field() }}
    
    <label for="firstname">First Name: </label>
    <input required="required" name="firstname" type="text" value="{{ $firstname }}" id="firstname">
    <br>
    
    <label for="lastname">Last Name: </label>
    <input required="required" name="lastname" type="text" value="{{ $lastname }}" id="lastname">
    <br><br>
    
    <label for="birthdate">Birth Date: </label>
    <input required="required" name="birthdate" type="date" value="{{ $birthdate }}" id="birthdate">
    <label class="error" id="bdateErr"></label>
    <br>
    
    <label for="gender">Gender: </label>
	{{ Form::select('gender', ['' => 'Choose', 'M' => 'Male', 'F' => 'Female', 'N' => 'Non Binary'], $gender, ['required' => 'required']) }}
    <br>
    
    <label for="phone">Phone Number: </label>
    {!! countrySelector($country, 'country-select', 'country') !!}
    <input value='{{ $phone }}' id='phone' name='phone' placeholder="8888888888" title="8888888888" pattern="[0-9]{10}" maxlength="10" required>
    <br>
    
    <label for="address">Address: </label>
    <input size="40" name="address" type="text" id="address">
	<div id="map" style="width:500px;height:500px;"></div>
    <br>
    
    <input id="lat" name="lat" type="hidden" value="{{ $lat }}">
    <input id="lon" name="lon" type="hidden" value="{{ $lon }}">
    <input id="submit" type="submit" value="Update">
</form> --}}
<script src='{{asset("js/verify_profile.js")}}'></script>
<script src='{{asset("js/profile_map.js")}}'></script>
<script>
    function init() {
        myMap({{$lat}}, {{$lon}});
    }
</script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}&callback=init'></script>
@endsection