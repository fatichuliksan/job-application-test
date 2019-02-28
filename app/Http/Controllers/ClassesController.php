<?php

namespace App\Http\Controllers;

use App\Classroom;
use App\Student;
use App\Teacher;
use Illuminate\Http\Request;
use DB;
use PDF;

class ClassesController extends Controller
{
    private $url = '/classes';
    private $pathViews = 'classes.';

    public function index()
    {
        $parameters = [
            'url' => $this->url,
        ];
        return view($this->pathViews . 'index', $parameters);
    }

    public function dataTable(Request $request)
    {
        $search = strtolower($request->input('search')['value']);
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');

        $orderArr = $request->input('order');
        $orderArr = $orderArr[0];
        $orderByColumnIndex = $orderArr['column']; // index of the sorting column (0 index based - i.e. 0 is the first record)
        $orderType = $orderArr['dir']; // ASC or DESC

        $orderBy = $request->input('columns');
        $orderBy = $orderBy[$orderByColumnIndex]['name'];//Get name of the sorting column from its index

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'classroom_id';
            $orderType = 'desc';
        }

        $total = Classroom::count();
        if ($search) {
            $filteredTotal = Classroom::leftjoin('teachers', 'teachers.teacher_id', 'classrooms.teacher_id')
                ->where(db::raw("lower(classrooms.name)"), "like", "%$search%")
                ->orWhere(db::raw("lower(teachers.name)"), "like", "%$search%")->count();
        } else {
            $filteredTotal = $total;
        }

        $data = Classroom::leftjoin('teachers', 'teachers.teacher_id', 'classrooms.teacher_id')
            ->where(db::raw("lower(classrooms.name)"), "like", "%$search%")
            ->orWhere(db::raw("lower(teachers.name)"), "like", "%$search%")
            ->select('classrooms.classroom_id', 'classrooms.name as class_name', 'teachers.name as teacher_name')
            ->offset($offset)
            ->limit($limit)
            ->orderBy(db::raw($orderBy), $orderType)
            ->get();
        $num = 1;
        $result = [];
        foreach ($data as $dt) {
            $urlEdit = url($this->url . '/' . $dt->classroom_id . '/edit');
            $actionData = '<a href="' . url($this->url . '/teacher/' . $dt->classroom_id) . '" class="btn btn-sm btn-info" title="Teacher">Teacher</a>';
            $actionData .= '<a href="' . url($this->url . '/student/' . $dt->classroom_id) . '" class="btn btn-sm btn-warning" title="Students">Students</a><br>';

            array_push($result, array(
                $num,
                $dt->class_name,
                $dt->teacher_name,
                count($dt->students),
                $actionData,
            ));
            $num++;
        }
        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filteredTotal,
            'data' => $result,
        ]);
    }

    public function teacher($id)
    {
        $parameters = [
            'url' => $this->url,
            'data' => Classroom::find($id),
            'teachersData' => Teacher::orderBy('name')->get(),
        ];
        return view($this->pathViews . 'teacher', $parameters);
    }

    public function teacherSave(Request $request)
    {
        $id = $request['id'];
        try {
            $data = Classroom::find($id);
            $data->teacher_id = $request['teacher_id'] ?: null;
            $data->save();
            return redirect($this->url)->with(['message' => messageAlert('Successfully saved!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => messageAlert($e->getMessage(), 'danger')]);
        }
    }

    public function student($id)
    {
        $parameters = [
            'url' => $this->url,
            'data' => Classroom::find($id),
            'studentsData' => Student::where('classroom_id', null)->orderBy('name')->get(),
        ];
        return view($this->pathViews . 'student', $parameters);
    }

    public function studentSave(Request $request)
    {
        try {
            $data = Student::find($request['student_id']);
            $data->classroom_id = $request['id'] ?: null;
            $data->save();
            return redirect($this->url . '/student/' . $request['id'])->with(['message' => messageAlert('Successfully saved!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => messageAlert($e->getMessage(), 'danger')]);
        }
    }

    public function studentDelete($studentId)
    {
        try {
            $data = Student::find($studentId);
            $id = $data->classroom_id;
            $data->classroom_id = null;
            $data->save();
            return redirect($this->url . '/student/' . $id)->with(['message' => messageAlert('Successfully saved!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => messageAlert($e->getMessage(), 'danger')]);
        }
    }

    public function studentDatatable(Request $request, $id)
    {
        $search = strtolower($request->input('search')['value']);
        $draw = $request->input('draw');
        $limit = $request->input('length');
        $offset = $request->input('start');

        $orderArr = $request->input('order');
        $orderArr = $orderArr[0];
        $orderByColumnIndex = $orderArr['column']; // index of the sorting column (0 index based - i.e. 0 is the first record)
        $orderType = $orderArr['dir']; // ASC or DESC

        $orderBy = $request->input('columns');
        $orderBy = $orderBy[$orderByColumnIndex]['name'];//Get name of the sorting column from its index

        if ($orderBy && $orderType) {
            $orderBy = $orderBy;
            $orderType = $orderType;
        } else {
            $orderBy = 'student_id';
            $orderType = 'desc';
        }

        $total = Student::where('classroom_id', '=', $id)->count();
        if ($search) {
            $filteredTotal = Student::where('classroom_id', '=', $id)
                ->where(db::raw("lower(name)"), "like", "%$search%")->count();
        } else {
            $filteredTotal = $total;
        }

        $data = Student::where('classroom_id', '=', $id)
            ->where(db::raw("lower(name)"), "like", "%$search%")
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $orderType)
            ->get();
        $num = 1;
        $result = [];
        foreach ($data as $dt) {
            $actionData = '<form action="' . url($this->url . '/student/' . $dt->student_id . '/delete') . '" method="post" style="display: inline;">
                               ' . csrf_field() . method_field('DELETE') . '
                               <button type="submit" class="btn btn-sm btn-danger" title="Delete">Delete</button>
                            </form>';
            array_push($result, array(
                $num,
                $dt->name,
                $actionData,
            ));
            $num++;
        }
        echo json_encode([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $filteredTotal,
            'data' => $result,
        ]);
    }

    public function pdf()
    {
        $parameters = [
            'data' => Classroom::orderBy('name')->get(),
        ];
        PDF::SetTitle('Class, Teacher and Student List');
        PDF::SetMargins(10, 5, 10, true);

        PDF::AddPage('P', 'F4');
        PDF::SetDisplayMode('fullpage', 'SinglePage', 'UseNone');
        PDF::SetAutoPageBreak(TRUE, 0);
        $view = view($this->pathViews . 'pdf', $parameters);
        PDF::WriteHTML($view->render());
        ob_end_clean(); // Clean (erase) the output buffer and turn off output buffering
        PDF::Output('Class, Teacher and Student List.pdf');
    }
}
