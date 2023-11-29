@extends('layouts.app')
@section('content')
<div class="container-xxl py-6" style="margin-top: 50px;">
    <div class="row justify-content-center">
        @if (session('message'))
        <div class="alert alert-danger">{{ session('message') }}</div>
        @endif
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Make Payment') }}</div>

                <div class="card-body">
                    <form method="POST" action="/fees/create/{{$unit_code}}">
                        @csrf
                        <div class="row mb-3">
                            <label for="unit_code" class="col-md-4 col-form-label text-md-end">{{ __('Course Code') }}</label>
                            <div class="col-md-6">
                                <input id="unit_code" type="text" class="form-control @error('unit_code') is-invalid @enderror" name="unit_code" value="{{$unit_code}}" required autocomplete="unit_code">
                                @error('unit_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="contact" class="col-md-4 col-form-label text-md-end">{{ __('Phone No.') }}</label>
                            <div class="col-md-6">
                                <input id="contact" type="number" class="form-control @error('contact') is-invalid @enderror" name="contact" value="{{Auth()->user()->contact}}" required autocomplete="contact" maxlength="13" minlength="9">
                                @error('contact')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="amount" class="col-md-4 col-form-label text-md-end">{{ __('Amount') }}</label>

                            <div class="col-md-6">
                                <input id="amount" type="number" class="form-control @error('amount') is-invalid @enderror" name="amount" value={{$fee}} required autocomplete="amount" maxlength="13" minlength="9">

                                @error('amount')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Pay Fee') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection