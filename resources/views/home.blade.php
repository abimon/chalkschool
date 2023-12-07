@extends('layouts.app')

@section('content')

<div class="container">

    <div class="row justify-content-center">
        <div class="page-breadcrumb bg-white">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <h4 class="page-title">Dashboard</h4>
                </div>
                @if(Auth()->user()->role == 'Admin')
                <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                    <div class="d-md-flex">
                        <ol class="breadcrumb ms-auto">
                            <li><a href="#" class="fw-normal"></a></li>
                        </ol>
                        <button type="button" data-toggle="modal" data-target="#exampleModal" class="btn btn-primary  d-none d-md-block pull-right ms-3 hidden-xs hidden-sm waves-effect waves-light text-white">Create Course</button>
                        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form method="post" action="/course/create" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Create Course</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-floating">
                                                <input type="text" name="title" id="" class="form-control mb-3" placeholder=' '>
                                                <label for="">Title</label>
                                            </div>

                                            <select name="category" id="" class="form-control mb-3" required>
                                                <option value="" selected disabled>Select category</option>
                                                <option value="Online">Online</option>
                                                <option value="Physical">Physical</option>
                                            </select>
                                            <div class="form-floating">
                                                <input type="text" name="fee" id="" class="form-control mb-3" placeholder=' '>
                                                <label for="">Fee</label>
                                            </div>

                                            <div class="form-floating">
                                                <input type="text" name="duration" id="" class="form-control mb-3" placeholder='x days/weeks'>
                                                <label for="">Duration</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        {{ __('You are logged in!') }}
                    </div>

                </div>

            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row d-flex flex-wrap justify-content-center">
            @foreach($courses as $course)
            <div class="col-lg-4 col-md-6 p-2">
                <div class="white-box analytics-info d-flex flex-column h-100 justify-content-between">
                    <h5 class="box-title">{{$course->title}}</h5>
                    <h6 class="box-title text-danger">({{$course->category}} Course)</h6>
                    <ul class="list-inline two-part d-flex align-items-center mb-0">
                        <li class="ms-auto">KShs. <span class="counter">{{number_format($course->fee)}}</span></li>
                        <li class="ms-auto"><span class="counter text-success">{{$course->duration}}</span></li>
                    </ul>
                    <div class="d-flex justify-content-between">
                        <button data-toggle="modal" data-target="#{{$course->unit_code}}" class="btn btn-primary">Enroll</button>
                        <div class="modal fade" id="{{$course->unit_code}}" tabindex="-1" role="dialog" aria-labelledby="{{$course->unit_code}}Label" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="{{$course->unit_code}}Label">Enroll for {{$course->title}}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="post" action="/student/create/{{$course->id}}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <p>The course {{$course->title}}({{$course->unit_code}}) takes {{$course->duration}} and costs Kshs. {{$course->fee}}. It is offered {{$course->category}}.
                                                @if(($course->category)=='Physical')
                                                The fee include accommodation expenses.
                                                @endif
                                            </p>
                                            <p>Are sure you want to enroll for this course?</p>
                                            <?php $cohorts = ['Jan-March', 'April-June', 'July-September', 'October-December']; ?>
                                            <div class="row">
                                                <label for="cohort" class="col-md-4 col-form-label text-md-end">{{ __('Cohort') }}</label>
                                                <div class="col-md-8">
                                                    <select name="cohort" id="" class="form-control" required>
                                                        <option value="" selected disabled>Select your cohort</option>
                                                        @foreach($cohorts as $cohort)
                                                        <option value="{{$cohort}}">{{$cohort}}</option>
                                                        @endforeach
                                                    </select>

                                                    @error('cohort')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">No</button>
                                            <button type="submit" class="btn btn-success">Yes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @if(Auth()->user()->role=='Admin')
                        <a href="/enrolls/{{$course->unit_code}}"><button class="btn btn-success">Check Enrollments</button></a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection