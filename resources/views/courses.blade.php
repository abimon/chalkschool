@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <h3 class="box-title">Courses Details</h3>
                @if($items->count()>0)
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th class="border-top-0">#</th>
                                @if(Auth()->user()->role =='Admin')
                                <th class="border-top-0">Name</th>
                                @endif
                                <th class="border-top-0">Unit Code</th>
                                <th class="border-top-0">Total Fee</th>
                                <th class="border-top-0">Paid Fee</th>
                                <th class="border-top-0">Last Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $key=>$item)
                            <tr>
                                <td>{{$key+1}}</td>
                                @if(Auth()->user()->role == 'Admin')
                                <td><a href="#" data-toggle="modal" data-target="#students{{$key+1}}">{{$item->name}}</a></td>
                                @endif
                                <td>{{$item->title}}<br><small class="text-warning">{{$item->duration}}  {{$item->category}}</small></td>
                                <td>{{$item->fee}}</td>
                                <td>{{$item->paid}}</td>
                                <td>{{($item->updated_at)->diffForHumans()}}</td>
                                <div class="modal fade" id="students{{$key+1}}" tabindex="-1" role="dialog" aria-labelledby="edit{{$key+1}}Label" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="edit{{$key+1}}Label">Student Detail</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                                <div class="modal-body">
                                                    <p><i class="fa fa-user"></i> {{$item->name}}</p>
                                                    <p><i class="fa fa-phone"></i> {{$item->contact}}</p>
                                                    <p><i class="fa fa-envelope"></i> {{$item->email}}</p>
                                                    <p><i class="fa fa-location-dot"></i> {{$item->residence}}</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                </div>
                                        </div>
                                    </div>
                                </div>
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