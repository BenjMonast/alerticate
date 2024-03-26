@extends('layouts.main')

@section('title','Forgot Password')

@section('body')
<form action="{{action("Auth\RegisterController@forgotPassword")}}" method="POST">
    {{ csrf_field() }}
    <div class="container bg-light rounded">
        <div class="row">
            <div class="col">
                <div class="card-body" style='text-align:center;'>
                    <h2 class="form-heading">Forgot Password</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col" style="text-align:center">
                <label for="email">Enter the email address that you created your account with:</label>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style='display:flex;justify-content:center;align-items:center;'>
                    <input type="text" name="email" id="email" class="form-control" style='width:250px' pattern='^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$' placeholder='Email' required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div style='display:flex;justify-content:center;align-items:center;'>
                    <input id="submit" type="submit" value="Send Reset Link" class="btn btn-primary btn-block btn-lg" style="width:200px;margin:15px"/>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection