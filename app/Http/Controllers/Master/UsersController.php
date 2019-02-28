<?php

namespace App\Http\Controllers\Master;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use DB;

class UsersController extends Controller
{
    private $url = '/master/users';
    private $pathViews = 'master.users.';

    public function index()
    {
        $parameters = [
            'url' => $this->url,
        ];
        return view($this->pathViews . 'index', $parameters);
    }

    public function create()
    {
        $data = new User();
        $parameters = [
            'url' => $this->url,
            'data' => $data
        ];
        return view($this->pathViews . 'form', $parameters);
    }

    public function edit($id)
    {
        if ($id) {
            $data = User::find($id);
        } else {
            $data = new User();
        }
        $parameters = [
            'url' => $this->url,
            'data' => $data
        ];
        return view($this->pathViews . 'form', $parameters);
    }

    public function save(Request $request)
    {

        $id = $request['id'];
        $rules = [
            'name' => 'required|max:225',
        ];

        if (empty($id)) {
            $rules = array_merge($rules,
                [
                    'password' => 'required|min:5',
                    'email' => 'email|unique:users|max:100'
                ]);
        } else {
            $rules = array_merge($rules,
                [
                    'email' => 'email|max:100|unique:users,email,' . $id
                ]);
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            $err = '<ul>';
            foreach ($validator->errors()->all() as $e) {
                $err .= "<li>" . $e . "</li>";
            }
            $err .= "</ul>";
            return redirect()->back()->with(['message' => messageAlert($err, 'danger')]);
        }

        try {
            if ($id) {
                $data = User::find($id);
            } else {
                $data = new User();
                $data->password = bcrypt($request['password']);
            }
            $data->name = $request['name'];
            $data->email = $request['email'];
            $data->save();
            return redirect($this->url)->with(['message' => messageAlert('Successfully saved!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => messageAlert($e->getMessage(), 'danger')]);
        }

    }

    public function delete($id)
    {
        try {
            $data = User::find($id);
            $data->delete();
            return redirect($this->url)->with(['message' => messageAlert('Successfully deleted!')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['message' => messageAlert($e->getMessage(), 'danger')]);
        }
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
            $orderBy = 'id';
            $orderType = 'desc';
        }

        $total = User::count();
        if ($search) {
            $filteredTotal = User::where(db::raw("lower(name)"), "like", "%$search%")->orWhere(db::raw("lower(email)"), "like", "%$search%")->count();
        } else {
            $filteredTotal = $total;
        }

        $data = User::where(db::raw("lower(name)"), "like", "%$search%")->orWhere(db::raw("lower(email)"), "like", "%$search%")
            ->offset($offset)
            ->limit($limit)
            ->orderBy($orderBy, $orderType)
            ->get();
        $num = 1;
        $result = [];
        foreach ($data as $dt) {
            $urlEdit = url($this->url . '/' . $dt->id . '/edit');
            $actionData = '<a href="' . $urlEdit . '" class="btn btn-sm btn-info" title="Edit">Edit</a>';
            $actionData .= '<form action="' . url($this->url . '/' . $dt->id . '/delete') . '" method="post" style="display: inline;">
                               ' . csrf_field() . method_field('DELETE') . '
                               <button type="submit" class="btn btn-sm btn-danger" title="Delete">Delete</button>
                            </form>';
            array_push($result, array(
                $num,
                $dt->name,
                $dt->email,
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
}