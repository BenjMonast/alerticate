@extends('layouts.main')

@section('body')
    <p>Hey there, you need to create contacts before you can create a trip.</p>
    <a href="{{ url('/profile') }}" style='color: blue;'>Profile Page</a>
@endsection