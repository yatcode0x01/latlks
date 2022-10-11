<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController as BaseController;

use App\Models\Poll;
use App\Http\Resources\PollResource;

use Illuminate\Http\Request;
use Throwable;

use Carbon\Carbon;

class PollController extends BaseController
{
    public function index()
    {
        try {
            $query = Poll::all();
            $data = PollResource::collection($query);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $insert = $request->all();
            $insert['deadline'] = Carbon::parse($request->deadline);
            $insert['created_by'] = $user = JWTAuth::authenticate($request->token)->id;
            $query = Poll::create($insert);
            $data = new PollResource($query);
            return $this->send_response('Store Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function show(Poll $poll)
    {
        try {
            $data = new PollResource($poll);
            return $this->send_response('Fetching Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function update(Request $request, Poll $poll)
    {
        try {
            $query = $request->all();
            $query['created_by'] = 2;
            $poll->update($query);
            $data = new PollResource($poll);
            return $this->send_response('Update Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }

    public function destroy(Poll $poll)
    {
        try {
            $poll->delete();
            $data = new PollResource($poll);
            return $this->send_response('Delete Data Success!', $data);
        } catch (Throwable $error) {
            return $this->send_error('Something Wrong!', $error, 404);
        }
    }
}
