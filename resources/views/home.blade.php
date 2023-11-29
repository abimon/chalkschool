@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row d-flex flex-wrap justify-content-center" >
            @foreach($courses as $course)
            <div class="col-lg-4 col-md-6 p-2">
                <div class="white-box analytics-info d-flex flex-column h-100 justify-content-between">
                    <h5 class="box-title">{{$course->title}}</h5>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li class="ms-auto">KShs. <span class="counter">{{number_format($course->fee)}}</span></li>
                        <li class="ms-auto"><span class="counter text-success">{{$course->duration}}</span></li>
                    </ul>
                    <div style="align-items: bottom;"><button class="btn btn-success">Enroll</button></div>
                </div>
                
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection