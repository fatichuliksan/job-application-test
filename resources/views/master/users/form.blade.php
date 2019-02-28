@extends('layouts.app')
@section('title',($data->id)?'Edit User':'Create User')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{($data->id)?'Edit User':'Create User'}}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ url($url .'/save')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="name"
                                       class="col-sm-2 col-form-label text-md-right">Name</label>

                                <div class="col-md-6">
                                    <input type="text" id="name" placeholder="Name"
                                           class="form-control"
                                           name="name" value="{{$data->name}}" required autofocus>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email"
                                       class="col-sm-2 col-form-label text-md-right">Email</label>

                                <div class="col-md-6">
                                    <input type="email" id="email" placeholder="Email"
                                           class="form-control"
                                           name="email" value="{{$data->email}}" required autofocus>
                                </div>
                            </div>
                            @if(empty($data->id))
                                <div class="form-group row">
                                    <label for="password"
                                           class="col-sm-2 col-form-label text-md-right">Password</label>
                                    <div class="col-md-6">
                                        <input type="password" id="password" placeholder="Password"
                                               class="form-control"
                                               name="password" value="{{$data->password}}" required autofocus>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group row mb-0">
                                <label class="col-sm-2 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <input type="hidden" name="id" value="{{$data->id}}">
                                    <input type="submit" class="btn btn-sm btn-primary" value="Save">
                                    <a href="{{url($url)}}" class="btn btn-sm btn-danger">Back</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection