<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Choice;
use App\Http\Resources\ChoiceResource;

use Illuminate\Http\Request;
use Throwable;

class ChoiceController extends BaseController
{
    public function index()
    {
        try {
            $query = Choice::all();
            $data = ChoiceResource::collection($query);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $query = Choice::create($request->all());
            $data = new ChoiceResource($query);
            return $this->send_response('Store Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function show(Choice $choice)
    {
        try {
            $data = new ChoiceResource($choice);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function update(Request $request, Choice $choice)
    {
        try {
            $choice->update($request->all());
            $data = new ChoiceResource($choice);
            return $this->send_response('Update Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function destroy(Choice $choice)
    {
        try {
            $choice->delete();
            $data = new ChoiceResource($choice);
            return $this->send_response('Delete Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }
}
