<?php

namespace App\Http\Controllers;
use JWTAuth;

use App\Models\Poll;
use App\Http\Resources\PollResource;

use App\Models\Choice;
use App\Http\Resources\ChoiceResource;

use App\Models\Vote;
use App\Http\Resources\VoteResource;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Carbon\Carbon;

class MainController extends BaseController
{
    public function store_poll(Request $request) {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'description' => 'string|required',
            'deadline' => 'required|date',
            'choice.*' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->send_error('You have a wrong field!', $validator->messages(), 400);
        }

        if (count($request->choice) < 2) {
            return $this->send_error('Choice must be more than 2 option', 'Invalid fields', 400);
        }
        
        try {
            $poll = new Poll();
            $poll->title = $request->title;
            $poll->description = $request->description;
            $poll->deadline = Carbon::parse($request->deadline);
            $poll->created_by = JWTAuth::authenticate($request->token)->id;
            $poll->save();

            foreach ($request->choice as $data) {
                Choice::create([
                    'choice' => $data,
                    'poll_id' => $poll->id
                ]);
            }

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'deadline' => Carbon::parse($request->deadline),
                'choice' => $request->choice
            ];

            return $this->send_response('Store Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function get_poll(Request $request) {
        $limit = 2;
        $page = 1;

        if (isset($request->page)) {
            $page = $request->page;
        }

        if (isset($request->limit)) {
            $limit = $request->limit;
        }

        $offset = $page * $limit;

        try {
            $data = PollResource::collection(Poll::orderBy('created_at','desc')->offset(0)->limit(10)->get());
            return $this->send_response('Fetching Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function get_poll_id(Request $request, $id) {
        try {
            $query = Poll::find($id);
            $data = new PollResource($query);
            return $this->send_response('Fetching Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function vote(Request $request, $poll_id, $choice_id) {
        try {
            $user = JWTAuth::authenticate($request->token);
            $is_avaiablePoll = Poll::find($poll_id);
            if (!$is_avaiablePoll) {
                return $this->send_error('Invalid data request!', 'Poll data not found', 404);
            }

            $is_avaiableChoice = Choice::where('id', '=', $choice_id)
                                        ->where('poll_id', '=', $poll_id)->exists();
            if (!$is_avaiableChoice) {
                return $this->send_error('Invalid data request!', 'Choice data not found', 404);
            }

            $wasVote = Vote::where('user_id', '=', $user->id)
                            ->where('poll_id', '=', $poll_id)->exists();
            
            if ($wasVote) {
                return $this->send_error('Cannot vote again, this user was vote', 'Unauthorized', 401);
            }

            $vote = [
                'choice_id' => $choice_id,
                'poll_id' => $poll_id,
                'user_id' => $user->id,
                'division_id' => $user->division_id
            ];
            $query = Vote::create($vote);
            return $this->send_response('Vote Successfully', $query, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function delete_poll(Request $request, $id) {
        try {
            $query = Poll::find($id);
            $query->delete();
            return $this->send_response('Delete Poll Data Successfully!', $query, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function show_poll_trash() {
        try {
            $query = Poll::onlyTrashed()->get();
            $data = PollResource::collection($query);
            return $this->send_response('Fetching Trash Poll Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function restore_poll_id($id) {
        try {
            $data = Poll::withTrashed()->find($id)->restore();
            return $this->send_response('Restore Trash Poll Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }

    public function force_delete_poll($id) {
        try {
            $data = Poll::withTrashed()->find($id)->forceDelete();
            return $this->send_response('Force Delete Trash Poll Data Successfully!', $data, 200);
        } catch (Throwable $e) {
            return $this->send_error('Something Wrong!', $e, 500);
        }
    }
}
