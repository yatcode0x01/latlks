<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Vote;
use App\Http\Resources\VoteResource;

use Illuminate\Http\Request;
use Throwable;

class VoteController extends BaseController
{
    public function index()
    {
        try {
            $query = Vote::all();
            $data = VoteResource::collection($query);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $insert = $request->all();
            $insert['user_id'] = 1;
            $insert['division_id'] = 1;
            $query = Vote::create($insert);
            $data = new VoteResource($query);
            return $this->send_response('Store Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function show(Vote $vote)
    {
        try {
            $data = new VoteResource($vote);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function update(Request $request, Vote $vote)
    {
        try {
            $vote->update($request->all());
            $data = new VoteResource($vote);
            return $this->send_response('Update Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function destroy(Vote $vote)
    {
        try {
            $vote->delete();
            $data = new VoteResource($vote);
            return $this->send_response('Delete Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }
}
