<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController as BaseController;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware('jwt.verify:admin');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|unique:users',
            'password' => 'required|string',
            'role' => 'required|string',
            'division_id' => 'required|integer|exists:divisions,id',
        ]);

        if ($validator->fails()) {
            $this->send_error('You have a wrong fields!', $validator->messages(), 400);
        }
        
        try {
            $data = User::create([
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'division_id' => $request->division_id
            ]);
            $this->send_response('Succsessfully!', 'Create User Successfully!', 200);
        } catch (Throwable $e) {
            $this->send_error('Internal Server Errors!', $e, 500);
        }
    }

    // public function index()
    // {
    //     $data = User::all();
    //     $this->send_response('Fetching data succsessfully!', $data, 200);
    //     try {
    //         $data = User::all();
    //         $this->send_response('Fetching data succsessfully!', $data, 200);
    //     } catch (Throwable $e) {
    //         $this->send_error('Internal Server Errors!', $e, 500);
    //     }
    // }

    // public function show($id)
    // {
    //     try {
    //         $data = User::find($id);
    //         $this->send_response('Fetching data succsessfully!', $data, 200);
    //     } catch (Throwable $e) {
    //         $this->send_error('Fetching data failed!', $e, 404);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'username' => 'required',
    //         'role' => 'required|string',
    //         'division_id' => 'required|integer|exists:divisions,id',
    //     ]);

    //     if ($validator->fails()) {
    //         $this->send_error('You have a wrong fields!', $validator->messages(), 400);
    //     }
        
    //     try {
    //         $user = User::where('username', '=', $request->username)->get('username');
    //         if (count($user) < 1 || $user == User::find($id)->username) {
    //             $data = User::find($id);
    //             $data->username = $request->username;
    //             if ($request->password) {
    //                 $data->password = bcrypt($request->password);
    //             }
    //             $data->role = $request->role;
    //             $data->division_id = $request->division_id;
    //             $data->update();

    //             $this->send_response('Store data succsessfully!', $data, 200);   
    //         }
    //         $this->send_error('Update data failed!', 'Username already exsists!', 400);
    //     } catch (Throwable $e) {
    //         $this->send_error('Internal Server Errors!', $e, 500);
    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $data = User::find($id);
    //         $data->delete();
    //         $this->send_response('Fetching data succsessfully!', $data, 200);
    //     } catch (Throwable $e) {
    //         $this->send_error('Fetching data failed!', $e, 404);
    //     }
    // }
}
