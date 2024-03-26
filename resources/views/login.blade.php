@extends('layouts.main')

@section('title','Login')

@section('head')
<style>
    .error {
        color: red;
    }
    a {
        color: blue;
    }
</style>
@endsection

@section('body')
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
<br>
<a href='./register'>No Account? Create One.</a>
@if (isset($error))
    <p class='error'>{{$error}}</p>
@endif
@endsection