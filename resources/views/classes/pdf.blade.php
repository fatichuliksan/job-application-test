@foreach($data as $d)
    <table cellpadding="2">
        <tr>
            <td width="20%">Class</td>
            <td style="text-align: center" width="5%">:</td>
            <td>{{$d->name}}</td>
        </tr>
        <tr>
            <td width="20%">Teacher</td>
            <td style="text-align: center" width="5%">:</td>
            <td>{{($d->teacher)?$d->teacher->name:'-'}}</td>
        </tr>
    </table>
    <table border="1" cellpadding="2">
        <tr>
            <th style="text-align: center" width="10%"><b>No</b></th>
            <th style="text-align: center" width="90%"><b>Name</b></th>
        </tr>
        @foreach($d->students as$n=> $i)
            <tr>
                <td style="text-align: center">{{$n+1}}</td>
                <td>{{$i->name}}</td>
            </tr>
        @endforeach
    </table>
    <br>
    <br>
    <br>
@endforeach