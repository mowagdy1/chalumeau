<?php

namespace App\Http\Controllers\Dashboard;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    public function all()
    {
        $users = User::orderBy('id', 'desc')->paginate(15);
        return view('dashboard.users.all', compact('users'));
    }

    public function admins()
    {
        $users = User::where(['role' => 'admin'])->orderBy('id', 'desc')->paginate(15);
        return view('dashboard.users.admins', compact('users'));
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed',
        ]);
        $user = new User;
        $user->name = $request['name'];
        $user->role = $request['role'];
        $user->email = $request['email'];
        $user->password = bcrypt($request['password']);
        if ($user->save()) {
            return redirect('dashboard/users/all')->with('message', 'User added successfully!')->with('class', 'alert-success');
        }
        return redirect('dashboard/users/all')->with('message', 'Failed creating the user')->with('class', 'alert-danger');
    }

    public function edit($id)
    {
        if ($user = User::find($id)) {
            return view('dashboard.users.edit', compact('user'));
        }
        return redirect('dashboard/users/all')->with('message', 'This user does not exist in DB')->with('class', 'alert-danger');
    }

    public function update(Request $request)
    {
        $rules = array(
            'userId' => 'required',
            'name' => 'required',
            'role' => 'required',
            'email' => 'required|email',
            'password' => 'confirmed',
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return response()->json(array(
                'success' => false,
                'msg' => 'Validation Error.',
                'errors' => $validator->errors()->all()
            ), 400);
        }

        if ($user = User::find($request['userId'])) {
            $user->name = $request['name'];
            $user->role = $request['role'];
            $user->email = $request['email'];
            if ($request->password) {
                $user->password = bcrypt($request['password']);
            }
            if ($user->update()) {

                return response()->json(array('msg' => 'User updated successfully.', 'class' => 'alert-success'), 200);
            }
            return response()->json(array('msg' => 'Failed updating this user.', 'class' => 'alert-danger'), 200);
        }
        return response()->json(array('msg' => 'This user did not exist in DB.', 'class' => 'alert-danger'), 200);
    }

    public function delete(Request $request)
    {
        if ($user = User::find($request->userId)) {
            $user->delete();
            return response()->json(array('msg' => 'User deleted successfully.', 'class' => 'alert-success'), 200);
        }
        return response()->json(array('msg' => 'Failed in deleting.', 'class' => 'alert-danger'), 200);
    }


}
