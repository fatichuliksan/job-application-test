@extends('layouts.app')
@section('title','Teacher of Class')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Teacher of Class</div>

                    <div class="card-body">
                        <div class="row">

                            <div class="col-md-12">
                                <form method="POST" action="{{ url($url .'/student/save')}}">
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
                                               class="col-sm-2 col-form-label text-md-right">Name of Class</label>

                                        <div class="col-md-6">
                                            <input type="text" id="name" placeholder="Name"
                                                   class="form-control"
                                                   name="name" value="{{($data->teacher)?$data->teacher->name:'-'}}"
                                                   disabled>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="name"
                                               class="col-sm-2 col-form-label text-md-right">Students</label>

                                        <div class="col-md-6">
                                            <select name="student_id" id="" class="form-control" required>
                                                <option value="">choose</option>
                                                @foreach($studentsData as $i)
                                                    <option value="{{$i->student_id}}">{{$i->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row mb-0">
                                        <label class="col-sm-2 col-form-label text-md-right"></label>
                                        <div class="col-md-6">
                                            <input type="hidden" name="id" value="{{$data->classroom_id}}">
                                            <input type="submit" class="btn btn-sm btn-primary">
                                            <a href="{{url($url)}}" class="btn btn-sm btn-danger">Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-md-12">
                                <div style="overflow-x:auto;">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Name</th>
                                            <th width="10%">Action</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')

    <style>
        table th {
            text-align: center;
        }

        table tbody tr .center {
            text-align: center;
        }
    </style>
@endsection

@section('js')
    <script type="text/javascript">

        $(document).ready(function () {
            url = '{{$url}}/student/datatable/{{$data->classroom_id}}';
            $('table').dataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: url,
                "lengthMenu": [[10, 25, 50, 0], [10, 25, 50, "All"]],
                dom: '<"html5buttons"B>lTfgitp',
                columnDefs: [
                    {"targets": 0, "orderable": false, 'className': 'center'},
                    {"targets": 1, "name": "name"},
                    {"targets": 2, "name": "action", "orderable": false, 'className': 'center'},
                ],
                "order": [[1, "asc"]],
            });
        });
    </script>
@endsection