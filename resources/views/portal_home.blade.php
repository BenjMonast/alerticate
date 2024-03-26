<?php include 'includes/countries.php' ?>
@extends('layouts.main')

@section('title', 'FindMe')

@section('head')
<script>
    $(function() {
        $('#cancel').on('click', function() {
            location.reload(true);
        });
    });
</script>
<script src='https://maps.googleapis.com/maps/api/js?key={{ env('MAPS_API_KEY') }}'></script>
<script>
    $(document).ready(function() {
        dialog = $('#dialog-form').dialog({
            autoOpen: false,
            height: 200,
            width: 310,
            modal: true,
            buttons: {
                'Confirm': function() {
                    $('#dialog-form form').submit();
                }, Cancel: function() {
                    dialog.dialog( "close" );
                }
            }
        });
        $( "#return" ).on( "click", function() {
            dialog.dialog( "open" );
        });

        // if (($("#contentContainer").offset().top + $("#contentContainer").height()) >= $(window).height()) {
        //     $('#contentContainer').css('margin-bottom', '30px');
        // }
        // Disable checkboxes as necessary
        function updateFormElements(row) {
            var email = $(row).children('td.email-cell').children('.email');
            var phone = $(row).children('td.phone-cell').children('.phone');
            var email_box = email.siblings('.email-checkbox'); 
            var phone_box = phone.siblings('.phone-checkbox'); 

            if (!email.val()) {
                email_box.prop('disabled', true);
                email_box.prop('checked', false);
                phone.prop('required', true);
            } else {
                email_box.attr('disabled', false);
                phone.prop('required', false);
            }
            
            if (!phone.val()) {
                phone_box.prop('disabled', true);
                phone_box.prop('checked', false);
                email.prop('required', true);
            } else {
                phone_box.attr('disabled', false);
                email.prop('required', false);
            }
        }

        $('table#edit tr:not(#header)').each(function(i, e) {
            updateFormElements(e);
        });

        $('.communication').on('input', function() {
            let row = $(this).parent().parent();
            updateFormElements(row);
        });

        window.newRows = -1;
        window.html = `<tr>
<td><button type="button" class="remove btn-sm btn-primary"><i class="fas fa-times"></i></button></td>
<td>
<div class="input-group">
<input name='index[firstname]' class='name form-control' required style="width: 70px">
<div class="input-group-prepend">
<span class="input-group-text bg-gray" style="border-left:0;border-right:0"> </span>
</div>
<input name='index[lastname]' class='name form-control' required style="width: 70px;">
</div>
</td>
<td class="email-cell">
<input name='index[email]' class='communication email form-control' required
style="width: 215px;display:inline" title='you@example.com' pattern='^(([^<>()\\[\\]\\.,;:\\s@"]+(\\.[^<>()\\[\\]\\.,;:\\s@"]+)*)|(".+"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$'>

</td>
<td class="phone-cell">
{!! countrySelector("1", NULL, "index[country]", 'form-control form-control-sm',
'required style="width:70px;display:inline"') !!}
<input class='communication phone form-control' name='index[phone]' placeholder="8888888888"
title="8888888888" pattern="[0-9]{10}" maxlength="10" required style="width: 115px;display:inline">

</td>
<input type="hidden" name="index[id]" value="index">
</tr>`;
        $('#edit-button').on('click', function() {
            document.location = './profile/edit';
        });
        $('#editContacts').on('click', function() {
            $('#view').hide();
            $('#edit').show();
            $('#cancel').show();
            $('#editContacts').hide();
            $('#removeContacts').hide();
            $('#addContacts').show();
            $('#saveContacts').show();
            $('#noContacts').hide();
            $('#edit-form').css('height','initial');
            $('.rounded').removeClass('col-md-8').addClass('col-md-12');
            $('.rounded-top').hide();
        });
        $('#cancel').on('click', function() {
            location.reload();
        })
        $('.remove').on('click', function() {
            let td = $(this).parent().parent();
            td.remove();
        });
        $('#addContacts').on('click', function() {
            let last_id = {{count($contacts)}};
            //Generate new row
            var row = html.replace(/index/g, newRows);
            window.newRows = newRows - 1;
            $('table#edit tr:last-of-type').after(row);
            $('.remove').on('click', function() {//I have to put this here again because this happens after document.ready
                let td = $(this).parent().parent();
                td.remove();
            });

            $('.communication').on('input', function() {
                let row = $(this).parent().parent();
                updateFormElements(row);
            });
        });
        var geocoder = new google.maps.Geocoder;
        var latlng = new google.maps.LatLng({{$userInfo['lat']}}, {{$userInfo['lon']}}); 
        geocoder.geocode({'location':latlng}, function (results, status) {
            if (results[0] !== null) {
                $('#address').html('<b>Address:</b> ' + results[0].formatted_address);
            }
        });
    });
</script>
@endsection

@section('body')
<div style="display:none;" id="dialog-form">
    <form action="{{ action('TripController@returned') }}" method="POST">
        {{ csrf_field() }}
        <input type="checkbox" name="email" id='email-checkbox' id="1" checked>
        <label for="email-checkbox">My contacts will be notified</label>
        <br>
        {{-- <button type="submit">Confirm</button>
        <button type='button' id='cancel'>Cancel</button> --}}
    </form>
</div>
<div class="container" id="contentContainer">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-sidebar rounded-top">
                <div class="profile-usertitle">
                    <div class="profile-usertitle-name">
                        <span>Your Trip</span>
                    </div>
@if ($contactsAlerted)
                    <p>Your contacts have been alerted</p>
                    <button id='return-late' class="btn-primary btn-sm trip-button" onclick="window.location = '{{url('trips/returnedlate')}}'">I Have Returned</button>
@elseif (!$hasContacts)
                    <span>You need to create contacts to start a trip</span>
                    <button class="btn-primary btn-sm trip-button" onclick="window.location = '{{url('trips/create_trip')}}'" disabled style='opacity:0.3'>Start Trip</button>
@elseif ($hasActiveTrip)
                    <p>You have an active trip</p>
                    <p id='address'>Destination: {{ $activeTripInfo['destination_address'] }}</p>
                    <p>You will be returning {{ $activeTripInfo['end'] }}</p>
                    <p>The following people will be notified:</p>
                    <ul>
                        @foreach ($activeTripInfo['contacts'] as $c)
                            <li>{{$c['firstname']}} {{$c['lastname']}}</li>
                        @endforeach
                    </ul>
                    <button class="btn-primary btn-sm trip-button" id="return">I Have Returned</button>
                    {{-- <button id='return'>I have returned</button>
                    <form action="{{ action('TripController@returned') }}" method="POST" id='form' hidden>
                        {{ csrf_field() }}
                        <input type="checkbox" name="email" id='email-checkbox' id="1" checked>
                        <label for="email-checkbox">My contacts will be notified</label>
                        <br>
                        <button type="submit">Confirm</button>
                        <button type='button' id='cancel'>Cancel</button>
                    </form> --}}
@else
                    <span>You do not currently have an active trip. You can start one below.</span>
                    <button class="btn-primary btn-sm trip-button" onclick="window.location = '{{url('trips/create_trip')}}'">Start Trip</button>
@endif
                </div>
                {{-- <div class="profile-usermenu">
                    <ul class="nav flex-column">
                        <li>
@if ($contactsAlerted)
                            <a class='nav-link' href='{{url("trips")}}'>I Have Returned</a>
@elseif ($hasActiveTrip)
                            <a class='nav-link' style='cursor:pointer' id="return">I Have Returned</a>
@else
                            <a class='nav-link' href='{{url("trips/create_trip")}}'>Start Your Trip</a>
@endif
                        </li>
                    </ul>
                </div> --}}
            </div>
        </div>
        <div class="col-md-8 bg-light px-2 rounded" style="height:100%;text-align:center;">
            <div class="profile-content">
                <form method='post' action="{{ action('ProfileController@editContact') }}" id='edit-form' role="form" style='height:80px'>
                    <div class=" py-2 text-black text-center ">
                        <div>
                            <div class="card-body" style="padding-bottom: 0px;padding-top: 8px">
                                <div class="d-flex justify-content-center">
                                    <div class="p-2">
                                        <h2 class="form-heading" id="contacts-header">Contacts</h2>
                                    </div>
                                    <div class="p-2">
                                        <button type="button" id='editContacts' class="btn-sm btn-primary">Edit <i class="fas fa-pencil-alt"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="buttonContainer">    
                        <button type="submit" style='display:none;' id='saveContacts' class="btn-sm btn-primary">Save <i
                                class='far fa-save'></i></button>
                        <button type="button" id='addContacts' style='display:none;' class="btn-sm btn-primary">New <i
                                class="fas fa-plus"></i></button>
                        <button type="button" id='cancel' style='display:none;' class="btn-sm btn-primary">Cancel <i class="fas fa-sync"
                                aria-hidden="true"></i></button>
                    </div>
                    <br />
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <table id='edit' class="points_table">
    
                        <tr id='header'>
                            <th>Action</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
    
                        @foreach(array_values($contacts) as $i => $c)
                        @if( $i%2 == 0)
                        <tr style="background:#efefef;color:#333;">
                        @else
                        <tr style="background:#fff;color:#333;">
                        @endif
                            <td><button type="button" class="remove btn-sm btn-primary"><i class="fas fa-times"></i></button></td>
                            <td>
                                <div class="input-group">
                                    <input name='{{$i}}[firstname]' class='name form-control' value='{{$c->firstname}}' required style="width: 70px">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-gray" style="border-left:0;border-right:0"> </span>
                                    </div>
                                    <input name='{{$i}}[lastname]' class='name form-control' value='{{$c->lastname}}' required style="width: 70px;">
                                </div>
                            </td>
                            <td class="email-cell">
                                <input name='{{$i}}[email]' value='{{$c->email}}' class='communication email form-control' required
                                    style="width: 215px;display:inline" title='you@example.com' pattern='^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$'>
                            </td>
                            <td class="phone-cell">
                                {!! countrySelector($c->country, NULL, $i . "[country]", 'form-control form-control-sm',
                                'required style="width:70px;display:inline"') !!}
                                <input value="{{ $c->phone }}" class='communication phone form-control' name='{{$i}}[phone]' placeholder="8888888888"
                                    title="8888888888" pattern="[0-9]{10}" maxlength="10" required style="width: 115px;display:inline">
                            </td>
                            <input type="hidden" name="{{$i}}[id]" value="{{$c->id}}">
                        </tr>
                        @endforeach
                    </table>
                </form>
                @if($hasContacts)
                <table id='view' class="points_table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                        </tr>
                    </thead>
    
                    <tbody class="points_table_scrollbar">
                        @foreach(array_values($contacts) as $i=>$c)
    
    
                        @if( $i%2 == 0)
                        <tr class="even">
                            @else
                        <tr class="odd">
                            @endif
                            <td>{{ "$c->firstname $c->lastname" }}</td>
                            <td>{{ $c->email }}</td>
                            <td>{{ $c->phone }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <h3 id='noContacts' style='margin-bottom:25px'>You don't have any contacts yet</h3>
                @endif
            </div>
        </div>
    </div>
</div>
{{-- @if ($contactsAlerted)
    <p>Your contacts have been alerted</p>
    <button id='return-late' onclick="document.location = '{{ url('/trips/returnedlate') }}'">I have returned</button>
@elseif ($hasActiveTrip)
    <p>You have an active trip</p>
    <p id='address'>Destination: {{ $activeTripInfo['destination_address'] }}</p>
    <p>You will be returning {{ $activeTripInfo['end'] }}</p>
    <p>The following people will be notified:</p>
    <ul>
        @foreach ($activeTripInfo['contacts'] as $c)
            <li>{{$c['firstname']}} {{$c['lastname']}}</li>
        @endforeach
    </ul>
    <button id='return'>I have returned</button>
    <form action="{{ action('TripController@returned') }}" method="POST" id='form' hidden>
        {{ csrf_field() }}
        <input type="checkbox" name="email" id='email-checkbox' id="1" checked>
        <label for="email-checkbox">My contacts will be notified</label>
        <br>
        <button type="submit">Confirm</button>
        <button type='button' id='cancel'>Cancel</button>
    </form>
@else
    <a href='{{ url('trips/create_trip') }}'>Start your trip</a>
@endif --}}
@endsection