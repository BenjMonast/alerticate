@extends('layouts.main')

@section('title','Reset Password')

@section('head')
<script>
    $(function() {
        var password = $('#password');
        var confirmPassword = $('#confirmPassword');

        function validatePassword() {
            if (password.val() != confirmPassword.val()) {
                password[0].setCustomValidity("Passwords Don't Match");
            } else if (!(password.val().match(/[a-z]/g) && password.val().match(/[A-Z]/g) && password.val().match(/[0-9]/g) && password.val().length >= 8)) {
                password[0].setCustomValidity("Password must have one uppercase and lowercase letter, a number, and must have 8 characters");
            } else {
                password[0].setCustomValidity("");
            }
        }

        password[0].onchange = validatePassword;
        confirmPassword[0].onchange = validatePassword;

        function passwordChanged() {
            password[0].setCustomValidity("");
        }

        password[0].oninput = passwordChanged;
        confirmPassword[0].oninput = passwordChanged;
    });

</script>
@endsection

@section('body')
<form action="{{action("Auth\RegisterController@resetPassword")}}" id='form' method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="id" value="{{$id}}">
    <input type="hidden" name="token" value="{{$token}}">
    <div class="container bg-light rounded">
        <div class="row">
            <div class="col">
                <div class="card-body" style='text-align:center;padding-bottom:0px;'>
                    <h2 class="form-heading">Reset Password</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" style="text-align:center">
                <label for="password">Enter your new password:</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style='display:flex;justify-content:center;align-items:center;'>
                    <input type="password" name="password" id="password" class="form-control" style='width:250px' placeholder='Password' required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" style="text-align:center">
                <label for="confirmPassword" style="margin-top:10px">Confirm your new password:</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style='display:flex;justify-content:center;align-items:center;'>
                    <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" style='width:250px' placeholder=' Confirm Password' required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style='display:flex;justify-content:center;align-items:center;'>
                    <input id="submit" type="submit" value="Reset Password" class="btn btn-primary btn-block btn-lg" style="width:200px;margin:15px"/>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection