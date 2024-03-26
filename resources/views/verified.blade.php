@extends('layouts.main')

@section('title', 'Verification')

@section('body')
    <div class="container bg-light rounded">
        <div class="row">
            <div class="col">
                @if (session()->get('success'))
                    Your email is verified and your account is activated
                @else
                    Something went wrong
                @endif
            </div>
        </div>
    </div>
@endsection