<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use Redirect,Response;

class UserController extends Controller
{

public function index(Request $request)
{
if ($request->ajax()) {
$data = User::latest()->get();
return Datatables::of($data)
->addIndexColumn()
->addColumn('action', function($row){

$action = '<a class="btn btn-info" id="show-user" data-toggle="modal" data-id='.$row->id.'>Show</a>
<a class="btn btn-success" id="edit-user" data-toggle="modal" data-id='.$row->id.'>Edit </a>
<meta name="csrf-token" content="{{ csrf_token() }}">
<a id="delete-user" data-id='.$row->id.' class="btn btn-danger delete-user">Delete</a>';

return $action;

})
->rawColumns(['action'])
->make(true);
}

return view('users');
}

public function store(Request $request)
{

$r=$request->validate([
'name' => 'required',
'email' => 'required',

]);

$uId = $request->user_id;
User::updateOrCreate(['id' => $uId],['name' => $request->name, 'email' => $request->email]);
if(empty($request->user_id))
$msg = 'User created successfully.';
else
$msg = 'User data is updated successfully';
return redirect()->route('users.index')->with('success',$msg);
}



public function show($id)
{
$where = array('id' => $id);
$user = User::where($where)->first();
return Response::json($user);
//return view('users.show',compact('user'));
}


public function edit($id)
{
$where = array('id' => $id);
$user = User::where($where)->first();
return Response::json($user);
}



public function destroy($id)
{
$user = User::where('id',$id)->delete();
return Response::json($user);
// return redirect()->route('users.index');
}
}