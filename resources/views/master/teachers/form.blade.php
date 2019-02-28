@extends('layouts.app')
@section('title',($data->teacher_id)?'Edit Teacher':'Create Teacher')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{($data->teacher_id)?'Edit Teacher':'Create Teacher'}}</div>

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

                            <div class="form-group row mb-0">
                                <label class="col-sm-2 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <input type="hidden" name="id" value="{{$data->teacher_id}}">
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
