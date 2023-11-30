@extends('layouts.app')
@section('dashboard')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-4 col-xlg-3 col-md-12">
            <div class="white-box">
                <div class="user-bg"> <img width="100%" alt="user" src="https://ausaakenya.com/storage/profile/{{Auth()->user()->profile}}">
                    <div class="overlay-box">
                        <div class="user-content">
                            <a href="javascript:void(0)"><img src="https://ausaakenya.com/storage/profile/{{Auth()->user()->profile}}" class="thumb-lg img-circle" alt="img"></a>
                            <h4 class="text-white mt-2">{{Auth()->user()->name}}</h4>
                            <h5 class="text-white mt-2">{{Auth()->user()->email}}</h5>
                            <div class='d-flex justify-content-center mt-2 mb-4'>
                                <a href="" data-bs-toggle="modal" data-bs-target="#image"><button class="btn btn-light">Change</button></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-btm-box mt-5 d-md-flex">
                    <div class="text-center">
                        <h1>+{{Auth()->user()->contact}}</h1>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 col-xlg-9 col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="post" action='/profile_update' class="form-horizontal form-material">
                        @csrf
                        <div class="form-floating mt-1">
                            <input type="text" disabled name="name" class="form-control" id="floatingInput" placeholder=" " value="{{Auth()->user()->name}}">
                            <label for="floatingInput">Name</label>
                        </div>
                        <div class="form-floating mt-1">
                            <input type="tel" disabled name="contact" class="form-control" id="floatingPassword" placeholder=" " value="{{Auth()->user()->contact}}">
                            <label for="floatingPassword">Contact</label>
                        </div>
                        <div class="form-floating mt-1">
                            <input type="email" disabled name="email" class="form-control" id="floatingInput" placeholder=" " value="{{Auth()->user()->email}}">
                            <label for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating mt-1">
                            <input type="text" name="residence" class="form-control" id="floatingInput" placeholder=" " value="{{Auth()->user()->current_residence}}">
                            <label for="floatingInput">Current Residence</label>
                        </div>
                        <div class='form-floating mb-2'>
                            <select name='chapter' class='form-control' required>
                                <?php $chapters = ['Nairobi', 'Eastern', 'Central', 'Rift Valley', 'Nyanza', 'Western', 'North Eastern', 'Coast', 'Diaspora']; ?>
                                <option value="{{Auth()->user()->chapter}}" class='form-control'>{{Auth()->user()->chapter}}</option>
                                @foreach($chapters as $chapter)
                                    <option value="{{$chapter}}">{{$chapter}}</option>
                                    @endforeach
                            </select>
                            <label for="inst">Current Chapter</label>
                            @error('chapter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class='form-floating mb-2'>
                            <select name='inst' class='form-control' required>
                                <?php $insts = ['JKUAT', 'MKU', 'KCA', 'CUK','KMTC', 'Other']; ?>
                                <option value="{{Auth()->user()->inst}}" class='form-control'>{{Auth()->user()->inst}}</option>
                                @foreach($insts as $inst)
                                <option value="{{$inst}}" class='form-control'>{{$inst}}</option>
                                @endforeach
                            </select>
                            <label for="inst">Affliate Institution</label>
                            @error('chapter')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="form-group mb-4">
                            <div class="col-sm-12">
                                <button type="submit" class="btn btn-success">Update Profile</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection