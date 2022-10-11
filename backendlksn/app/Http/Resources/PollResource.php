<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;
use App\Models\Choice;
use App\Models\Vote;
use App\Models\User;

use JWTAuth;
class PollResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $user = JWTAuth::authenticate($request->token);
        $userId = $user->id;
        
        $is_voted = Vote::where('user_id', '=', $userId)
                            ->where('poll_id', '=', $this->id)->count();
        if ($is_voted > 0) {
            $is_voted = true;
        } else {
            $is_voted = false;
            $res_data = [];
        }

        if ($user->role == 'admin') {
            $is_voted = true;
        }

        $dataChoice = Choice::where('poll_id', '=', $this->id)->get();
        $results = array();

        foreach ($dataChoice as $data) {
            $isVote = Vote::where('poll_id', '=', $this->id)->get();
            $isChoice = Vote::where('poll_id', '=', $this->id)->where('choice_id', '=', $data->id)->get();

            $countVote = count($isVote);
            $countIsChoice = count($isChoice);

            if ($countIsChoice != 0) {
                $point = $countIsChoice / $countVote * 100;
            } else {
                $point = 0;
            }

            if ($is_voted == true) {
                $results[] = [
                    'choice_id' => $data->id,
                    'choice' => $data->choice,
                    'point' => $point,
                    'count' => $countIsChoice
                ];
            } else {
                $results[] = [
                    'choice_id' => $data->id,
                    'choice' => $data->choice,
                    'point' => 'hide',
                    'count' => 'hide'
                ];
            }
        }

        $option = array();
        $dataChoice = Choice::where('poll_id', '=', $this->id)->get('choice');
        foreach ($dataChoice as $arr) {
            $option[] = $arr->choice;
        }
        
        
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'option' => $option,
            'deadline' => Carbon::parse($this->deadline)->toDateString('Y-M-D'),
            'is_past' => Carbon::parse($this->deadline)->isPast(),
            'is_voted' => $is_voted,
            'created_by' => $this->created_by,
            'creator' => User::find($this->created_by)->username,
            'created_at' => $this->created_at,
            'results' => $results,
            'updated_at' => $this->update_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
