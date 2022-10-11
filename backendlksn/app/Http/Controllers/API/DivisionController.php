<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Division;
use App\Http\Resources\DivisionResource;

use Illuminate\Http\Request;
use Throwable;

class DivisionController extends BaseController
{
    public function index()
    {
        try {
            $query = Division::all();
            $data = DivisionResource::collection($query);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $query = Division::create($request->all());
            $data = new DivisionResource($query);
            return $this->send_response('Store Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function show(Division $division)
    {
        try {
            $data = new DivisionResource($division);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function update(Request $request, Division $division)
    {
        try {
            $division->update($request->all());
            $data = new DivisionResource($division);
            return $this->send_response('Update Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function destroy(Division $division)
    {
        try {
            $division->delete();
            $data = new DivisionResource($division);
            return $this->send_response('Delete Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }
}
