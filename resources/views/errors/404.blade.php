@extends('layouts.main')

@section('title', '404 Not Found')

@section('body')
<div class="container bg-light rounded" style="padding-bottom:25px;">
    <div class="row">
        <div class="col">
            <div class="card-body" style='text-align:center;padding-bottom:0px;'>
                <h2 class="form-heading">File Not Found</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" style="text-align:center;">
            This app may be able to find you, but it can't find this file
        </div>
    </div>
</div>
@endsection