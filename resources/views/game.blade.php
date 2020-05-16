@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-4">
            <img src="{{ $image }}" />
        </div>
        <div class="col-8">
            <h4>{{ $title }}</h4>
            <p><span class="badge badge-warning strike">${{ $price }}</span><span class="badge badge-success">Free</span></p>
        </div>
    </div>
</div>
<div>
    <div id="app" class="content" data-device="{{ $device }}">
        <div class="container"><div class="row justify-content-center" style="margin-top: 1rem;"><div class="col-12"><div class="card"><div class="card-header" style="text-align: center;"><p style="margin-bottom: 0px;font-size: 1.0rem;font-weight: bolder;/* text-decoration: underline; */">Step 1. Select your device/platform</p><p style="margin-bottom: 0px;font-weight: bolder;/* text-decoration: underline; */">Tap on your platform</p></div><div class="card-body"><div class="row"><button class="btn btn-link col-6" style="padding: 1rem; border: 1px solid black; border-radius: 2.25rem; margin-right: 1rem; margin-left: 0.5rem;"><img src="{{ asset('/images/android.png') }}" style="width: 50%;"></button><button class="btn btn-link col-6" style="padding: 1rem; border: 1px solid black; border-radius: 2.25rem;"><img src="{{ asset('/images/ios.png') }}" style="width: 50%;"></button></div></div><div class="card-footer"><div></div></div></div></div></div></div>
    </div>
</div>
<script type="text/javascript" src="{{ mix('/js/app.js') }}"></script>
@endsection
