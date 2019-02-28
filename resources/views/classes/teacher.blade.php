@extends('layouts.app')
@section('title','Teacher of Class')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Teacher of Class</div>

                    <div class="card-body">
                        <form method="POST" action="{{ url($url .'/teacher/save')}}">
                            @csrf
                            <div class="form-group row">
                                <label for="name"
                                       class="col-sm-2 col-form-label text-md-right">Name of Class</label>

                                <div class="col-md-6">
                                    <input type="text" id="name" placeholder="Name"
                                           class="form-control"
                                           name="name" value="{{$data->name}}" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"
                                       class="col-sm-2 col-form-label text-md-right">Teacher</label>

                                <div class="col-md-6">
                                    <select name="teacher_id" id="" class="form-control">
                                        <option value="">empty</option>
                                        @foreach($teachersData as $i)
                                            <option value="{{$i->teacher_id}}" {{($data->teacher_id==$i->teacher_id)?'selected':''}}>{{$i->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <label class="col-sm-2 col-form-label text-md-right"></label>
                                <div class="col-md-6">
                                    <input type="hidden" name="id" value="{{$data->classroom_id}}">
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
