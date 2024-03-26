@extends('layouts.main')

@section('title', 'Welcome to FindMe')

@section('head')
<style>
    .error {
        color:red;
        top: 30px;
        position: relative;
    }
</style>
@endsection

@section('body')
<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <div style="margin-top:120px"></div>
            <h1>What is Alerticate?</h1>
            <p class="about-text">Alerticate is a free service that gives you peace of mind the next time you go on a trip. If you don't return from your trip when you say you will, your contacts will be notified, and you can stay safe. It's really easy to sign up and set up your contacts, so you can be prepared the next time you go on vacation.</p>
        </div>
        <div class="col-md-4">
            <div class="form-login bg-light">
                    <h2 class="text-center form-heading" style="margin-bottom:30px">Login</h2>
                    <form method="POST" action="{{ action('Auth\LoginController@login') }}" accept-charset="UTF-8" class="login-form">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <input required="required" name="username" type="text" id="username" class="form-control" placeholder="Username">
                        </div>
                        <div class="form-group">
                            <input name="password" type="password" value="" id="password" class="form-control pwd" placeholder="Password">
                        </div>

                        <a href="{{url('/forgotpassword')}}" class="float-right">Forgot Password</a>

                        <span class='error'>{{session('error')}}</span>

                        <input type="submit" value="Log In" class="btn btn-login" />

                        <span>Don't have an account? <a href="./register">Sign-up Now</a></span>
                    </form>
    
    
            </div>
        </div>
    </div>
</div>
{{-- <p style='text-align:center;font-size:50px;'>Home page goes here</p>
<div id='login'>
    <form method="POST" action="{{ action('Auth\LoginController@login') }}" accept-charset="UTF-8">
        {{ csrf_field() }}
        <label for="username">Username:</label>
        <input required="required" name="username" type="text" id="username">
        <br>
        <label for="password">Password:</label>
        <input name="password" type="password" value="" id="password">
        <br>
        <input type="submit" value="Log In">
    </form>
    <a href='./register' style='color:blue;'>No Account? Create One.</a>
</div> --}}
@endsection