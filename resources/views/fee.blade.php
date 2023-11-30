@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Payment Details</h3>
                @if(count($items)>0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th class="border-top-0">#</th>
                                <th class="border-top-0">Account</th>
                                <th class="border-top-0">Transacted Amount</th>
                                <th class="border-top-0">Transaction Id</th>
                                <th class="border-top-0">Phone Number</th>
                                <th class="border-top-0">Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{$item->unit_code}}</td>
                                <td>{{$item->TransAmount}}</td>
                                <td>{{$item->MpesaReceiptNumber}}</td>
                                <td>{{$item->PhoneNumber}}</td>
                                <td>{{($item->updated_at)->diffForHumans()}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection