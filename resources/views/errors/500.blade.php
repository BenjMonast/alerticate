@extends('layouts.main')

@section('title', 'Something Went Wrong')

@section('body')
<div class="container bg-light rounded" style="padding-bottom:25px;">
    <div class="row">
        <div class="col">
            <div class="card-body" style='text-align:center;padding-bottom:0px;'>
                <h2 class="form-heading" style="text-transform:inherit">Uh Oh</h2>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col" style="text-align:center;">
            Something Went Wrong
        </div>
    </div>
</div>
@endsection